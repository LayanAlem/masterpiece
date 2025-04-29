<?php

namespace App\Providers;

use App\Models\MainCategory;
use App\Models\Booking;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for database schema
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        // Use Bootstrap for pagination styling
        \Illuminate\Pagination\Paginator::useBootstrap();
        // Share main categories with all views
        View::composer('*', function ($view) {
            $view->with('mainCategories', MainCategory::all());

            // Add booking information to the navbar if user is logged in
            if (Auth::check()) {
                $user = Auth::user();

                // Modified query to only show bookings that are pending or have incomplete payment
                $pendingBookings = Booking::where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->where('status', 'pending')
                            ->orWhere('payment_status', '!=', 'completed');
                    })
                    ->with('activities')
                    ->get();

                $view->with([
                    'bookingCount' => $pendingBookings->count(),
                    'pendingBookings' => $pendingBookings
                ]);
            }
        });
    }
}
