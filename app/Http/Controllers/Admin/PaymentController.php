<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user']);

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Search by transaction ID or booking number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($q) use ($search) {
                        $q->where('booking_number', 'like', "%{$search}%");
                    });
            });
        }

        // Apply sorting
        $sortBy = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Get payments with pagination
        $payments = $query->paginate(15)->withQueryString();

        // Get payment statistics
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        $pendingAmount = Payment::where('status', 'pending')->sum('amount');
        $refundedAmount = Payment::where('status', 'refunded')->sum('amount');

        // Payment method distribution
        $paymentMethods = Payment::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.payments.index', compact(
            'payments',
            'totalRevenue',
            'monthlyRevenue',
            'pendingAmount',
            'refundedAmount',
            'paymentMethods'
        ));
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.user', 'booking.activities'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,refunded,failed'
        ]);

        $payment = Payment::findOrFail($id);
        $oldStatus = $payment->status;
        $newStatus = $request->status;

        $payment->status = $newStatus;
        $payment->save();

        // Update booking payment status if applicable
        if ($payment->booking) {
            $booking = $payment->booking;

            if ($newStatus == 'completed') {
                $booking->payment_status = 'paid';
            } elseif ($newStatus == 'refunded') {
                $booking->payment_status = 'refunded';
            } elseif ($newStatus == 'failed') {
                $booking->payment_status = 'failed';
            } else {
                $booking->payment_status = 'pending';
            }

            $booking->save();
        }

        return redirect()->route('payments.show', $payment->id)
            ->with('success', 'Payment status updated successfully.');
    }
}
