@extends('public.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/index.css') }}">
    <style>
        :root {
            --primary: #92400b;
            --primary-dark: #793509;
            --secondary: #b85c38;
            --accent: #e09132;
            --light: #f7f1e5;
            --dark: #2d2424;
            --white: #ffffff;
        }

        /* General styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="hero-content">
                        <h1 class="hero-title">Discover the Magic of Jordan</h1>
                        <p class="hero-text">Experience ancient wonders, desert adventures, and rich culture in one
                            extraordinary destination</p>

                        <!-- Search Box -->
                        <div class="search-box">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="destination" class="form-label">Where to?</label>
                                    <input type="text" class="form-control" id="destination" placeholder="Destination">
                                </div>
                                <div class="col-md-4">
                                    <label for="checkin" class="form-label">Check in</label>
                                    <div class="date-input">
                                        <input type="text" class="form-control" id="checkin" placeholder="DD/MM/YYYY">
                                        <i class="far fa-calendar"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="duration" placeholder="Duration">
                                </div>
                                <div class="col-12 mt-3">
                                    <button class="search-button">Search Experiences</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden Places Competition Banner -->
    <section class="competition-section">
        <div class="container py-5">
            <div class="competition-banner">
                <div class="row g-0">
                    <div class="col-md-6 competition-content">
                        <h2 class="competition-title">Hidden Places Competition</h2>
                        <p class="competition-text">Share your secret spot in Jordan and win a luxury trip worth $5000!</p>
                        <a href="#" class="btn btn-light competition-btn">Enter Now</a>
                    </div>
                    <div class="col-md-6 competition-image">
                        <img src="../assets/images/hiddenGem.jpg" alt="Hidden canyon in Jordan" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trip Cards Section -->
    <section class="trips-section">
        <div class="container">
            <h2 class="section-title text-center">Discover Jordan's Top Trips</h2>

            @if ($tripActivities->isNotEmpty())
                <div class="owl-carousel owl-theme">
                    @foreach ($tripActivities as $activity)
                        <!-- Trip Card -->
                        <div class="item">
                            <div class="trip-card">
                                <a href="{{ route('activity.detail', $activity->id) }}" class="card-link"></a>
                                <div class="card-img-wrapper">
                                    <div class="trip-badge">{{ $activity->categoryType->name }}</div>
                                    <button class="favorite-btn" data-activity-id="{{ $activity->id }}">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <img src="{{ asset($activity->image) }}" class="card-img-top"
                                        alt="{{ $activity->name }}">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-3">{{ $activity->name }}</h5>
                                    <div class="trip-info mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span
                                                class="text-primary fw-bold">${{ number_format($activity->price, 2) }}</span>
                                            <span
                                                class="{{ $activity->remaining_capacity <= 5 ? 'text-danger' : 'text-muted' }} fw-bold">
                                                <i class="fas fa-users"></i> {{ $activity->remaining_capacity }} left
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-secondary">
                                                <i
                                                    class="fas fa-clock me-1"></i>{{ $activity->formatted_duration ?? '3 Days' }}
                                            </span>
                                            @if ($activity->is_family_friendly)
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-child me-1"></i>Family Friendly
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="card-text text-muted">
                                        {{ Str::limit($activity->description, 100) }}
                                    </p>
                                    <a href="{{ route('activity.detail', $activity->id) }}" class="btn book-now-btn">Book
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info">
                            <h4 class="mb-0">No Activities Available</h4>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Restaurant Section -->
    <section class="restaurant-section py-5">
        <div class="container">
            <h2 class="section-title text-center">Top Restaurants in Jordan</h2>

            @if ($featuredRestaurants->isNotEmpty())
                <div class="owl-carousel restaurant-carousel owl-theme">
                    @foreach ($featuredRestaurants as $restaurant)
                        <!-- Restaurant Card -->
                        <div class="item">
                            <div class="restaurant-card">
                                <div class="card-image">
                                    <img src="{{ asset($restaurant->image) }}" alt="{{ $restaurant->name }}" />
                                </div>
                                <div class="card-content">
                                    <h3 class="restaurant-name">{{ $restaurant->name }}</h3>
                                    <div class="rating">
                                        <div class="stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($restaurant->avg_rating ?? 5))
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - 0.5 == round($restaurant->avg_rating ?? 5))
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span
                                            class="rating-number">{{ number_format($restaurant->avg_rating ?? 5, 1) }}</span>
                                    </div>
                                    <div class="details">
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $restaurant->location }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-wallet"></i>
                                            <span>{{ $restaurant->price_range }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-utensils"></i>
                                            <span>{{ $restaurant->cuisine_type }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info">
                            <h4 class="mb-0">No Available Restaurants</h4>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Loyalty & Referral Programs Section -->
    <section class="loyalty-section py-5">
        <div class="container">
            <div class="row">
                <!-- Loyalty Program -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="loyalty-card">
                        <div class="loyalty-header">
                            <span class="loyalty-icon"><i class="fas fa-crown"></i></span>
                            <h3 class="loyalty-title">Loyalty Program</h3>
                        </div>
                        <p class="loyalty-text">Earn points with every booking and unlock exclusive rewards</p>

                        <div class="loyalty-benefits">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="benefit-value">500</div>
                                    <div class="benefit-label">Points per booking</div>
                                </div>
                                <div class="col-4">
                                    <div class="benefit-value">10%</div>
                                    <div class="benefit-label">Cashback</div>
                                </div>
                                <div class="col-4">
                                    <div class="benefit-value">VIP</div>
                                    <div class="benefit-label">Access</div>
                                </div>
                            </div>
                        </div>

                        <a href="#" class="btn btn-loyalty w-100 mt-auto">Join Now</a>
                    </div>
                </div>

                <!-- Refer & Earn -->
                <div class="col-md-6">
                    <div class="referral-card">
                        <div class="referral-header">
                            <span class="referral-icon"><i class="fas fa-gift"></i></span>
                            <h3 class="referral-title">Refer & Earn</h3>
                        </div>
                        <p class="referral-text">Invite friends and get rewards for each successful referral</p>

                        <div class="referral-reward">
                            <div class="reward-amount">$50</div>
                            <div class="reward-text">For each friend who books</div>
                        </div>

                        <a href="#" class="btn btn-referral w-100 mt-auto">Get Referral Link</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('mainJS/index.js') }}"></script>
    <script src="{{ asset('mainJS/wishlist.js') }}"></script>
@endpush
