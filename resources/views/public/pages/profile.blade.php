@extends('public.layouts.main')
@push('styles')
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
        }

        .booking-content.active {
            display: block;
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
    </style>
@endpush

@section('title', 'Profile')

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="sidebar">
                        <div class="sidebar-header">
                            <div class="avatar">
                                @if ($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->first_name }}"
                                        class="img-fluid rounded-circle">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <div class="user-name">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</div>
                            <div class="membership">Gold Member</div>
                        </div>
                        <div class="sidebar-menu">
                            <a href="#personal-info-section" class="menu-item">
                                <i class="fas fa-user-edit"></i>
                                Personal Information
                            </a>
                            <a href="#password-section" class="menu-item">
                                <i class="fas fa-lock"></i>
                                Change Password
                            </a>
                            <a href="#trips-section" class="menu-item active">
                                <i class="fas fa-suitcase"></i>
                                Booking History
                            </a>
                            <a href="#loyalty-section" class="menu-item">
                                <i class="fas fa-award"></i>
                                Loyalty Points
                            </a>
                            <a href="#referral-section" class="menu-item">
                                <i class="fas fa-user-friends"></i>
                                Referral Program
                            </a>
                            <a href="#wishlist-section" class="menu-item">
                                <i class="fas fa-heart"></i>
                                Wishlist
                            </a>
                            <a href="{{ route('logout') }}" class="menu-item"
                                onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Main Profile Content -->
                <div class="col-lg-9">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Personal Information Section -->
                    <div class="content-card" id="personal-info-section">
                        <div class="section-title">
                            Personal Information
                            <button class="edit-button" id="edit-info-btn"><i class="fas fa-pencil-alt me-1"></i>
                                Edit</button>
                            <button type="submit" form="profile-form" class="save-button" id="save-info-btn"><i
                                    class="fas fa-save me-1"></i> Save</button>
                        </div>
                        <form id="profile-form" action="{{ route('profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="personal-info-grid">
                                @if ($errors->any())
                                    <div class="alert alert-danger col-12 mb-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="info-field">
                                    <div class="info-label">First Name</div>
                                    <div class="info-value">{{ $user->first_name }}</div>
                                    <input type="text" name="first_name"
                                        class="form-control info-input @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name', $user->first_name) }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Last Name</div>
                                    <div class="info-value">{{ $user->last_name }}</div>
                                    <input type="text" name="last_name" class="form-control info-input"
                                        value="{{ $user->last_name }}">
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ $user->email }}</div>
                                    <input type="email" name="email" class="form-control info-input"
                                        value="{{ $user->email }}">
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value">{{ $user->phone ?? 'Not set' }}</div>
                                    <input type="tel" name="phone" class="form-control info-input"
                                        value="{{ $user->phone }}">
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Profile Image</div>
                                    <div class="info-value">
                                        @if ($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile"
                                                style="width: 100px; height: 100px; object-fit: cover;"
                                                class="rounded-circle">
                                        @else
                                            No image set
                                        @endif
                                    </div>
                                    <input type="file" name="profile_image"
                                        class="form-control info-input @error('profile_image') is-invalid @enderror">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-1">Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change Section -->
                    <div class="content-card mt-4" id="password-section">
                        <div class="section-title">
                            Change Password
                            <button class="edit-button" id="edit-password-btn"><i class="fas fa-pencil-alt me-1"></i>
                                Edit</button>
                            <button type="submit" form="password-form" class="save-button" id="save-password-btn"><i
                                    class="fas fa-save me-1"></i> Save</button>
                        </div>
                        <form id="password-form" action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            <div class="password-form-container" style="display: none;">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation"
                                        id="new_password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- My Trips Section -->
                    <div class="content-card" id="trips-section">
                        <div class="section-title">
                            My Bookings
                        </div>

                        <!-- Booking Statistics Summary -->
                        <div class="stats-summary mb-4">
                            <div class="stat-card">
                                <div class="stat-icon stat-primary">
                                    <i class="fas fa-suitcase"></i>
                                </div>
                                <div class="stat-value">{{ $user->bookings->count() }}</div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon stat-green">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-value">{{ $user->bookings->where('status', 'completed')->count() }}</div>
                                <div class="stat-label">Completed</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon stat-orange">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-value">
                                    {{ $user->bookings->whereIn('status', ['confirmed', 'pending'])->count() }}</div>
                                <div class="stat-label">Upcoming</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon stat-purple">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="stat-value">
                                    {{ $user->bookings->pluck('activities')->flatten()->unique('id')->count() }}</div>
                                <div class="stat-label">Unique Activities</div>
                            </div>
                        </div>

                        <!-- Booking Tabs -->
                        <div class="booking-tabs">
                            <div class="booking-tab active" data-tab="upcoming">
                                Upcoming
                                <span
                                    class="booking-tab-count">{{ $user->bookings->whereIn('status', ['confirmed', 'pending'])->count() }}</span>
                            </div>
                            <div class="booking-tab" data-tab="completed">
                                Completed
                                <span
                                    class="booking-tab-count">{{ $user->bookings->where('status', 'completed')->count() }}</span>
                            </div>
                            <div class="booking-tab" data-tab="cancelled">
                                Cancelled
                                <span
                                    class="booking-tab-count">{{ $user->bookings->where('status', 'cancelled')->count() }}</span>
                            </div>
                        </div>

                        <!-- Upcoming Bookings -->
                        <div class="booking-content active" id="upcoming-content">
                            @forelse($user->bookings->whereIn('status', ['confirmed', 'pending']) as $booking)
                                @foreach ($booking->activities as $activity)
                                    <div class="trip-card">
                                        <div
                                            class="trip-label {{ $booking->status == 'confirmed' ? 'label-green' : 'label-orange' }}">
                                            {{ ucfirst($booking->status) }}</div>
                                        <h4 class="trip-title">{{ $activity->name }}</h4>
                                        <div class="trip-detail">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $activity->location }}
                                        </div>
                                        <div class="trip-date">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }} -
                                            {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <div>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-users me-1"></i> {{ $booking->ticket_count }}
                                                    {{ Str::plural('ticket', $booking->ticket_count) }}
                                                </span>
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    ${{ number_format($booking->total_price, 2) }}
                                                </span>
                                            </div>
                                            <a href="#" class="view-details">View Details</a>
                                        </div>
                                    </div>
                                @endforeach
                            @empty
                                <div class="empty-bookings">
                                    <i class="fas fa-calendar-times"></i>
                                    <h5>No Upcoming Trips</h5>
                                    <p>You don't have any upcoming bookings. <br>Start exploring and book your next
                                        adventure!</p>
                                    <a href="{{ route('services') }}" class="btn explore-btn mt-3">Explore Activities</a>
                                </div>
                            @endforelse
                        </div>

                        <!-- Completed Bookings -->
                        <div class="booking-content" id="completed-content">
                            @forelse($user->bookings->where('status', 'completed') as $booking)
                                @foreach ($booking->activities as $activity)
                                    <div class="trip-card">
                                        <div class="trip-label label-blue">Completed</div>
                                        <h4 class="trip-title">{{ $activity->name }}</h4>
                                        <div class="trip-detail">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $activity->location }}
                                        </div>
                                        <div class="trip-date">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }} -
                                            {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <div>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-users me-1"></i> {{ $booking->ticket_count }}
                                                    {{ Str::plural('ticket', $booking->ticket_count) }}
                                                </span>
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    ${{ number_format($booking->total_price, 2) }}
                                                </span>
                                            </div>
                                            <div>
                                                @if (!$booking->hasReview())
                                                    <a href="#" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="fas fa-star me-1"></i> Write Review
                                                    </a>
                                                @endif
                                                <a href="#" class="view-details">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @empty
                                <div class="empty-bookings">
                                    <i class="fas fa-check-circle"></i>
                                    <h5>No Completed Trips</h5>
                                    <p>You haven't completed any bookings yet.</p>
                                    <a href="{{ route('services') }}" class="btn explore-btn mt-3">Explore Activities</a>
                                </div>
                            @endforelse
                        </div>

                        <!-- Cancelled Bookings -->
                        <div class="booking-content" id="cancelled-content">
                            @forelse($user->bookings->where('status', 'cancelled') as $booking)
                                @foreach ($booking->activities as $activity)
                                    <div class="trip-card">
                                        <div class="trip-label label-orange">Cancelled</div>
                                        <h4 class="trip-title">{{ $activity->name }}</h4>
                                        <div class="trip-detail">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $activity->location }}
                                        </div>
                                        <div class="trip-date">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }} -
                                            {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}
                                        </div>
                                        <div class="trip-detail">
                                            <i class="fas fa-times-circle me-1"></i> Cancelled on
                                            {{ $booking->updated_at->format('M d, Y') }}
                                        </div>

                                        <div class="d-flex justify-content-between mt-3">
                                            <div>
                                                @if ($booking->payment_status == 'refunded')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-undo me-1"></i> Refunded
                                                    </span>
                                                @endif
                                            </div>
                                            <a href="#" class="view-details">View Details</a>
                                        </div>
                                    </div>
                                @endforeach
                            @empty
                                <div class="empty-bookings">
                                    <i class="fas fa-ban"></i>
                                    <h5>No Cancelled Trips</h5>
                                    <p>You don't have any cancelled bookings.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Loyalty Points Section -->
                    <div class="content-card" id="loyalty-section">
                        <div class="section-title">
                            Loyalty Points
                            <a href="#" class="see-all">View All Benefits</a>
                        </div>
                        <div class="points-container">
                            <div class="points-card green">
                                <div class="points-value">
                                    {{ $user->bookings->sum('loyalty_points_earned') - $user->bookings->sum('loyalty_points_used') }}
                                </div>
                                <div class="points-label">Available Points</div>
                            </div>
                            <div class="points-card blue">
                                <div class="points-value">
                                    {{ $user->bookings->where('created_at', '>=', now()->startOfMonth())->sum('loyalty_points_earned') }}
                                </div>
                                <div class="points-label">Points Earned this Month</div>
                            </div>
                            <div class="points-card purple">
                                <div class="points-value">1,000</div>
                                <div class="points-label">Points to Next Tier</div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Program Section -->
                    <div class="content-card" id="referral-section">
                        <div class="section-title">
                            Referral Program
                        </div>
                        <div class="referral-code">
                            <div>
                                <div class="code-label">Your Referral Code</div>
                                <div class="code-value">{{ strtoupper(substr($user->first_name, 0, 5) . rand(100, 999)) }}
                                </div>
                            </div>
                            <div>
                                <div class="rewards-label">Rewards Earned</div>
                                <div class="rewards-value">$150</div>
                            </div>
                        </div>
                        <div class="share-buttons">
                            <button class="share-button share-email">Share via Email</button>
                            <button class="share-button share-whatsapp">Share via WhatsApp</button>
                        </div>
                    </div>

                    <!-- Saved Trips Section -->
                    <div class="content-card" id="wishlist-section">
                        <div class="section-title">
                            My Wishlist
                        </div>

                        @if (isset($wishlistedActivities) && $wishlistedActivities->count() > 0)
                            <div class="saved-trips-grid">
                                @foreach ($wishlistedActivities as $activity)
                                    <div class="saved-trip-card">
                                        <div class="trip-image">
                                            <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'api/placeholder/400/300') }}"
                                                alt="{{ $activity->name }}">
                                            <form action="{{ route('wishlist.remove', $activity->id) }}" method="POST"
                                                class="remove-wishlist-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="trip-details">
                                            <h4 class="trip-title">{{ $activity->name }}</h4>
                                            <div class="trip-info">
                                                <span class="trip-category">
                                                    <i class="fas fa-tag"></i>
                                                    {{ $activity->categoryType->name ?? 'N/A' }}
                                                </span>
                                                <span class="trip-price">
                                                    <i class="fas fa-dollar-sign"></i>
                                                    {{ number_format($activity->price, 0) }}
                                                </span>
                                            </div>
                                            <div class="trip-actions">
                                                <a href="{{ route('activity.detail', $activity->id) }}"
                                                    class="view-details-btn">
                                                    View Details
                                                </a>
                                                <a href="{{ route('activity.detail', $activity->id) }}"
                                                    class="book-now-btn">
                                                    Book Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-wishlist">
                                <i class="far fa-heart"></i>
                                <h4>Your wishlist is empty</h4>
                                <p>Save your favorite activities by clicking the heart icon while browsing activities.</p>
                                <a href="{{ route('services') }}" class="btn browse-btn">Browse Activities</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Show inputs initially if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            const hasErrors = {{ $errors->any() ? 'true' : 'false' }};

            if (hasErrors) {
                // Automatically show edit mode if there are validation errors
                const section = document.getElementById('personal-info-section');
                section.classList.add('edit-mode');

                // Hide info values and show input fields
                const infoValues = section.querySelectorAll('.info-value');
                const infoInputs = section.querySelectorAll('.info-input');

                infoValues.forEach(value => {
                    value.style.display = 'none';
                });

                infoInputs.forEach(input => {
                    input.style.display = 'block';
                });

                // Hide edit button and show save button
                document.getElementById('edit-info-btn').style.display = 'none';
                document.getElementById('save-info-btn').style.display = 'inline-block';
            }

            // Booking tabs functionality
            const bookingTabs = document.querySelectorAll('.booking-tab');

            bookingTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    bookingTabs.forEach(t => t.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all content sections
                    document.querySelectorAll('.booking-content').forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show content corresponding to clicked tab
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-content').classList.add('active');
                });
            });

            // Smooth scroll to sections
            document.querySelectorAll('.sidebar-menu .menu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const target = this.getAttribute('href');
                    if (target.startsWith('#')) {
                        e.preventDefault();
                        const targetElement = document.querySelector(target);
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });

                        // Update active menu item
                        document.querySelectorAll('.sidebar-menu .menu-item').forEach(i => {
                            i.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                });
            });
        });

        // Edit mode for personal information
        document.getElementById('edit-info-btn').addEventListener('click', function() {
            const section = document.getElementById('personal-info-section');
            section.classList.add('edit-mode');

            // Hide info values and show input fields
            const infoValues = section.querySelectorAll('.info-value');
            const infoInputs = section.querySelectorAll('.info-input');

            infoValues.forEach(value => {
                value.style.display = 'none';
            });

            infoInputs.forEach(input => {
                input.style.display = 'block';
            });

            // Hide edit button and show save button
            this.style.display = 'none';
            document.getElementById('save-info-btn').style.display = 'inline-block';
        });

        // Password edit functionality
        document.getElementById('edit-password-btn').addEventListener('click', function() {
            // Show password form
            document.querySelector('.password-form-container').style.display = 'block';

            // Hide edit button and show save button
            this.style.display = 'none';
            document.getElementById('save-password-btn').style.display = 'inline-block';
        });
    </script>
@endpush
