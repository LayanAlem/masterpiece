@extends('public.layouts.main')
@section('title', $activity->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/detailed.css') }}">
    <link rel="stylesheet" href="{{ asset('mainStyle/bookingParticipants.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('mainStyle/profile.css') }}">
    <style>
        /* Form styles */
        .info-input {
            display: none;
        }

        #save-info-btn {
            display: none;
        }

        #save-password-btn {
            display: none;
        }

        /* Profile image styles */
        .avatar {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .avatar i.fas.fa-user {
            width: 100%;
            height: 100%;
            font-size: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .info-field .info-value img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--primary, #4e9ca9);
            transition: all 0.3s ease;
        }

        /* Error styling */
        .is-invalid {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + 0.75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback,
        .was-validated .form-select:invalid~.invalid-feedback,
        .form-select.is-invalid~.invalid-feedback {
            display: block;
        }

        /* Show errors in edit mode */
        #personal-info-section.edit-mode .invalid-feedback {
            display: block;
        }

        /* Booking status colors */
        .status-confirmed {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-completed {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .status-cancelled {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Booking card tabs */
        .booking-tabs {
            display: flex;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .booking-tab {
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .booking-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .booking-tab-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background-color: #e9ecef;
            font-size: 0.75rem;
            margin-left: 5px;
        }

        .booking-tab.active .booking-tab-count {
            background-color: var(--primary);
            color: white;
        }

        .booking-content {
            display: none;
            max-height: 500px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .booking-content.active {
            display: block;
        }

        /* Styling for the booking stats summary */
        .stats-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            flex: 1;
            min-width: 120px;
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border-radius: 50%;
            font-size: 18px;
            color: white;
        }

        .stat-primary {
            background-color: var(--primary, #92400b);
        }

        .stat-green {
            background-color: #198754;
        }

        .stat-orange {
            background-color: #fd7e14;
        }

        .stat-purple {
            background-color: #6f42c1;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 3px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Custom scrollbar styling */
        .booking-content::-webkit-scrollbar {
            width: 6px;
        }

        .booking-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .booking-content::-webkit-scrollbar-thumb {
            background: #cdcdcd;
            border-radius: 10px;
        }

        .booking-content::-webkit-scrollbar-thumb:hover {
            background: #92400b;
        }

        .empty-bookings {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .empty-bookings i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        /* Payment Section Styles */
        .payment-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 80px;
            /* Space for fixed button */
        }

        .booking-summary {
            flex: 1;
            min-width: 280px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .payment-section {
            flex: 1.2;
            min-width: 300px;
        }

        .tour-info {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .tour-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .tour-date {
            color: #666;
            font-size: 0.9rem;
        }

        .pricing-table {
            width: 100%;
            margin-top: 15px;
        }

        .pricing-table tr {
            line-height: 2.2;
        }

        .pricing-label {
            color: #555;
        }

        .td-right {
            text-align: right;
        }

        .discount {
            color: #28a745;
            font-weight: 500;
        }

        .total-row {
            font-weight: 700;
            font-size: 1.1em;
            border-top: 1px solid #ddd;
        }

        .regular-price {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #666;
        }

        /* Rewards Section Styles */
        .rewards-card {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .reward-option {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .reward-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .loyalty-icon {
            background: linear-gradient(135deg, #ffb347, #ffcc33);
            color: white;
        }

        .referral-icon {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .section-divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .section-divider::before,
        .section-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .section-divider span {
            margin: 0 10px;
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        /* Custom Range Slider */
        .custom-range {
            height: 8px;
            border-radius: 5px;
        }

        .custom-range::-webkit-slider-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #007bff;
            cursor: pointer;
            -webkit-appearance: none;
            margin-top: -6px;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }

        .custom-range::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #007bff;
            cursor: pointer;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        }

        /* Toggle Switch Style */
        .modern-switch .form-check-input {
            width: 48px;
            height: 24px;
        }

        .modern-switch .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        /* Complete Booking Button */
        .complete-booking-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            text-align: center;
        }

        .payment-button {
            background: linear-gradient(45deg, #2754eb, #5c7ef3);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .payment-button:hover {
            background: linear-gradient(45deg, #1643da, #4b6dee);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .points-value {
            font-size: 0.9rem;
        }

        .discount-badge {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .reward-earn-info {
            background: rgba(0, 123, 255, 0.05);
            border-left: 3px solid #007bff;
            padding: 10px 15px;
            border-radius: 3px;
            font-size: 0.9rem;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .terms-text {
            font-size: 0.9rem;
            margin-top: -2px;
        }

        /* For mobile responsiveness */
        @media (max-width: 768px) {
            .payment-container {
                flex-direction: column;
            }

            .complete-booking-container {
                padding: 10px;
            }

            .payment-button {
                width: 100%;
                padding: 10px;
                font-size: 1rem;
            }
        }

        /* Review section styles */
        .reviews-summary {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .overall-rating {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .rating-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 5px;
        }

        .rating-stars {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .rating-count {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .rating-bars {
            margin-top: 10px;
        }

        .rating-bar-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .rating-label {
            width: 60px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .progress {
            flex: 1;
            height: 8px;
            margin: 0 10px;
            background-color: #e9ecef;
        }

        .rating-count {
            width: 30px;
            text-align: right;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .write-review-prompt {
            background-color: rgba(246, 246, 246, 0.6);
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .write-review-prompt:hover {
            background-color: rgba(246, 246, 246, 1);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .write-review-btn {
            background-color: var(--primary, #92400b);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(146, 64, 11, 0.2);
            display: inline-block;
            text-decoration: none;
            cursor: pointer;
        }

        .write-review-btn:hover {
            background-color: #7a3609;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(146, 64, 11, 0.3);
            color: white;
            text-decoration: none;
        }

        .review-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-approved {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-rejected {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Navbar Dropdown Fix */
        .navbar-nav .nav-item.dropdown .dropdown-menu {
            position: absolute;
            z-index: 1000;
            display: none;
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 0.25rem;
        }

        .navbar-nav .nav-item.dropdown .dropdown-menu.show {
            display: block;
            margin-top: 0;
        }

        .navbar-nav .nav-item.dropdown .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }

        .navbar-nav .nav-item.dropdown .dropdown-toggle:empty::after {
            margin-left: 0;
        }

        .navbar-nav .nav-item.dropdown .dropdown-item {
            display: block;
            width: 100%;
            padding: 0.25rem 1rem;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
        }

        .navbar-nav .nav-item.dropdown .dropdown-item:hover,
        .navbar-nav .nav-item.dropdown .dropdown-item:focus {
            color: #1e2125;
            background-color: #f8f9fa;
        }

        .navbar-nav .nav-item.dropdown .dropdown-item.active,
        .navbar-nav .nav-item.dropdown .dropdown-item:active {
            color: #fff;
            text-decoration: none;
            background-color: #0d6efd;
        }

        /* Override any conflicting navbar styles */
        .nav-link.dropdown-toggle:after {
            display: inline-block !important;
            position: static !important;
            width: auto !important;
            height: auto !important;
            margin-left: 0.255em !important;
            vertical-align: 0.255em !important;
            content: "" !important;
            border-top: 0.3em solid !important;
            border-right: 0.3em solid transparent !important;
            border-bottom: 0 !important;
            border-left: 0.3em solid transparent !important;
            transform: none !important;
            background-color: transparent !important;
        }

        /* Add new styles for review modal */
        .review-modal .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .review-modal .modal-body {
            padding-top: 0;
        }

        .review-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px;
        }

        .review-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .review-modal .rating-large {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .review-modal .review-date {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .review-modal .review-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .review-card {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
        }

        .all-reviews-btn {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .all-reviews-btn:hover {
            background-color: #f1f3f5;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .no-reviews-message {
            text-align: center;
            padding: 30px;
            background-color: rgba(246, 246, 246, 0.6);
            border-radius: 8px;
        }

        .no-reviews-message i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 15px;
        }

        .no-reviews-message h4 {
            margin-bottom: 10px;
        }

        .no-reviews-message p {
            color: #6c757d;
            margin-bottom: 15px;
        }

        /* Star rating styles for review modals */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            font-size: 30px;
            color: #ddd;
            padding: 0 5px;
            transition: color 0.3s ease;
        }

        .star-rating label:hover,
        .star-rating label:hover~label,
        .star-rating input:checked~label {
            color: #FFC107;
        }

        /* Fixes for star rating in edit modal */
        #editStarRating input {
            display: none;
        }

        #editStarRating label {
            cursor: pointer;
            font-size: 30px;
            color: #ddd;
            padding: 0 5px;
        }

        #editStarRating label:hover,
        #editStarRating label:hover~label,
        #editStarRating input:checked~label {
            color: #FFC107;
        }
    </style>
@endpush

@section('content')

    <!-- Activity Content -->
    <div class="activity-content">
        <div class="container">
            <div class="activity-header">
                <h1 class="activity-title">{{ $activity->name }}</h1>
                <div class="activity-meta">
                    <div class="activity-meta-item">
                        <i class="far fa-clock"></i>
                        <span>{{ $activity->formatted_duration }}</span>
                    </div>
                    <div class="activity-meta-item">
                        <i class="fas fa-hiking"></i>
                        <span>{{ $activity->difficulty ?? 'Moderate' }}</span>
                    </div>
                    <div class="activity-meta-item">
                        <i class="fas fa-users"></i>
                        <span>{{ $activity->is_family_friendly ? 'Family Friendly' : 'Small groups' }}</span>
                    </div>
                    <div class="activity-meta-item">
                        <i class="fas fa-star"></i>
                        <span>{{ number_format($activity->reviews_avg_rating ?? 4.5, 1) }}/5
                            ({{ $activity->reviews_count ?? 0 }} reviews)</span>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="photo-gallery">
                @php
                    $primaryImage = null;
                    $additionalImages = collect(); // Initialize as a collection instead of array

                    if (
                        $activity->has_images &&
                        $activity->images instanceof \Illuminate\Database\Eloquent\Collection &&
                        $activity->images->count() > 0
                    ) {
                        // Get the primary image
                        $primaryImage = $activity->images->where('is_primary', true)->first();

                        // Get additional images
                        $additionalImages = $activity->images->where('is_primary', false)->take(2);
                    }
                @endphp

                <div class="photo-item main-photo">
                    <img src="{{ asset($primaryImage ? 'storage/' . $primaryImage->path : 'api/placeholder/800/400') }}"
                        alt="{{ $activity->name }}">
                </div>

                <div class="photo-item sub-photo">
                    @if ($additionalImages->isNotEmpty() && $additionalImages->first())
                        <img src="{{ asset('storage/' . $additionalImages->first()->path) }}"
                            alt="Close-up detail of {{ $activity->name }}">
                    @elseif($primaryImage)
                        <img src="{{ asset('storage/' . $primaryImage->path) }}"
                            alt="Close-up detail of {{ $activity->name }}">
                    @else
                        <img src="{{ asset('api/placeholder/400/200') }}" alt="Close-up detail of {{ $activity->name }}">
                    @endif
                </div>

                <div class="photo-item sub-photo">
                    @if ($additionalImages->count() > 1 && $additionalImages->get(1))
                        <img src="{{ asset('storage/' . $additionalImages->get(1)->path) }}"
                            alt="{{ $activity->name }} additional view">
                    @elseif($primaryImage)
                        <img src="{{ asset('storage/' . $primaryImage->path) }}"
                            alt="{{ $activity->name }} additional view">
                    @else
                        <img src="{{ asset('api/placeholder/400/200') }}" alt="{{ $activity->name }} additional view">
                    @endif
                </div>
            </div>

            <!-- Book Now Button -->
            @if ($activity->is_fully_booked)
                <button class="book-btn disabled" disabled style="background-color: #6c757d; cursor: not-allowed;">
                    <i class="fas fa-calendar-times"></i>
                    Sorry, This Experience is Fully Booked
                </button>
            @else
                @auth
                    <button class="book-btn" id="openBookingModal">
                        <i class="fas fa-calendar-check"></i>
                        Book Your {{ $activity->name }} Experience Now
                        <span class="ms-2 badge rounded-pill bg-light text-dark">
                            {{ $activity->remaining_capacity }} @choice('seat|seats', $activity->remaining_capacity) left
                        </span>
                    </button>
                @else
                    <button class="book-btn" id="openLoginModal">
                        <i class="fas fa-calendar-check"></i>
                        Book Your {{ $activity->name }} Experience Now
                        <span class="ms-2 badge rounded-pill bg-light text-dark">
                            {{ $activity->remaining_capacity }} @choice('seat|seats', $activity->remaining_capacity) left
                        </span>
                    </button>
                @endauth
            @endif

            <!-- Weather & Preparation -->
            <div class="content-card">
                <h2 class="section-title">Weather & Preparation</h2>
                <div class="weather-grid">
                    <div class="current-conditions">
                        <h4>Current Conditions</h4>
                        <div class="current-weather">
                            <div class="temp-display">{{ $weather->current_temp }}°C</div>
                            <div class="weather-desc">{{ $weather->condition }}</div>
                            <div class="weather-details">
                                <div>Humidity: {{ $weather->humidity }}%</div>
                                <div>UV Index: {{ $weather->uv_index }}</div>
                            </div>
                        </div>
                        <div class="forecast">
                            <h5 class="mt-4 mb-2">Today's Forecast</h5>
                            <div class="forecast-row">
                                @foreach ($weather->forecast as $forecast)
                                    <div class="forecast-item">
                                        <div class="forecast-time">{{ $forecast['time'] }}</div>
                                        <i
                                            class="fas {{ $forecast['icon'] }} {{ $forecast['icon'] == 'fa-sun' ? 'text-warning' : 'text-primary' }}"></i>
                                        <div class="forecast-temp">{{ $forecast['temp'] }}°C</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="what-to-bring">
                        <h4>What to Bring</h4>
                        <ul class="items-to-bring">
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Comfortable walking shoes</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Sun protection (hat, sunscreen, sunglasses)</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Water bottle (2L minimum)</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Light, breathable clothing</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Camera</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Small backpack</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Visitor Experiences -->
            <div class="content-card">
                <h2 class="section-title">Visitor Experiences</h2>

                @if ($activity->reviews && $activity->reviews->where('status', 'approved')->count() > 0)
                    <div class="reviews-summary mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="overall-rating">
                                    <div class="rating-number">{{ number_format($activity->reviews_avg_rating, 1) }}</div>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= round($activity->reviews_avg_rating) ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="rating-count">
                                        {{ $activity->reviews->where('status', 'approved')->count() }} @choice('review|reviews', $activity->reviews->where('status', 'approved')->count())
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="rating-bars">
                                    @php
                                        $reviewsByRating = [
                                            5 => $activity->reviews
                                                ->where('status', 'approved')
                                                ->where('rating', 5)
                                                ->count(),
                                            4 => $activity->reviews
                                                ->where('status', 'approved')
                                                ->where('rating', 4)
                                                ->count(),
                                            3 => $activity->reviews
                                                ->where('status', 'approved')
                                                ->where('rating', 3)
                                                ->count(),
                                            2 => $activity->reviews
                                                ->where('status', 'approved')
                                                ->where('rating', 2)
                                                ->count(),
                                            1 => $activity->reviews
                                                ->where('status', 'approved')
                                                ->where('rating', 1)
                                                ->count(),
                                        ];
                                        $totalReviews = $activity->reviews->where('status', 'approved')->count() ?: 1;
                                    @endphp

                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <div class="rating-bar-row">
                                            <div class="rating-label">{{ $rating }} <i
                                                    class="fas fa-star text-warning"></i></div>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ ($reviewsByRating[$rating] / $totalReviews) * 100 }}%"
                                                    aria-valuenow="{{ $reviewsByRating[$rating] }}" aria-valuemin="0"
                                                    aria-valuemax="{{ $totalReviews }}"></div>
                                            </div>
                                            <div class="rating-count">{{ $reviewsByRating[$rating] }}</div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-container">
                        @foreach ($activity->reviews->where('status', 'approved')->take(3) as $review)
                            <div class="review-card">
                                <div class="review-avatar">
                                    <img src="{{ $review->user->profile_image ? asset('storage/' . $review->user->profile_image) : '/api/placeholder/60/60' }}"
                                        alt="{{ $review->user->first_name ?? 'Reviewer' }}">
                                </div>
                                <div class="review-content">
                                    <h5 class="reviewer-name">{{ $review->user->first_name ?? 'Anonymous' }}
                                        {{ $review->user->last_name ?? '' }}</h5>
                                    <div class="review-date">Visited
                                        {{ \Carbon\Carbon::parse($review->created_at)->format('F Y') }}</div>
                                    <div class="review-rating mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="review-text">
                                        "{{ $review->comment }}"
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($activity->reviews->where('status', 'approved')->count() > 3)
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" id="loadMoreReviews">
                                <i class="fas fa-comment-dots me-1"></i> See All
                                {{ $activity->reviews->where('status', 'approved')->count() }} Reviews
                            </button>
                        </div>
                    @endif

                    <!-- Check if user has a completed booking for this activity but hasn't written a review -->
                    @auth
                        @php
                            $hasCompletedBooking = false;
                            $eligibleBooking = null;

                            foreach (Auth::user()->bookings->where('status', 'completed') as $booking) {
                                if ($booking->activities->contains($activity->id)) {
                                    $hasCompletedBooking = true;
                                    if (!$booking->hasReview()) {
                                        $eligibleBooking = $booking;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if ($hasCompletedBooking && $eligibleBooking)
                            <div class="write-review-prompt mt-4 p-3 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1"><i class="fas fa-star me-2 text-warning"></i> Share your
                                            experience
                                        </h5>
                                        <p class="mb-0 text-muted">You've completed this activity. Help others by writing
                                            a
                                            review!</p>
                                    </div>
                                    <button type="button" class="btn btn-primary write-review-btn"
                                        data-booking-id="{{ $eligibleBooking->id }}">
                                        <i class="fas fa-pencil-alt me-1"></i> Write a Review
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endauth
                @else
                    <div class="no-reviews-message p-4 mb-4">
                        <div class="text-center">
                            <i class="far fa-comment-alt d-block mb-3"></i>
                            <h4>No Reviews Yet</h4>
                            <p>This activity doesn't have any reviews yet.</p>

                            @auth
                                @php
                                    $hasCompletedBooking = false;
                                    $eligibleBooking = null;

                                    foreach (Auth::user()->bookings->where('status', 'completed') as $booking) {
                                        if ($booking->activities->contains($activity->id)) {
                                            $hasCompletedBooking = true;
                                            if (!$booking->hasReview()) {
                                                $eligibleBooking = $booking;
                                                break;
                                            }
                                        }
                                    }
                                @endphp

                                @if ($hasCompletedBooking && $eligibleBooking)
                                    <div class="mt-3">
                                        <button type="button"
                                            class="btn btn-primary write-review-btn d-flex align-items-center justify-content-center mx-auto"
                                            data-booking-id="{{ $eligibleBooking->id }}">
                                            <i class="fas fa-pencil-alt me-1"
                                                style="position: relative; top: 8px; font-size: small;"></i> Be the first to
                                            write a review
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endif
            </div>

            <!-- Complete Your Journey -->
            <div class="content-card">
                <h2 class="section-title">Complete Your Journey</h2>
                <div class="related-activities">
                    @if ($similarActivities && $similarActivities->count() > 0)
                        @foreach ($similarActivities as $relatedActivity)
                            <div class="activity-card">
                                <div class="activity-img">
                                    @if ($relatedActivity->has_images)
                                        @php
                                            $primaryImage = $relatedActivity->images
                                                ->where('is_primary', true)
                                                ->first();
                                        @endphp
                                        <img src="{{ asset('storage/' . $primaryImage->path) }}"
                                            alt="{{ $relatedActivity->name }}">
                                    @else
                                        <img src="{{ asset('api/placeholder/400/250') }}"
                                            alt="{{ $relatedActivity->name }}">
                                    @endif
                                </div>
                                <div class="activity-info">
                                    <h4 class="activity-name">{{ $relatedActivity->name }}</h4>
                                    <div class="tour-attributes">
                                        <div class="attribute-item">
                                            <i class="far fa-clock"></i>
                                            <span>{{ $relatedActivity->formatted_duration ?? 'Full day' }}</span>
                                        </div>
                                    </div>
                                    <p class="mb-2">{{ Str::limit($relatedActivity->description, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="activity-price">From
                                            ${{ number_format($relatedActivity->price, 0) }}</span>
                                        <a href="{{ route('activity.detail', $relatedActivity->id) }}"
                                            class="book-now-mini">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="activity-card">
                            <div class="activity-img">
                                <img src="/api/placeholder/400/250" alt="Wadi Rum Desert Camp">
                            </div>
                            <div class="activity-info">
                                <h4 class="activity-name">Wadi Rum Desert Camp</h4>
                                <div class="tour-attributes">
                                    <div class="attribute-item">
                                        <i class="far fa-clock"></i>
                                        <span>Overnight</span>
                                    </div>
                                </div>
                                <p class="mb-2">Experience Bedouin hospitality under the stars in Wadi Rum.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="activity-price">From $85 USD</span>
                                    <a href="#" class="btn btn-sm book-now-btn">Book</a>
                                </div>
                            </div>
                        </div>
                        <div class="activity-card">
                            <div class="activity-img">
                                <img src="/api/placeholder/400/250" alt="Dead Sea Wellness Day">
                            </div>
                            <div class="activity-info">
                                <h4 class="activity-name">Dead Sea Wellness Day</h4>
                                <div class="tour-attributes">
                                    <div class="attribute-item">
                                        <i class="far fa-clock"></i>
                                        <span>Full day</span>
                                    </div>
                                </div>
                                <p class="mb-2">Relax and float in mineral-rich, therapeutic mud.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="activity-price">From $95 USD</span>
                                    <a href="#" class="btn btn-sm book-now-btn">Book</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Book {{ $activity->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Booking Steps -->
                    <div class="booking-steps mb-4">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Select Quantity</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Participants</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Payment</div>
                        </div>
                    </div>

                    <!-- Step 1: Quantity Selection -->
                    <div class="booking-step" id="step1">
                        <h4>How many people will be joining?</h4>
                        <div class="quantity-selector mb-4">
                            <button class="quantity-btn" id="decreaseQuantity">-</button>
                            <input type="number" id="ticketQuantity" value="1" min="1" readonly>
                            <button class="quantity-btn" id="increaseQuantity">+</button>
                        </div>

                        <!-- Capacity warning message -->
                        <div id="capacityWarning" class="alert alert-warning" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="capacityWarningText">You've reached the maximum available seats for this
                                activity.</span>
                        </div>

                        <div class="price-summary">
                            <div class="price-row">
                                <span>{{ $activity->name }}</span>
                                <span>${{ number_format($activity->price, 2) }} × <span
                                        id="quantityDisplay">1</span></span>
                            </div>
                            <div class="price-row total">
                                <span>Total</span>
                                <span id="totalPrice">${{ number_format($activity->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-primary" id="goToStep2">Continue</button>
                        </div>
                    </div>

                    <!-- Step 2: Participants Information -->
                    <div class="booking-step" id="step2" style="display: none;">
                        <!-- Participant form will be inserted here dynamically -->
                        <div id="participantFormsContainer">
                            <!-- Each participant form will be added here -->
                        </div>

                        <div class="step-navigation d-flex justify-content-between mt-4">
                            <button class="btn btn-secondary" id="backToStep1">Back</button>
                            <button class="btn btn-primary" id="goToStep3">Continue to Payment</button>
                        </div>
                    </div>

                    <!-- Step 3: Payment -->
                    <div class="booking-step" id="step3" style="display: none;">
                        <div class="payment-container" style="flex-direction: column; margin-bottom: 20px;">
                            <!-- Booking Summary -->
                            <div class="booking-summary mb-4">
                                <div class="tour-info">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="tour-name">{{ $activity->name }}</div>
                                            <div class="tour-date" id="bookingSummaryDate">{{ date('F d, Y') }} • <span
                                                    id="participantCount">1</span> Participants</div>
                                        </div>
                                        <div class="tour-image">
                                            @if ($activity->has_images)
                                                @php
                                                    $primaryImage = $activity->images
                                                        ->where('is_primary', true)
                                                        ->first();
                                                @endphp
                                                <img src="{{ asset('storage/' . $primaryImage->path) }}"
                                                    alt="{{ $activity->name }}" class="rounded-circle shadow-sm">
                                            @else
                                                <img src="{{ asset('api/placeholder/80/80') }}"
                                                    alt="{{ $activity->name }}" class="rounded-circle shadow-sm">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="regular-price mt-3">
                                        <div class="pricing-label">Regular Price</div>
                                        <div class="pricing-value">${{ number_format($activity->price, 2) }} × <span
                                                id="quantityDisplayPayment">1</span></div>
                                    </div>
                                </div>

                                <table class="pricing-table">
                                    <tr>
                                        <td class="pricing-label">Subtotal</td>
                                        <td class="td-right pricing-value" id="paymentSubtotal">
                                            ${{ number_format($activity->price, 2) }}</td>
                                    </tr>
                                    <tr id="discountRow" style="display: none;">
                                        <td class="pricing-label">Multi-ticket Discount (5%)</td>
                                        <td class="td-right pricing-value discount" id="discountAmount">-$0.00</td>
                                    </tr>
                                    <tr id="pointsDiscountRow" style="display: none;">
                                        <td class="pricing-label">Loyalty Points Applied</td>
                                        <td class="td-right pricing-value discount" id="pointsDiscountAmount">-$0.00</td>
                                    </tr>
                                    <tr id="referralDiscountRow" style="display: none;">
                                        <td class="pricing-label">Referral Credit Applied</td>
                                        <td class="td-right pricing-value discount" id="referralDiscountAmount">-$0.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pricing-label">Tourism Tax</td>
                                        <td class="td-right pricing-value" id="taxAmount">
                                            ${{ number_format($activity->price * 0.05, 2) }}</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td>Total</td>
                                        <td class="td-right" id="paymentTotal">
                                            ${{ number_format($activity->price * 1.05, 2) }}</td>
                                    </tr>
                                </table>

                                <div class="back-button mt-3" id="backToStep2">
                                    <i class="fas fa-chevron-left"></i> Back to Participant Details
                                </div>
                            </div>

                            <!-- Payment & Rewards Options -->
                            <div class="payment-section">
                                <!-- Payment Details Card -->
                                <div class="card rewards-card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Payment & Rewards</h5>

                                        @if (Auth::check())
                                            <!-- REWARDS SECTION: Directly inside the payment card -->
                                            <div class="payment-rewards-section">
                                                <div class="section-divider">
                                                    <span>Available Rewards</span>
                                                </div>

                                                <div class="rewards-options">
                                                    <!-- Loyalty Points Option -->
                                                    @if (Auth::user()->available_points > 0)
                                                        <div class="reward-option mb-4 card-hover-effect">
                                                            <div class="reward-header d-flex align-items-center">
                                                                <div class="reward-icon loyalty-icon pulse-animation">
                                                                    <i class="fas fa-star"></i>
                                                                </div>
                                                                <div class="reward-summary">
                                                                    <h6 class="mb-0">Loyalty Points</h6>
                                                                    <p class="points-available mb-0">You have
                                                                        <strong>{{ number_format(Auth::user()->available_points) }}</strong>
                                                                        points (worth
                                                                        ${{ number_format(Auth::user()->available_points * 0.1, 2) }})
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="reward-controls mt-3">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <label for="points-slider"
                                                                        class="form-label fw-bold mb-0">Apply
                                                                        points:</label>
                                                                    <span class="points-badge"
                                                                        id="points-selected-display">0</span>
                                                                </div>
                                                                <input type="range" class="form-range custom-range mt-2"
                                                                    id="points-slider" min="0"
                                                                    max="{{ min(Auth::user()->available_points, round($activity->price * 10)) }}"
                                                                    value="0">

                                                                <div
                                                                    class="slider-values d-flex justify-content-between mt-1">
                                                                    <small>0</small>
                                                                    <small class="text-center fw-bold text-success">
                                                                        Saving: $<span
                                                                            id="points-value-display">0.00</span>
                                                                    </small>
                                                                    <small>{{ min(Auth::user()->available_points, round($activity->price * 10)) }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Referral Credits Option -->
                                                    @if (Auth::check() && Auth::user()->referral_balance > 0)
                                                        <div class="reward-option mb-4 card-hover-effect">
                                                            <div class="reward-header d-flex align-items-center">
                                                                <div class="reward-icon referral-icon pulse-animation">
                                                                    <i class="fas fa-user-friends"></i>
                                                                </div>
                                                                <div class="reward-summary">
                                                                    <h6 class="mb-0">Referral Credit</h6>
                                                                    <p class="credit-available mb-0">You have
                                                                        <strong>$<span id="referral-credit-available"
                                                                                data-value="{{ Auth::user()->referral_balance }}">{{ number_format(Auth::user()->referral_balance, 2) }}</span></strong>
                                                                        in credit
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="reward-controls mt-2">
                                                                <div class="form-check form-switch modern-switch">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="referral-credit-checkbox">
                                                                    <label class="form-check-label fw-bold"
                                                                        for="referral-credit-checkbox">
                                                                        Apply referral credit
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="reward-earn-info mt-3">
                                                    <div class="d-flex">
                                                        <div class="reward-badge me-2">
                                                            <i class="fas fa-gift"></i>
                                                        </div>
                                                        <div>
                                                            <span id="pointsToEarn">You'll earn
                                                                {{ round($activity->price) }} points from this
                                                                purchase</span>
                                                            <small class="d-block text-muted">Worth
                                                                ${{ number_format(round($activity->price) * 0.1, 2) }} on
                                                                your next booking</small>
                                                            <small class="d-block text-warning mt-1"><i
                                                                    class="fas fa-info-circle me-1"></i>Points will be
                                                                added to your account after the activity is completed and
                                                                verified by admin</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="section-divider my-4">
                                                    <span>Payment Method</span>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Payment Method Selection -->
                                        <div class="payment-methods mb-4">
                                            <div class="d-flex mb-3">
                                                <div class="payment-method-option selected">
                                                    <i class="fas fa-credit-card"></i>
                                                    <span>Credit Card</span>
                                                </div>
                                                <div class="payment-method-option disabled">
                                                    <i class="fab fa-paypal"></i>
                                                    <span>PayPal</span>
                                                </div>
                                                <div class="payment-method-option disabled">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    <span>Cash</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Credit Card Form -->
                                        <div class="card-form">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="cardNumber"
                                                    placeholder="1234 5678 9012 3456" value="4242 4242 4242 4242">
                                                <label for="cardNumber">Card Number</label>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="expiryDate"
                                                            placeholder="MM/YY" value="12/25">
                                                        <label for="expiryDate">Expiry Date</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="cvv"
                                                            placeholder="123" value="123">
                                                        <label for="cvv">Security Code</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="cardholderName"
                                                    placeholder="Name as it appears on card" value="Test User">
                                                <label for="cardholderName">Cardholder Name</label>
                                            </div>
                                        </div>

                                        <div class="terms-checkbox mt-4">
                                            <input type="checkbox" class="form-check-input" id="terms-agree" required>
                                            <label for="terms-agree" class="terms-text">
                                                I agree to the <a href="#" target="_blank">Terms and Conditions</a>
                                                and
                                                <a href="#" target="_blank">Privacy Policy</a>
                                            </label>
                                            <div class="invalid-feedback">
                                                You must agree to the terms before proceeding.
                                            </div>
                                        </div>

                                        <!-- Complete Booking Button (Inside the modal) -->
                                        <div class="text-center mt-4">
                                            <button type="button" class="payment-button" id="completeBooking">
                                                <i class="fas fa-lock me-2"></i>
                                                Complete Booking for <span
                                                    id="paymentButtonAmount">${{ number_format($activity->price * 1.05, 2) }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="py-4">
                        <i class="fas fa-user-lock fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Please login to continue</h4>
                        <p class="text-muted mb-4">You need to be logged in to book this experience. Please login to your
                            account or create a new one.</p>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i> Sign Up
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade review-modal" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="review-avatar-large">
                        <img id="reviewModalAvatar" src="/api/placeholder/80/80" alt="Reviewer">
                    </div>
                    <h4 id="reviewModalName" class="mb-1">Reviewer Name</h4>
                    <div id="reviewModalDate" class="review-date">Visited April 2023</div>
                    <div id="reviewModalRating" class="rating-large">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <div id="reviewModalComment" class="review-text">
                        "The review text will appear here."
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- All Reviews Modal -->
    <div class="modal fade" id="allReviewsModal" tabindex="-1" aria-labelledby="allReviewsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allReviewsModalLabel">All Reviews for {{ $activity->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="allReviewsContainer">
                        <!-- Reviews will be loaded here -->
                        @if ($activity->reviews && $activity->reviews->where('status', 'approved')->count() > 0)
                            @foreach ($activity->reviews->where('status', 'approved') as $review)
                                <div class="review-card mb-4" data-review-id="{{ $review->id }}">
                                    <div class="review-avatar">
                                        <img src="{{ $review->user->profile_image ? asset('storage/' . $review->user->profile_image) : '/api/placeholder/60/60' }}"
                                            alt="{{ $review->user->first_name ?? 'Reviewer' }}">
                                    </div>
                                    <div class="review-content">
                                        <h5 class="reviewer-name">{{ $review->user->first_name ?? 'Anonymous' }}
                                            {{ $review->user->last_name ?? '' }}</h5>
                                        <div class="review-date">Visited
                                            {{ \Carbon\Carbon::parse($review->created_at)->format('F Y') }}</div>
                                        <div class="review-rating mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="review-text">
                                            "{{ $review->comment }}"
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-reviews-message">
                                <i class="far fa-comment-alt"></i>
                                <h4>No Reviews Yet</h4>
                                <p>This activity doesn't have any reviews yet. Be the first to share your experience!</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Write Review Modal -->
    <div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="writeReviewModalLabel">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="reviewAlerts"></div>

                    <div class="activity-summary mb-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($primaryImage ? 'storage/' . $primaryImage->path : 'api/placeholder/100/100') }}"
                                alt="{{ $activity->name }}" class="me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h4 class="mb-1">{{ $activity->name }}</h4>
                                <p class="text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $activity->location ?? 'Location not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="review-guidelines bg-light p-3 rounded mb-4">
                        <h6 class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Tips for a Great Review</h6>
                        <ul class="mb-0 ps-3">
                            <li>Focus on your personal experience</li>
                            <li>Be specific about what you liked and any suggestions for improvement</li>
                            <li>Keep it honest and helpful for other travelers</li>
                        </ul>
                    </div>

                    <form id="reviewForm">
                        <input type="hidden" name="activity_id" id="review_activity_id" value="{{ $activity->id }}">
                        <input type="hidden" name="booking_id" id="review_booking_id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="mb-4">
                            <p class="fw-bold mb-2">How would you rate your experience?</p>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                            </div>
                            <div class="invalid-feedback rating-error">Please select a rating</div>
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Your Review</label>
                            <textarea id="comment" name="comment" rows="5" class="form-control"
                                placeholder="Share your experience, what you enjoyed, and any suggestions for improvement..." required></textarea>
                            <div class="form-text">Minimum 10 characters, maximum 1000 characters.</div>
                            <div class="invalid-feedback comment-error">Please enter your review (10-1000 characters)</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submitReviewBtn">
                                <i class="fas fa-paper-plane me-1"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Review Modal -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">Edit Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editReviewAlerts"></div>

                    <div class="activity-summary mb-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($primaryImage ? 'storage/' . $primaryImage->path : 'api/placeholder/100/100') }}"
                                alt="{{ $activity->name }}" class="me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h4 class="mb-1">{{ $activity->name }}</h4>
                                <p class="text-muted mb-0"><i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $activity->location ?? 'Location not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="edit-review-status mb-3"></div>

                    <div class="review-guidelines bg-light p-3 rounded mb-4">
                        <h6 class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Tips for a Great Review</h6>
                        <ul class="mb-0 ps-3">
                            <li>Focus on your personal experience</li>
                            <li>Be specific about what you liked and any suggestions for improvement</li>
                            <li>Keep it honest and helpful for other travelers</li>
                        </ul>
                    </div>

                    <form id="editReviewForm">
                        <input type="hidden" name="review_id" id="edit_review_id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="mb-4">
                            <p class="fw-bold mb-2">How would you rate your experience?</p>
                            <div class="star-rating" id="editStarRating">
                                <input type="radio" id="edit_star5" name="rating" value="5" required>
                                <label for="edit_star5" title="5 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="edit_star4" name="rating" value="4">
                                <label for="edit_star4" title="4 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="edit_star3" name="rating" value="3">
                                <label for="edit_star3" title="3 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="edit_star2" name="rating" value="2">
                                <label for="edit_star2" title="2 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="edit_star1" name="rating" value="1">
                                <label for="edit_star1" title="1 star"><i class="fas fa-star"></i></label>
                            </div>
                            <div class="invalid-feedback edit-rating-error">Please select a rating</div>
                        </div>

                        <div class="mb-4">
                            <label for="edit_comment" class="form-label fw-bold">Your Review</label>
                            <textarea id="edit_comment" name="comment" rows="5" class="form-control"
                                placeholder="Share your experience, what you enjoyed, and any suggestions for improvement..." required></textarea>
                            <div class="form-text">Minimum 10 characters, maximum 1000 characters.</div>
                            <div class="invalid-feedback edit-comment-error">Please enter your review (10-1000 characters)
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger" id="deleteReviewBtn">
                                <i class="fas fa-trash-alt me-1"></i> Delete Review
                            </button>

                            <div>
                                <button type="button" class="btn btn-secondary me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="updateReviewBtn">
                                    <i class="fas fa-save me-1"></i> Update Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Delete Confirmation Modal -->
    <div class="modal fade" id="deleteReviewConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your review? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteReview">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize variables and capacity limits
            const activityPrice = {{ $activity->price }};
            const maxCapacity = {{ $activity->remaining_capacity }}; // Available seats for this activity
            let currentParticipant = 1;
            let totalParticipants = 1;
            let pointsUsed = 0;
            let pointsValue = 0;

            // Elements
            const bookingModal = document.getElementById('bookingModal');
            const openBookingModalBtn = document.getElementById('openBookingModal');
            const openLoginModalBtn = document.getElementById('openLoginModal');
            const decreaseQuantityBtn = document.getElementById('decreaseQuantity');
            const increaseQuantityBtn = document.getElementById('increaseQuantity');
            const ticketQuantityInput = document.getElementById('ticketQuantity');
            const quantityDisplay = document.getElementById('quantityDisplay');
            const totalPriceElement = document.getElementById('totalPrice');
            const participantFormsContainer = document.getElementById('participantFormsContainer');
            const capacityWarning = document.getElementById('capacityWarning');

            // Step navigation buttons
            const goToStep2Btn = document.getElementById('goToStep2');
            const backToStep1Btn = document.getElementById('backToStep1');
            const goToStep3Btn = document.getElementById('goToStep3');
            const backToStep2Btn = document.getElementById('backToStep2');
            const completeBookingBtn = document.getElementById('completeBooking');

            // Step elements
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            const bookingSteps = document.querySelectorAll('.booking-steps .step');

            // Initialize modals
            const modal = new bootstrap.Modal(bookingModal);
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

            // Open modal when book button is clicked
            openBookingModalBtn?.addEventListener('click', function() {
                modal.show();

                // Set the maximum number of tickets to the available capacity
                ticketQuantityInput.setAttribute('max', maxCapacity);

                // If only 1 seat is available, disable the increase button and show warning
                if (maxCapacity <= 1) {
                    increaseQuantityBtn.classList.add('disabled');
                    increaseQuantityBtn.disabled = true;
                    capacityWarning.style.display = 'block';
                    document.getElementById('capacityWarningText').innerHTML =
                        "<strong>Note:</strong> Only 1 seat remains available for this activity. You cannot add more participants.";
                } else if (maxCapacity < 5) {
                    // Show warning for limited availability but not fully booked
                    capacityWarning.style.display = 'block';
                    document.getElementById('capacityWarningText').innerHTML =
                        `<strong>Note:</strong> Only ${maxCapacity} seats remain available for this activity.`;
                }
            });

            // Open login modal when unregistered user tries to book
            openLoginModalBtn?.addEventListener('click', function() {
                loginModal.show();
            });

            // Quantity selector
            decreaseQuantityBtn.addEventListener('click', function() {
                if (parseInt(ticketQuantityInput.value) > 1) {
                    ticketQuantityInput.value = parseInt(ticketQuantityInput.value) - 1;
                    updatePriceSummary();

                    // Re-enable increase button if below max capacity
                    if (parseInt(ticketQuantityInput.value) < maxCapacity) {
                        increaseQuantityBtn.classList.remove('disabled');
                        increaseQuantityBtn.disabled = false;
                    }

                    // Hide capacity warning if below max capacity
                    capacityWarning.style.display = 'none';
                }
            });

            increaseQuantityBtn.addEventListener('click', function() {
                const currentValue = parseInt(ticketQuantityInput.value);
                // Only allow increasing quantity if below maximum capacity (no more 10-participant limit)
                if (currentValue < maxCapacity) {
                    ticketQuantityInput.value = currentValue + 1;
                    updatePriceSummary();

                    // Disable increase button if max capacity reached
                    if (parseInt(ticketQuantityInput.value) >= maxCapacity) {
                        increaseQuantityBtn.classList.add('disabled');
                        increaseQuantityBtn.disabled = true;

                        // Show capacity warning with specific message based on available seats
                        capacityWarning.style.display = 'block';
                        if (maxCapacity === 1) {
                            document.getElementById('capacityWarningText').innerHTML =
                                "<strong>Note:</strong> Only 1 seat remains available for this activity. You cannot add more participants.";
                        } else {
                            document.getElementById('capacityWarningText').innerHTML =
                                `<strong>Note:</strong> You've reached the maximum of ${maxCapacity} available seats for this activity.`;
                        }
                    }
                } else if (currentValue >= maxCapacity) {
                    // Show specific message when trying to exceed capacity
                    capacityWarning.style.display = 'block';
                    document.getElementById('capacityWarningText').innerHTML =
                        `<strong>Sorry!</strong> Only ${maxCapacity} ${maxCapacity === 1 ? 'seat is' : 'seats are'} available for this activity.`;
                }
            });

            // Update price summary
            function updatePriceSummary() {
                const quantity = parseInt(ticketQuantityInput.value);
                const total = quantity * activityPrice;

                quantityDisplay.textContent = quantity;
                totalPriceElement.textContent = '$' + total.toFixed(2);
                totalParticipants = quantity;

                // Also update payment summary
                updatePaymentSummary();
            }

            // Update price summary for payment page
            function updatePaymentSummary() {
                const quantity = parseInt(ticketQuantityInput.value);
                const subtotal = quantity * activityPrice;
                let discount = 0;
                let discountPercent = 0;

                // Apply discount for multiple tickets (if more than 3)
                if (quantity >= 3) {
                    discountPercent = 5;
                    discount = subtotal * (discountPercent / 100);
                    document.getElementById('discountRow').style.display = 'table-row';
                    document.getElementById('discountAmount').textContent = '-$' + discount.toFixed(2);
                } else {
                    document.getElementById('discountRow').style.display = 'none';
                }

                // Calculate subtotal after discount but before points
                const subtotalAfterDiscount = subtotal - discount;

                // Apply loyalty points discount (if any)
                const loyaltyDiscount = pointsValue;

                const taxRate = 0.05;
                const taxableAmount = subtotalAfterDiscount - loyaltyDiscount;
                const tax = taxableAmount * taxRate;
                const total = taxableAmount + tax;

                // Update payment page elements
                document.getElementById('quantityDisplayPayment').textContent = quantity;
                document.getElementById('participantCount').textContent = quantity;
                document.getElementById('paymentSubtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('taxAmount').textContent = '$' + tax.toFixed(2);
                document.getElementById('paymentTotal').textContent = '$' + total.toFixed(2);
                document.getElementById('paymentButtonAmount').textContent = '$' + total.toFixed(2);

                // Update loyalty points to earn (1 point per dollar)
                if (document.getElementById('pointsToEarn')) {
                    const pointsToEarn = Math.round(total);
                    document.getElementById('pointsToEarn').textContent =
                        `You'll earn ${pointsToEarn} points from this purchase`;
                }
            }

            // Navigate to Step 2
            goToStep2Btn.addEventListener('click', function() {
                // Generate participant forms based on quantity
                generateParticipantForms();

                // Show Step 2
                step1.style.display = 'none';
                step2.style.display = 'block';

                // Update steps indicator
                bookingSteps[0].classList.remove('active');
                bookingSteps[1].classList.add('active');
            });

            // Navigate back to Step 1
            backToStep1Btn.addEventListener('click', function() {
                step2.style.display = 'none';
                step1.style.display = 'block';

                bookingSteps[1].classList.remove('active');
                bookingSteps[0].classList.add('active');
            });

            // Navigate to Step 3
            goToStep3Btn.addEventListener('click', function() {
                // Validate participant forms
                if (validateParticipantForms()) {
                    step2.style.display = 'none';
                    step3.style.display = 'block';

                    bookingSteps[1].classList.remove('active');
                    bookingSteps[2].classList.add('active');
                }
            });

            // Navigate back to Step 2
            backToStep2Btn.addEventListener('click', function() {
                step3.style.display = 'none';
                step2.style.display = 'block';

                bookingSteps[2].classList.remove('active');
                bookingSteps[1].classList.add('active');
            });

            // Allow clicking on steps to navigate backward
            document.querySelectorAll('.booking-steps .step').forEach(step => {
                step.addEventListener('click', function() {
                    const clickedStep = parseInt(this.getAttribute('data-step'));
                    let currentActiveStep = 0;

                    // Find which step is currently active
                    bookingSteps.forEach((step, index) => {
                        if (step.classList.contains('active')) {
                            currentActiveStep = index + 1;
                        }
                    });

                    // Only allow going backward, not forward
                    if (clickedStep < currentActiveStep) {
                        // Navigate to the clicked step
                        if (clickedStep === 1) {
                            // Go to step 1
                            step2.style.display = 'none';
                            step3.style.display = 'none';
                            step1.style.display = 'block';

                            // Update step indicators
                            bookingSteps[0].classList.add('active');
                            bookingSteps[1].classList.remove('active');
                            bookingSteps[2].classList.remove('active');
                        } else if (clickedStep === 2 && currentActiveStep === 3) {
                            // Go to step 2 from step 3
                            step1.style.display = 'none';
                            step3.style.display = 'none';
                            step2.style.display = 'block';

                            // Update step indicators
                            bookingSteps[0].classList.remove('active');
                            bookingSteps[1].classList.add('active');
                            bookingSteps[2].classList.remove('active');
                        }
                    }
                });
            });

            // Generate participant forms
            function generateParticipantForms() {
                participantFormsContainer.innerHTML = '';

                for (let i = 1; i <= totalParticipants; i++) {
                    const participantForm = `
                    <div class="participant-form" data-participant="${i}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Participant ${i} of ${totalParticipants}</h5>
                            <div class="participant-navigation">
                                <button type="button" class="btn btn-sm ${i === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'} prev-participant" ${i === 1 ? 'disabled' : ''}>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="mx-2">${i} / ${totalParticipants}</span>
                                <button type="button" class="btn btn-sm ${i === totalParticipants ? 'btn-secondary disabled' : 'btn-outline-secondary'} next-participant" ${i === totalParticipants ? 'disabled' : ''}>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                    <i class="fas fa-info-circle" title="Enter full name as it appears on ID"></i>
                                </label>
                                <input type="text" class="form-control participant-name" required>
                                <div class="invalid-feedback">
                                    Please enter the participant's full name
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                    <i class="fas fa-info-circle" title="Include country code (e.g., +1 for US)"></i>
                                </label>
                                <input type="tel" class="form-control participant-phone" required>
                                <div class="invalid-feedback">
                                    Please enter a phone number
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Email Address (optional)
                                </label>
                                <input type="email" class="form-control participant-email">
                            </div>
                        </div>
                    </div>
                `;

                    participantFormsContainer.insertAdjacentHTML('beforeend', participantForm);
                }

                // Add event listeners for navigation between participants
                document.querySelectorAll('.next-participant').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const currentForm = this.closest('.participant-form');
                        const currentIndex = parseInt(currentForm.dataset.participant);

                        if (currentIndex < totalParticipants) {
                            showParticipantForm(currentIndex + 1);
                        }
                    });
                });

                document.querySelectorAll('.prev-participant').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const currentForm = this.closest('.participant-form');
                        const currentIndex = parseInt(currentForm.dataset.participant);

                        if (currentIndex > 1) {
                            showParticipantForm(currentIndex - 1);
                        }
                    });
                });

                // Show only the first participant form initially
                showParticipantForm(1);
            }

            // Show a specific participant form
            function showParticipantForm(index) {
                document.querySelectorAll('.participant-form').forEach(form => {
                    form.style.display = 'none';
                });

                const formToShow = document.querySelector(`.participant-form[data-participant="${index}"]`);
                if (formToShow) {
                    formToShow.style.display = 'block';
                }
            }

            // Validate participant forms
            function validateParticipantForms() {
                let isValid = true;
                let errorMessages = [];
                let firstErrorParticipant = null;

                document.querySelectorAll('.participant-form').forEach(form => {
                    const participantNumber = parseInt(form.getAttribute('data-participant'));
                    const nameInput = form.querySelector('.participant-name');
                    const phoneInput = form.querySelector('.participant-phone');
                    let hasError = false;

                    if (!nameInput.value.trim()) {
                        nameInput.classList.add('is-invalid');
                        isValid = false;
                        hasError = true;
                        errorMessages.push(`Participant ${participantNumber}: Missing full name`);
                        if (firstErrorParticipant === null) firstErrorParticipant = participantNumber;
                    } else {
                        nameInput.classList.remove('is-invalid');
                    }

                    if (!phoneInput.value.trim()) {
                        phoneInput.classList.add('is-invalid');
                        if (!phoneInput.nextElementSibling || !phoneInput.nextElementSibling.classList
                            .contains('invalid-feedback')) {
                            phoneInput.insertAdjacentHTML('afterend',
                                '<div class="invalid-feedback">Please enter a phone number</div>');
                        }
                        isValid = false;
                        hasError = true;
                        errorMessages.push(`Participant ${participantNumber}: Missing phone number`);
                        if (firstErrorParticipant === null) firstErrorParticipant = participantNumber;
                    } else {
                        phoneInput.classList.remove('is-invalid');
                    }
                });

                // If there are validation errors, show them in an alert at the top of the form
                if (!isValid) {
                    // Remove any existing error summary
                    const existingErrorSummary = document.getElementById('participant-error-summary');
                    if (existingErrorSummary) {
                        existingErrorSummary.remove();
                    }

                    // Create error summary element
                    const errorSummary = document.createElement('div');
                    errorSummary.id = 'participant-error-summary';
                    errorSummary.className = 'alert alert-danger mb-3';
                    errorSummary.innerHTML = `
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Please correct the following errors:</h5>
                        <ul class="mb-0 ps-3">
                            ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                        </ul>
                    `;

                    // Add a click handler to navigate to the form with errors
                    errorSummary.addEventListener('click', function() {
                        if (firstErrorParticipant !== null) {
                            showParticipantForm(firstErrorParticipant);
                        }
                    });

                    // Insert at the top of the participant form container
                    participantFormsContainer.insertBefore(errorSummary, participantFormsContainer.firstChild);

                    // Navigate to the first participant with errors
                    if (firstErrorParticipant !== null) {
                        showParticipantForm(firstErrorParticipant);
                    }
                }

                return isValid;
            }

            // Complete booking with direct redirect and soft modal
            completeBookingBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Validate terms agreement first
                const termsCheckbox = document.getElementById('terms-agree');
                if (!termsCheckbox.checked) {
                    termsCheckbox.nextElementSibling.classList.add('is-invalid');
                    document.querySelector('.terms-checkbox .invalid-feedback').style.display = 'block';
                    return;
                }

                // Store button text and state before changing
                completeBookingBtn.disabled = true;
                const originalText = completeBookingBtn.innerHTML;
                completeBookingBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                // Reset button automatically after 3 seconds as a failsafe
                const resetTimer = setTimeout(function() {
                    completeBookingBtn.disabled = false;
                    completeBookingBtn.innerHTML = originalText;
                }, 3000);

                // Gather booking data
                const quantity = parseInt(ticketQuantityInput.value);
                const activityId = {{ $activity->id }};
                const unitPrice = {{ $activity->price }};
                const total = quantity * unitPrice;

                // Get participant data
                const participants = [];
                document.querySelectorAll('.participant-form').forEach(form => {
                    participants.push({
                        name: form.querySelector('.participant-name').value,
                        phone: form.querySelector('.participant-phone').value,
                        email: form.querySelector('.participant-email').value
                    });
                });

                // Get payment info
                const paymentInfo = {
                    payment_method: 'credit_card',
                    card_number: document.getElementById('cardNumber').value,
                    cardholder_name: document.getElementById('cardholderName').value
                };

                // Create booking object
                const bookingData = {
                    activity_id: activityId,
                    quantity: quantity,
                    unit_price: unitPrice,
                    total: total,
                    activity_date: new Date().toISOString().split('T')[0], // Today's date
                    activity_time: '10:00:00', // Default time
                    participants: participants,
                    payment_info: paymentInfo,
                    loyalty_points_used: pointsUsed, // Add loyalty points used to the booking data
                    use_referral_credit: document.getElementById('referral-credit-checkbox') &&
                        document.getElementById('referral-credit-checkbox').checked
                };

                // Send booking data to server
                fetch('{{ route('api.activity-booking.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(bookingData),
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            // If server returns non-2xx status code, throw an error
                            return response.json().then(errorData => {
                                throw new Error(errorData.error || 'Unknown server error');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        clearTimeout(resetTimer);

                        if (data.success) {
                            // Hide modal
                            try {
                                modal.hide();
                            } catch (e) {
                                console.log("Modal hide error", e);
                            }

                            // Create a soft modal instead of alert
                            showBookingConfirmation(data.data?.loyalty_points_earned || Math.round(
                                total));

                            // Set timer to redirect after showing modal
                            setTimeout(function() {
                                // Redirect to profile page
                                window.location.href =
                                    "{{ route('profile.index') }}#trips-section";
                            }, 2500);
                        } else {
                            // Show detailed error
                            console.error('Booking failed:', data);

                            // Show more detailed error message to user
                            let errorMessage = data.error || 'Unknown error';
                            if (data.details) {
                                errorMessage += ' - ' + data.details;
                            }

                            Swal.fire({
                                title: 'Booking Failed',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonColor: '#92400b'
                            });

                            completeBookingBtn.disabled = false;
                            completeBookingBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        Swal.fire({
                            title: 'Booking Error',
                            text: error.message ||
                                'An error occurred while processing your booking. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#92400b'
                        });

                        completeBookingBtn.disabled = false;
                        completeBookingBtn.innerHTML = originalText;
                    });
            });

            // Function to show booking confirmation modal
            function showBookingConfirmation(pointsEarned) {
                // Create a soft modal instead of alert
                const softModal = document.createElement('div');
                softModal.style.position = 'fixed';
                softModal.style.top = '0';
                softModal.style.left = '0';
                softModal.style.right = '0';
                softModal.style.bottom = '0';
                softModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                softModal.style.display = 'flex';
                softModal.style.alignItems = 'center';
                softModal.style.justifyContent = 'center';
                softModal.style.zIndex = '9999';

                // Create modal content
                const modalContent = document.createElement('div');
                modalContent.style.backgroundColor = 'white';
                modalContent.style.borderRadius = '12px';
                modalContent.style.padding = '30px';
                modalContent.style.textAlign = 'center';
                modalContent.style.maxWidth = '400px';
                modalContent.style.width = '90%';
                modalContent.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.2)';
                modalContent.style.animation = 'fadeIn 0.3s ease-out';

                // Add keyframes for animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(-20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                `;
                document.head.appendChild(style);

                // Add checkmark icon
                const checkIcon = document.createElement('div');
                checkIcon.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 12l2 2 6-6"></path></svg>';
                checkIcon.style.marginBottom = '20px';

                // Add title
                const title = document.createElement('h3');
                title.textContent = 'Booking Confirmed!';
                title.style.color = '#212529';
                title.style.fontSize = '1.5rem';
                title.style.fontWeight = '600';
                title.style.marginBottom = '10px';

                // Add message
                const message = document.createElement('p');
                message.textContent = 'Your booking has been completed successfully!';
                message.style.color = '#6c757d';
                message.style.marginBottom = '15px';

                // Add loyalty points message with future tense
                if (pointsEarned > 0) {
                    const pointsMessage = document.createElement('p');
                    pointsMessage.innerHTML = `<strong>You will earn ${pointsEarned} loyalty points!</strong>`;
                    pointsMessage.style.color = '#92400b';
                    pointsMessage.style.fontSize = '1.1rem';
                    pointsMessage.style.marginBottom = '5px';

                    const pointsInfo = document.createElement('p');
                    pointsInfo.innerHTML =
                        'Points will be added to your account after the activity is completed and verified by admin';
                    pointsInfo.style.color = '#6c757d';
                    pointsInfo.style.fontSize = '0.9rem';
                    pointsInfo.style.marginBottom = '25px';

                    modalContent.appendChild(pointsMessage);
                    modalContent.appendChild(pointsInfo);
                }

                // Add button
                const viewButton = document.createElement('button');
                viewButton.textContent = 'View My Bookings';
                viewButton.style.backgroundColor = '#92400b';
                viewButton.style.color = 'white';
                viewButton.style.border = 'none';
                viewButton.style.borderRadius = '8px';
                viewButton.style.padding = '12px 24px';
                viewButton.style.fontSize = '1rem';
                viewButton.style.fontWeight = '500';
                viewButton.style.cursor = 'pointer';
                viewButton.style.transition = 'all 0.2s ease';

                viewButton.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#793509';
                });

                viewButton.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '#92400b';
                });

                // Add all elements to modal content
                modalContent.appendChild(checkIcon);
                modalContent.appendChild(title);
                modalContent.appendChild(message);
                modalContent.appendChild(viewButton);
                softModal.appendChild(modalContent);

                // Add modal to body
                document.body.appendChild(softModal);

                // Also redirect when button is clicked
                viewButton.addEventListener('click', function() {
                    window.location.href = "{{ route('profile.index') }}#trips-section";
                });

                // In case redirection fails, add a fallback link
                const fallbackDiv = document.createElement('div');
                fallbackDiv.style.position = 'fixed';
                fallbackDiv.style.top = '20px';
                fallbackDiv.style.left = '0';
                fallbackDiv.style.right = '0';
                fallbackDiv.style.textAlign = 'center';
                fallbackDiv.style.zIndex = '9999';
                fallbackDiv.style.pointerEvents = 'none';
                fallbackDiv.innerHTML =
                    '<div style="display:inline-block; background:#28a745; color:white; padding:15px; border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.1); pointer-events:all;">' +
                    'If you\'re not redirected automatically, <a href="{{ route('profile.index') }}#trips-section" style="color:white; text-decoration:underline; font-weight:bold;">click here</a> to view your bookings</div>';
                fallbackDiv.style.opacity = '0';
                fallbackDiv.style.transition = 'opacity 0.5s ease';

                // Show fallback after 4 seconds if redirect doesn't happen
                setTimeout(function() {
                    fallbackDiv.style.opacity = '1';
                }, 4000);

                document.body.appendChild(fallbackDiv);
            }

            // Loyalty points slider functionality
            if (document.getElementById('points-slider')) {
                const pointsSlider = document.getElementById('points-slider');
                const pointsSelectedDisplay = document.getElementById('points-selected-display');
                const pointsValueDisplay = document.getElementById('points-value-display');

                // Initialize points variables
                pointsUsed = 0;
                pointsValue = 0;

                pointsSlider.addEventListener('input', function() {
                    pointsUsed = parseInt(this.value);
                    pointsValue = (pointsUsed * 0.1).toFixed(
                        2); // Convert points to dollars (1 point = $0.1)

                    // Update displays
                    pointsSelectedDisplay.textContent = pointsUsed;
                    pointsValueDisplay.textContent = pointsValue;

                    // Update payment total to reflect points discount
                    updatePaymentWithDiscounts();

                    // Update the booking data with loyalty points
                    document.querySelector('input[name="loyalty_points_used"]') ||
                        document.body.insertAdjacentHTML('beforeend',
                            `<input type="hidden" name="loyalty_points_used" value="${pointsUsed}">`);
                    document.querySelector('input[name="loyalty_points_used"]').value = pointsUsed;
                });
            }

            // Referral credit handling
            if (document.getElementById('referral-credit-checkbox')) {
                const referralCreditCheckbox = document.getElementById('referral-credit-checkbox');
                const referralCreditAvailable = parseFloat(document.getElementById('referral-credit-available')
                    .getAttribute('data-value'));

                referralCreditCheckbox.addEventListener('change', function() {
                    updatePaymentWithDiscounts();
                });
            }

            // Update payment with all applicable discounts (loyalty points and referral credits)
            function updatePaymentWithDiscounts() {
                const quantity = parseInt(ticketQuantityInput.value);
                const subtotal = quantity * activityPrice;
                let discount = 0;
                let discountPercent = 0;

                // Apply multi-ticket discount if applicable (if more than 3 tickets)
                if (quantity >= 3) {
                    discountPercent = 5;
                    discount = subtotal * (discountPercent / 100);
                    document.getElementById('discountRow').style.display = 'table-row';
                    document.getElementById('discountAmount').textContent = '-$' + discount.toFixed(2);
                } else {
                    document.getElementById('discountRow').style.display = 'none';
                }

                // Calculate subtotal after discount
                const subtotalAfterDiscount = subtotal - discount;

                // Apply loyalty points discount
                let pointsDiscountValue = 0;
                if (pointsUsed > 0) {
                    pointsDiscountValue = parseFloat(pointsValue);

                    // Create or update points discount row
                    let pointsDiscountRow = document.getElementById('pointsDiscountRow');
                    if (!pointsDiscountRow) {
                        // Create points discount row if it doesn't exist
                        const taxRow = document.getElementById('taxAmount').parentElement;
                        const pointsRow = document.createElement('tr');
                        pointsRow.id = 'pointsDiscountRow';
                        pointsRow.innerHTML = `
                            <td class="pricing-label">Loyalty Points Applied (${pointsUsed} points)</td>
                            <td class="td-right pricing-value discount" id="pointsDiscountAmount">-$${pointsDiscountValue.toFixed(2)}</td>
                        `;
                        taxRow.parentNode.insertBefore(pointsRow, taxRow);
                    } else {
                        // Update existing row
                        pointsDiscountRow.style.display = 'table-row';
                        document.getElementById('pointsDiscountAmount').textContent = '-$' + pointsDiscountValue
                            .toFixed(2);
                        pointsDiscountRow.firstElementChild.textContent =
                            `Loyalty Points Applied (${pointsUsed} points)`;
                    }
                } else {
                    const pointsDiscountRow = document.getElementById('pointsDiscountRow');
                    if (pointsDiscountRow) {
                        pointsDiscountRow.style.display = 'none';
                    }
                }

                // Apply referral credit discount
                let referralCreditValue = 0;
                if (document.getElementById('referral-credit-checkbox') &&
                    document.getElementById('referral-credit-checkbox').checked) {

                    const availableCredit = parseFloat(document.getElementById('referral-credit-available')
                        .getAttribute('data-value'));
                    referralCreditValue = Math.min(availableCredit, subtotalAfterDiscount - pointsDiscountValue);

                    // Create or update referral credit discount row
                    let referralDiscountRow = document.getElementById('referralDiscountRow');
                    if (!referralDiscountRow) {
                        // Create referral discount row if it doesn't exist
                        const taxRow = document.getElementById('taxAmount').parentElement;
                        const referralRow = document.createElement('tr');
                        referralRow.id = 'referralDiscountRow';
                        referralRow.innerHTML = `
                            <td class="pricing-label">Referral Credit Applied</td>
                            <td class="td-right pricing-value discount" id="referralDiscountAmount">-$${referralCreditValue.toFixed(2)}</td>
                        `;
                        taxRow.parentNode.insertBefore(referralRow, taxRow);
                    } else {
                        // Update existing row
                        referralDiscountRow.style.display = 'table-row';
                        document.getElementById('referralDiscountAmount').textContent = '-$' + referralCreditValue
                            .toFixed(2);
                    }
                } else {
                    const referralDiscountRow = document.getElementById('referralDiscountRow');
                    if (referralDiscountRow) {
                        referralDiscountRow.style.display = 'none';
                    }
                }

                // Calculate price after all discounts
                const priceAfterDiscounts = subtotalAfterDiscount - pointsDiscountValue - referralCreditValue;

                // Calculate tax and total
                const taxRate = 0.05;
                const tax = priceAfterDiscounts * taxRate;
                const total = priceAfterDiscounts + tax;

                // Update payment page elements
                document.getElementById('taxAmount').textContent = '$' + tax.toFixed(2);
                document.getElementById('paymentTotal').textContent = '$' + total.toFixed(2);
                document.getElementById('paymentButtonAmount').textContent = '$' + total.toFixed(2);

                // FIXED: Show points earned based on the amount BEFORE tax
                // This ensures consistency with what's stored in the database
                if (document.getElementById('pointsToEarn')) {
                    // Calculate points based on the price before tax
                    const pointsToEarn = Math.round(priceAfterDiscounts);
                    document.getElementById('pointsToEarn').textContent =
                        `You'll earn ${pointsToEarn} points from this purchase (worth $${(pointsToEarn * 0.1).toFixed(2)})`;
                }
            }

            // Add event listeners for review modals
            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            const allReviewsModal = new bootstrap.Modal(document.getElementById('allReviewsModal'));

            // Make individual review cards clickable to show detailed view
            document.querySelectorAll('.review-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Get review details from the card
                    const avatar = this.querySelector('.review-avatar img').src;
                    const name = this.querySelector('.reviewer-name').textContent;
                    const date = this.querySelector('.review-date').textContent;
                    const ratingHtml = this.querySelector('.review-rating').innerHTML;
                    const comment = this.querySelector('.review-text').textContent;

                    // Populate the modal with review details
                    document.getElementById('reviewModalAvatar').src = avatar;
                    document.getElementById('reviewModalName').textContent = name;
                    document.getElementById('reviewModalDate').textContent = date;
                    document.getElementById('reviewModalRating').innerHTML = ratingHtml;
                    document.getElementById('reviewModalComment').textContent = comment;

                    // Show the modal
                    reviewModal.show();
                });
            });

            // Handle "Load More Reviews" button click
            if (document.getElementById('loadMoreReviews')) {
                document.getElementById('loadMoreReviews').addEventListener('click', function() {
                    allReviewsModal.show();
                });
            }

            // Make reviews in the all reviews modal clickable too
            document.querySelectorAll('#allReviewsContainer .review-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Get review details from the card
                    const avatar = this.querySelector('.review-avatar img').src;
                    const name = this.querySelector('.reviewer-name').textContent;
                    const date = this.querySelector('.review-date').textContent;
                    const ratingHtml = this.querySelector('.review-rating').innerHTML;
                    const comment = this.querySelector('.review-text').textContent;

                    // Populate the review modal
                    document.getElementById('reviewModalAvatar').src = avatar;
                    document.getElementById('reviewModalName').textContent = name;
                    document.getElementById('reviewModalDate').textContent = date;
                    document.getElementById('reviewModalRating').innerHTML = ratingHtml;
                    document.getElementById('reviewModalComment').textContent = comment;

                    // Hide all reviews modal and show the single review modal
                    allReviewsModal.hide();
                    setTimeout(() => reviewModal.show(), 500);
                });
            });

            // Review modal handling
            const writeReviewModal = new bootstrap.Modal(document.getElementById('writeReviewModal'));
            const editReviewModal = new bootstrap.Modal(document.getElementById('editReviewModal'));
            const deleteReviewConfirmModal = new bootstrap.Modal(document.getElementById(
                'deleteReviewConfirmModal'));

            // Handle "Write a Review" button click
            document.querySelectorAll('.write-review-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    document.getElementById('review_booking_id').value = bookingId;

                    // Reset form
                    document.getElementById('reviewForm').reset();
                    document.getElementById('reviewAlerts').innerHTML = '';

                    // Show write review modal
                    writeReviewModal.show();
                });
            });

            // Handle review submission
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                const submitBtn = document.getElementById('submitReviewBtn');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

                // Get form data
                const formData = new FormData(this);

                // Validate form
                let isValid = true;
                let errors = [];

                const rating = formData.get('rating');
                const comment = formData.get('comment');

                if (!rating) {
                    document.querySelector('.rating-error').style.display = 'block';
                    errors.push('Please select a rating');
                    isValid = false;
                } else {
                    document.querySelector('.rating-error').style.display = 'none';
                }

                if (!comment || comment.length < 10 || comment.length > 1000) {
                    document.querySelector('.comment-error').style.display = 'block';
                    errors.push('Please enter a review between 10 and 1000 characters');
                    isValid = false;
                } else {
                    document.querySelector('.comment-error').style.display = 'none';
                }

                if (!isValid) {
                    // Show errors
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    return;
                }

                // Submit data via AJAX using a regular form submission approach (not JSON)
                fetch('{{ route('reviews.quick') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData // Using FormData instead of JSON.stringify
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Check for either success key (from older code) or status == 'success' (from newer code)
                        if (data.success || data.status === 'success') {
                            // Show success message
                            Swal.fire({
                                title: 'Review Submitted!',
                                text: data.message ||
                                    'Your review has been submitted successfully and is pending approval.',
                                icon: 'success',
                                confirmButtonColor: '#92400b'
                            }).then(() => {
                                // Close modal and reload page to show the new review
                                const writeReviewModal = bootstrap.Modal.getInstance(document
                                    .getElementById('writeReviewModal'));
                                writeReviewModal.hide();
                                location.reload();
                            });
                        } else {
                            // Show error message
                            const errorMessage = data.message ||
                                'An error occurred while submitting your review.';
                            const alertHtml = `
                            <div class="alert alert-danger">
                                <strong>Error!</strong> ${errorMessage}
                            </div>
                        `;
                            document.getElementById('reviewAlerts').innerHTML = alertHtml;

                            // Re-enable submit button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Show error message
                        const alertHtml = `
                        <div class="alert alert-danger">
                            <strong>Error!</strong> An unexpected error occurred. Please try again.
                        </div>
                    `;
                        document.getElementById('reviewAlerts').innerHTML = alertHtml;

                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    });
            });

            // Open edit review modal when clicking on user's own review
            // We need to check if the user is the author of the review
            @auth
            document.querySelectorAll('.review-card').forEach(card => {
                const reviewId = card.getAttribute('data-review-id');
                const reviewerName = card.querySelector('.reviewer-name').textContent.trim();

                // Check if the logged-in user is the author of this review
                // This is a simple check comparing the name, but you might want to use user IDs instead
                if (reviewerName.includes('{{ Auth::user()->first_name }}')) {
                    card.classList.add('my-review');

                    // Add a click handler for edit when it's the user's own review
                    card.addEventListener('click', function(e) {
                        // If it's a click on the review card but not on any action buttons
                        if (e.target.closest('.review-actions') === null) {
                            // Fetch review data
                            fetch(`{{ url('/reviews') }}/${reviewId}/edit`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.review) {
                                        const review = data.review;

                                        // Populate edit form
                                        document.getElementById('edit_review_id').value = review
                                            .id;
                                        document.querySelector(
                                            `#editStarRating input[value="${review.rating}"]`
                                        ).checked = true;
                                        document.getElementById('edit_comment').value = review
                                            .comment;

                                        // Show review status
                                        const statusHtml = `
                                            <div class="review-status status-${review.status}">
                                                <i class="fas ${review.status === 'approved' ? 'fa-check-circle' : (review.status === 'rejected' ? 'fa-times-circle' : 'fa-clock')} me-1"></i>
                                                Status: ${review.status.charAt(0).toUpperCase() + review.status.slice(1)}
                                                ${review.status === 'pending' ? '<small class="ms-2">(Your review is awaiting approval)</small>' : ''}
                                                ${review.status === 'rejected' ? '<small class="ms-2">(Your review may need revisions)</small>' : ''}
                                            </div>
                                        `;
                                        document.querySelector('.edit-review-status')
                                            .innerHTML = statusHtml;

                                        // Show edit modal instead of view modal
                                        if (reviewModal._isShown) {
                                            reviewModal.hide();
                                            setTimeout(() => editReviewModal.show(), 500);
                                        } else {
                                            editReviewModal.show();
                                        }
                                    } else {
                                        console.error('Error fetching review data:', data
                                            .message || 'Unknown error');
                                        // Show regular review modal as fallback
                                        reviewModal.show();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    // Show regular review modal as fallback
                                    reviewModal.show();
                                });
                        }
                    });
                }
            });
        @endauth

        // Handle edit review form submission
        document.getElementById('editReviewForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Disable submit button and show loading
            const updateBtn = document.getElementById('updateReviewBtn');
            const originalBtnText = updateBtn.innerHTML;
            updateBtn.disabled = true;
            updateBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

            // Get form data
            const formData = new FormData(this);
            const reviewId = formData.get('review_id');
            const rating = formData.get('rating');
            const comment = formData.get('comment');

            // Validate form
            let isValid = true;
            let errors = [];

            if (!rating) {
                document.querySelector('.edit-rating-error').style.display = 'block';
                errors.push('Please select a rating');
                isValid = false;
            } else {
                document.querySelector('.edit-rating-error').style.display = 'none';
            }

            if (!comment || comment.length < 10 || comment.length > 1000) {
                document.querySelector('.edit-comment-error').style.display = 'block';
                errors.push('Please enter a review between 10 and 1000 characters');
                isValid = false;
            } else {
                document.querySelector('.edit-comment-error').style.display = 'none';
            }

            if (!isValid) {
                // Show errors
                updateBtn.disabled = false;
                updateBtn.innerHTML = originalBtnText;
                return;
            }

            // Submit data via AJAX
            fetch(`{{ url('/reviews') }}/${reviewId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: rating,
                        comment: comment
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Review Updated!',
                            text: 'Your review has been updated successfully and is pending approval.',
                            icon: 'success',
                            confirmButtonColor: '#92400b'
                        }).then(() => {
                            // Close modal and reload page to show the updated review
                            editReviewModal.hide();
                            location.reload();
                        });
                    } else {
                        // Show error message
                        const alertHtml = `
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> ${data.message || 'An error occurred while updating your review.'}
                                </div>
                            `;
                        document.getElementById('editReviewAlerts').innerHTML = alertHtml;

                        // Re-enable submit button
                        updateBtn.disabled = false;
                        updateBtn.innerHTML = originalBtnText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Show error message
                    const alertHtml = `
                            <div class="alert alert-danger">
                                <strong>Error!</strong> An unexpected error occurred. Please try again.
                            </div>
                        `;
                    document.getElementById('editReviewAlerts').innerHTML = alertHtml;

                    // Re-enable submit button
                    updateBtn.disabled = false;
                    updateBtn.innerHTML = originalBtnText;
                });
        });

        // Handle delete review button click
        document.getElementById('deleteReviewBtn').addEventListener('click', function() {
            // Show delete confirmation modal
            deleteReviewConfirmModal.show();
        });

        // Handle confirm delete button click
        document.getElementById('confirmDeleteReview').addEventListener('click', function() {
            const reviewId = document.getElementById('edit_review_id').value;

            // Disable button and show loading
            this.disabled = true;
            this.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';

            // Submit delete request
            fetch(`{{ url('/reviews') }}/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            title: 'Review Deleted!',
                            text: 'Your review has been deleted successfully.',
                            icon: 'success',
                            confirmButtonColor: '#92400b'
                        }).then(() => {
                            // Close modals and reload page
                            deleteReviewConfirmModal.hide();
                            editReviewModal.hide();
                            location.reload();
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'An error occurred while deleting your review.',
                            icon: 'error',
                            confirmButtonColor: '#92400b'
                        });

                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = 'Delete';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#92400b'
                    });

                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = 'Delete';
                });
        });
        });
    </script>
@endpush
