<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'activities']);

        // Apply status filter
        if ($request->filled('status') && is_array($request->status)) {
            $query->whereIn('status', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                        $userQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('activities', function (Builder $activityQuery) use ($search) {
                        $activityQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply ticket count filter
        if ($request->filled('min_tickets')) {
            $query->where('ticket_count', '>=', $request->min_tickets);
        }

        if ($request->filled('max_tickets')) {
            $query->where('ticket_count', '<=', $request->max_tickets);
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('total_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('total_price', '<=', $request->max_price);
        }

        // Apply user filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Apply activity filter
        if ($request->filled('activity_id')) {
            $query->whereHas('activities', function (Builder $q) use ($request) {
                $q->where('activities.id', $request->activity_id);
            });
        }

        // Get sorting parameters
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['created_at', 'status', 'ticket_count', 'total_price', 'booking_number'];
        $sortField = in_array($sortField, $allowedSortFields) ? $sortField : 'created_at';

        // Apply sorting
        $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');

        // Paginate the results
        $bookings = $query->paginate(10)->withQueryString();

        // Get data for filter dropdowns
        $users = User::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $activities = Activity::select('id', 'name')->orderBy('name')->get();
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        return view('admin.bookings.index', compact('bookings', 'users', 'activities', 'statuses'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'activities', 'participants', 'payments'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function create()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $activities = Activity::select('id', 'name', 'price')->orderBy('name')->get();
        return view('admin.bookings.create', compact('users', 'activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_id' => 'required|array',
            'activity_id.*' => 'exists:activities,id',
            'ticket_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,refunded',
        ]);

        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'ticket_count' => $validated['ticket_count'],
            'total_price' => $validated['total_price'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
        ]);

        // Attach activities
        $booking->activities()->attach($validated['activity_id']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function edit($id)
    {
        $booking = Booking::with(['user', 'activities'])->findOrFail($id);
        $users = User::select('id', 'first_name', 'last_name', 'email')->orderBy('first_name')->get();
        $activities = Activity::select('id', 'name', 'price')->orderBy('name')->get();

        return view('admin.bookings.edit', compact('booking', 'users', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Check if this is a quick status update from the table
        if ($request->has('quick_update')) {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,completed,cancelled',
            ]);

            $oldStatus = $booking->status;
            $newStatus = $validated['status'];

            $booking->update([
                'status' => $newStatus,
            ]);

            // If status is being changed to "completed", add loyalty points to the user
            if ($oldStatus != 'completed' && $newStatus == 'completed') {
                $this->processCompletedBookingPoints($booking);
            }

            return redirect()->route('bookings.index')
                ->with('success', 'Booking status updated successfully.');
        }

        // Regular full update
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,refunded,failed',
            'ticket_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'activity_id' => 'required|array',
            'activity_id.*' => 'exists:activities,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'numeric|min:0',
            'activity_date' => 'required|array',
            'activity_date.*' => 'date',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        // Update booking
        $booking->update([
            'user_id' => $validated['user_id'],
            'status' => $newStatus,
            'payment_status' => $validated['payment_status'],
            'ticket_count' => $validated['ticket_count'],
            'total_price' => $validated['total_price'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
        ]);

        // If status is being changed to "completed", add loyalty points to the user
        if ($oldStatus != 'completed' && $newStatus == 'completed') {
            $this->processCompletedBookingPoints($booking);
        }

        // Sync activities with pivot data
        $activities = [];
        foreach ($validated['activity_id'] as $index => $activityId) {
            $activities[$activityId] = [
                'quantity' => $validated['quantity'][$index],
                'unit_price' => $validated['unit_price'][$index],
                'activity_date' => $validated['activity_date'][$index],
            ];
        }

        $booking->activities()->sync($activities);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->activities()->detach();
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking has been cancelled.');
    }

    /**
     * Process loyalty points when a booking is marked as completed
     * This adds the earned points to the user's account
     *
     * @param \App\Models\Booking $booking
     * @return void
     */
    protected function processCompletedBookingPoints($booking)
    {
        // Only proceed if there are points to add and the status is now completed
        if ($booking->loyalty_points_earned <= 0 || $booking->status !== 'completed') {
            return;
        }

        $pointsService = app(\App\Services\PointsService::class);
        $user = $booking->user;

        // Add the points that were calculated when booking was created but not yet added to user account
        $pointsToAdd = $booking->loyalty_points_earned;
        $pointsService->addPoints($user, $pointsToAdd, 'booking_completed');

        // Log the points addition
        \Log::info("Added {$pointsToAdd} loyalty points to user {$user->id} for completed booking {$booking->id}");
    }
}
