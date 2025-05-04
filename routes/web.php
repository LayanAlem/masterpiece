<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\MainCategoryController;
use App\Http\Controllers\Admin\CategoryTypeController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Public Routes
Route::get(uri: '/', action: [App\Http\Controllers\HomeController::class, 'index'])->name(name: 'public.index');

Auth::routes();

Route::get('test', function () {
    return view('public.pages.servicesPage');
})->name('test');


// VisitJo Public Routes
Route::prefix('VisitJo')->group(function () {
    Route::get('/bookings', function () {
        return view('public.pages.bookings');
    })->name('bookings');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout')->middleware('auth');
    Route::get('/payment', function () {
        return view('public.pages.payment');
    })->name(name: 'payment');
    Route::get('/upload', function () {
        return view('public.pages.competitionUpload');
    });
    Route::get('/activity/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activity.detail');
    Route::get('/detailed', function () {
        return view('public.pages.detailed');
    });
    Route::get('/about', action: function () {
        return view('public.pages.about');
    })->name(name: 'about');
    Route::get('/hiddengem', function () {
        return view('public.pages.hiddengem');
    })->name('hiddengem');

    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/category/{categoryId}', [ServiceController::class, 'categoryActivities'])->name('category.activities');
    Route::get('bookingpart', function () {
        return view('public.pages.bookingParticipants');
    })->name('bookingpart');
    Route::get('contact', function () {
        return view('public.pages.contact');
    })->name('contact');
});

// Profile Routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');



// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// Admin Routes - with authentication
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    Route::resource('admins', AdminController::class);
    Route::resource('users', UserController::class);
    Route::resource('activities', ActivityController::class);

    // Categories
    Route::resource('main-categories', MainCategoryController::class);
    Route::get('main-categories-trashed', [MainCategoryController::class, 'trashed'])->name('main-categories.trashed');
    Route::patch('main-categories/{id}/restore', [MainCategoryController::class, 'restore'])->name('main-categories.restore');
    Route::delete('main-categories/{id}/force-delete', [MainCategoryController::class, 'forceDelete'])->name('main-categories.forceDelete');
    Route::delete('main-categories/empty-trash', [MainCategoryController::class, 'emptyTrash'])->name('main-categories.empty-trash');

    Route::resource('category-types', CategoryTypeController::class);
    Route::get('category-types-trashed', [CategoryTypeController::class, 'trashed'])->name('category-types.trashed');
    Route::patch('category-types/{id}/restore', [CategoryTypeController::class, 'restore'])->name('category-types.restore');
    Route::delete('category-types/{id}/force-delete', [CategoryTypeController::class, 'forceDelete'])->name('category-types.forceDelete');
    Route::delete('category-types/empty-trash', [CategoryTypeController::class, 'emptyTrash'])->name('category-types.empty-trash');

    // Bookings
    Route::resource('bookings', AdminBookingController::class);
    Route::patch('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    // Restaurants
    Route::resource('restaurants', RestaurantController::class);
    Route::get('restaurants-trashed', [RestaurantController::class, 'trashed'])->name('restaurants.trashed');
    Route::patch('restaurants/{id}/restore', [RestaurantController::class, 'restore'])->name('restaurants.restore');
    Route::delete('restaurants/{id}/force-delete', [RestaurantController::class, 'forceDelete'])->name('restaurants.forceDelete');
    Route::post('restaurants/feature/add', [RestaurantController::class, 'addToFeatured'])->name('restaurants.feature.add');
    Route::delete('restaurants/feature/remove', [RestaurantController::class, 'removeFromFeatured'])->name('restaurants.feature.remove');

    // Reviews
    Route::resource('reviews', AdminReviewController::class)->names([
        'index' => 'admin.reviews.index',
        'create' => 'admin.reviews.create',
        'store' => 'admin.reviews.store',
        'show' => 'admin.reviews.show',
        'edit' => 'admin.reviews.edit',
        'update' => 'admin.reviews.update',
        'destroy' => 'admin.reviews.destroy',
    ]);
    Route::get('reviews-trashed', [AdminReviewController::class, 'trashed'])->name('admin.reviews.trashed');
    Route::patch('reviews/{id}/restore', [AdminReviewController::class, 'restore'])->name('admin.reviews.restore');
    Route::delete('reviews/{id}/force-delete', [AdminReviewController::class, 'forceDelete'])->name('admin.reviews.force-delete');
    Route::post('reviews/bulk-update', [AdminReviewController::class, 'bulkUpdate'])->name('admin.reviews.bulk-update');
    Route::post('reviews/{id}/update-status', [AdminReviewController::class, 'updateStatus'])->name('admin.reviews.update-status');

    // Activities Trashed
    Route::get('activities-trashed', [ActivityController::class, 'trashed'])->name('activities.trashed');
    Route::patch('activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');
    Route::delete('activities/{id}/force-delete', [ActivityController::class, 'forceDelete'])->name('activities.forceDelete');

    // Admin Management with trash
    Route::get('admins-trashed', [AdminController::class, 'trashed'])->name('admins.trashed');
    Route::patch('admins/{id}/restore', [AdminController::class, 'restore'])->name('admins.restore');
    Route::delete('admins/{id}/force-delete', [AdminController::class, 'forceDelete'])->name('admins.forceDelete');

    // Inside the admin prefix route group, add these routes
    Route::get('users-trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
});

// Admin panel user management routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // User management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // User loyalty points
    Route::post('/users/{id}/points', [UserController::class, 'updatePoints'])->name('users.points.update');

    // User referral code
    Route::post('/users/{id}/referral-code', [UserController::class, 'regenerateReferralCode'])->name('users.referral.regenerate');

    // User referral balance
    Route::post('/users/{id}/referral-balance', [UserController::class, 'updateReferralBalance'])->name('users.referral-balance.update');

    // Trashed users
    Route::get('/users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');

    // Other admin routes...
});

// Seasonal routes
Route::get('/seasonal/{season}', [App\Http\Controllers\SeasonalController::class, 'activities'])->name('seasonal.activities');

// Wishlist routes
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/check', [App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Booking routes
// Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store')->middleware('auth');

// User profile and bookings routes
Route::middleware('auth')->group(function () {
    // Cancel a booking
    Route::post('/booking/{booking}/cancel', [App\Http\Controllers\BookingController::class, 'cancel'])->name('booking.cancel');
});

// User reviews
Route::middleware(['auth'])->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/quick', [ReviewController::class, 'quickReview'])->name('reviews.quick');
});
