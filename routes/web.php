<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\MainCategoryController;
use App\Http\Controllers\Admin\CategoryTypeController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\ReviewController;
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
    Route::resource('reviews', ReviewController::class);
    Route::get('reviews-trashed', [ReviewController::class, 'trashed'])->name('reviews.trashed');
    Route::patch('reviews/{id}/restore', [ReviewController::class, 'restore'])->name('reviews.restore');
    Route::delete('reviews/{id}/force-delete', [ReviewController::class, 'forceDelete'])->name('reviews.force-delete');

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

// Seasonal routes
Route::get('/seasonal/{season}', [App\Http\Controllers\SeasonalController::class, 'activities'])->name('seasonal.activities');

// Wishlist routes
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/check', [App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Booking routes
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store')->middleware('auth');
