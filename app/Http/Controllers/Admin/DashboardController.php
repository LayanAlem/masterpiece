<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Skip all the complex data collection for now
        $admin = auth()->guard('admin')->user();

        // Return a very basic view
        return view('admin.index', [
            'admin' => $admin,
            'totalUsers' => 0,
            'totalActivities' => 0,
            'totalRestaurants' => 0,
            'totalBookings' => 0,
            'totalRevenue' => 0,
            'monthlyRevenue' => 0,
            'previousMonthRevenue' => 0,
            'revenuePercentChange' => 0,
            'recentTransactions' => [],
            'monthlyRevenueData' => [],
            'ordersByCategory' => collect([]),
            'eventsByType' => collect([]),
            'topActivities' => collect([]),
            'weeklyExpensesData' => []
        ]);
    }

    private function getCategoryIcon($categoryName)
    {
        $icons = [
            'Adventure' => 'bx-run',
            'Culture' => 'bx-book-open',
            'Food' => 'bx-restaurant',
            'Relaxation' => 'bx-spa',
            'Nature' => 'bx-landscape',
            'Water' => 'bx-water',
            'Entertainment' => 'bx-film',
            'Shopping' => 'bx-store',
            'Family' => 'bx-home-heart',
            'Historical' => 'bx-landmark',
        ];

        return $icons[$categoryName] ?? 'bx-category';
    }

    private function getCategoryColor($categoryName)
    {
        $colors = [
            'Adventure' => 'primary',
            'Culture' => 'info',
            'Food' => 'success',
            'Relaxation' => 'warning',
            'Nature' => 'success',
            'Water' => 'info',
            'Entertainment' => 'danger',
            'Shopping' => 'secondary',
            'Family' => 'warning',
            'Historical' => 'dark',
        ];

        return $colors[$categoryName] ?? 'primary';
    }

    private function getEventIcon($eventType)
    {
        $icons = [
            'Summer Events' => 'bx-sun',
            'Winter Events' => 'bx-snow',
            'Spring Events' => 'bx-leaf',
            'Fall Events' => 'bx-wind',
            'Autumn Events' => 'bx-wind',
            'Upcoming Events' => 'bx-calendar-plus',
            'Ongoing Events' => 'bx-calendar-check',
            'Past Events' => 'bx-calendar-x',
        ];

        return $icons[$eventType] ?? 'bx-calendar-event';
    }

    private function getEventColor($eventType)
    {
        $colors = [
            'Summer Events' => 'warning',
            'Winter Events' => 'info',
            'Spring Events' => 'success',
            'Fall Events' => 'danger',
            'Autumn Events' => 'danger',
            'Upcoming Events' => 'primary',
            'Ongoing Events' => 'success',
            'Past Events' => 'secondary',
        ];

        return $colors[$eventType] ?? 'primary';
    }
}
