@extends('public.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/profile.css') }}">
    <style>
        /* Form styles */
        .info-input {
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
                            <a href="#referral-section" class="menu-item">
                                <i class="fas fa-user-friends"></i>
                                Referral Program
                            </a>
                            <a href="#loyalty-section" class="menu-item">
                                <i class="fas fa-award"></i>
                                Earned Points
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
                        <div class="section-title d-flex justify-content-between align-items-center">
                            My Bookings
                            <span class="booking-total ms-2 small">
                                <span class="text-muted">Total:</span>
                                <span class="fw-bold ms-1">{{ $user->bookings->count() }}</span>
                            </span>
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
                            <div class="booking-tab" data-tab="reviews">
                                My Reviews
                                <span class="booking-tab-count">{{ $user->reviews->count() }}</span>
                            </div>
                        </div>

                        <!-- Upcoming Bookings -->
                        <div class="booking-content active" id="upcoming-content"
                            style="max-height: 400px; overflow-y: auto;">
                            @forelse($user->bookings->whereIn('status', ['confirmed', 'pending'])->sortByDesc('created_at') as $booking)
                                @foreach ($booking->activities as $activity)
                                    <div class="booking-card">
                                        <div class="booking-card-header">
                                            <h4 class="booking-card-title">{{ $activity->name }}</h4>
                                            <span
                                                class="booking-status status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                                        </div>
                                        <div class="booking-card-body">
                                            <div class="booking-detail">
                                                <span class="booking-detail-label">Location</span>
                                                <span class="booking-detail-value"><i class="fas fa-map-marker-alt"></i>
                                                    {{ $activity->location }}</span>
                                            </div>
                                            <div class="booking-detail">
                                                <span class="booking-detail-label">Date</span>
                                                <span class="booking-detail-value">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}
                                                </span>
                                            </div>
                                            <div class="booking-detail">
                                                <span class="booking-detail-label">Tickets</span>
                                                <span class="booking-detail-value"><i class="fas fa-users"></i>
                                                    {{ $booking->ticket_count }}
                                                    {{ Str::plural('ticket', $booking->ticket_count) }}</span>
                                            </div>
                                            <div class="booking-detail">
                                                <span class="booking-detail-label">Total Price</span>
                                                <span class="booking-detail-value"><i class="fas fa-money-bill-wave"></i>
                                                    ${{ number_format($booking->total_price, 2) }} <small
                                                        class="text-muted">(incl. tax)</small></span>
                                            </div>
                                        </div>
                                        @if ($booking->status == 'pending')
                                            <div class="booking-card-footer">
                                                <button type="button"
                                                    class="btn-profile btn-profile-danger btn-profile-sm cancel-booking-btn"
                                                    data-bs-toggle="modal" data-bs-target="#cancelBookingModal"
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-activity-name="{{ $activity->name }}">
                                                    <i class="fas fa-times-circle"></i> Cancel Booking
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @empty
                                <div class="empty-bookings">
                                    <i class="fas fa-calendar-times"></i>
                                    <h5>No Upcoming Trips</h5>
                                    <p>You don't have any upcoming bookings. <br>Start exploring and book your next
                                        adventure!</p>
                                    <a href="{{ route('services') }}"
                                        class="btn-profile btn-profile-primary mt-3">Explore
                                        Activities</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Completed Bookings -->
                    <div class="booking-content" id="completed-content" style="max-height: 400px; overflow-y: auto;">
                        @forelse($user->bookings->where('status', 'completed')->sortByDesc('created_at') as $booking)
                            @foreach ($booking->activities as $activity)
                                <div class="booking-card">
                                    <div class="booking-card-header">
                                        <h4 class="booking-card-title">{{ $activity->name }}</h4>
                                        <span class="booking-status status-completed">Completed</span>
                                    </div>
                                    <div class="booking-card-body">
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Location</span>
                                            <span class="booking-detail-value"><i class="fas fa-map-marker-alt"></i>
                                                {{ $activity->location }}</span>
                                        </div>
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Date</span>
                                            <span class="booking-detail-value">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }} -
                                                {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Tickets</span>
                                            <span class="booking-detail-value"><i class="fas fa-users"></i>
                                                {{ $booking->ticket_count }}
                                                {{ Str::plural('ticket', $booking->ticket_count) }}</span>
                                        </div>
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Total Price</span>
                                            <span class="booking-detail-value"><i class="fas fa-money-bill-wave"></i>
                                                ${{ number_format($booking->total_price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="booking-card-footer">
                                        @if (!$booking->hasReview())
                                            <a href="{{ route('reviews.create', ['booking_id' => $booking->id, 'activity_id' => $activity->id]) }}"
                                                class="btn-profile btn-profile-primary btn-profile-sm">
                                                <i class="fas fa-star"></i> Write a Review
                                            </a>
                                        @else
                                            <a href="{{ route('reviews.edit', $booking->review->id) }}"
                                                class="btn-profile btn-profile-secondary btn-profile-sm">
                                                <i class="fas fa-pencil-alt"></i> Edit Review
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @empty
                            <div class="empty-bookings">
                                <i class="fas fa-check-circle"></i>
                                <h5>No Completed Trips</h5>
                                <p>You haven't completed any bookings yet.</p>
                                <a href="{{ route('services') }}" class="btn-profile btn-profile-primary mt-3">Explore
                                    Activities</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Cancelled Bookings -->
                    <div class="booking-content" id="cancelled-content" style="max-height: 400px; overflow-y: auto;">
                        @forelse($user->bookings->where('status', 'cancelled')->sortByDesc('created_at') as $booking)
                            @foreach ($booking->activities as $activity)
                                <div class="booking-card">
                                    <div class="booking-card-header">
                                        <h4 class="booking-card-title">{{ $activity->name }}</h4>
                                        <span class="booking-status status-cancelled">Cancelled</span>
                                    </div>
                                    <div class="booking-card-body">
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Location</span>
                                            <span class="booking-detail-value"><i class="fas fa-map-marker-alt"></i>
                                                {{ $activity->location }}</span>
                                        </div>
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Date</span>
                                            <span class="booking-detail-value">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }} -
                                                {{ \Carbon\Carbon::parse($activity->end_date)->format('M d, Y') }}
                                            </span>
                                        </div>
                                        <div class="booking-detail">
                                            <span class="booking-detail-label">Cancelled On</span>
                                            <span class="booking-detail-value"><i class="fas fa-times-circle"></i>
                                                {{ $booking->updated_at->format('M d, Y') }}</span>
                                        </div>
                                        @if ($booking->payment_status == 'refunded')
                                            <div class="booking-detail">
                                                <span class="booking-detail-label">Status</span>
                                                <span class="booking-detail-value text-success"><i
                                                        class="fas fa-undo-alt"></i> Refunded</span>
                                            </div>
                                        @endif
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

                    <!-- Reviews Content Tab -->
                    <div class="booking-content" id="reviews-content" style="max-height: 400px; overflow-y: auto;">
                        @forelse($user->reviews as $review)
                            <div class="booking-card">
                                <div class="booking-card-header">
                                    <h4 class="booking-card-title">Review for: {{ $review->activity->name }}</h4>
                                    <span class="booking-status status-{{ $review->status }}">
                                        <i
                                            class="fas {{ $review->status == 'approved' ? 'fa-check-circle' : ($review->status == 'rejected' ? 'fa-times-circle' : 'fa-clock') }}"></i>
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </div>
                                <div class="booking-card-body">
                                    <div class="booking-detail" style="grid-column: 1 / -1;">
                                        <span class="booking-detail-label">Submitted On</span>
                                        <span class="booking-detail-value"><i class="far fa-calendar-alt"></i>
                                            {{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="booking-detail">
                                        <span class="booking-detail-label">Rating</span>
                                        <span class="booking-detail-value">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-2 fw-bold">({{ $review->rating }}/5)</span>
                                        </span>
                                    </div>
                                    <div class="booking-detail" style="grid-column: 1 / -1;">
                                        <span class="booking-detail-label">Comment</span>
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                                <div class="booking-card-footer">
                                    <a href="{{ route('reviews.edit', $review->id) }}"
                                        class="btn-profile btn-profile-secondary btn-profile-sm">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form id="delete-review-form-{{ $review->id }}"
                                        action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-profile btn-profile-danger btn-profile-sm"
                                            onclick="confirmDeleteReview({{ $review->id }}, '{{ $review->activity->name }}')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="empty-bookings">
                                <i class="fas fa-star"></i>
                                <h5>No Reviews Yet</h5>
                                <p>You haven't left any reviews yet. Share your experiences by reviewing activities you've
                                    completed.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Referral Program Section -->
                    <div class="content-card" id="referral-section">
                        <div class="section-title">
                            Referral Program
                        </div>
                        <div class="referral-code">
                            <div>
                                <div class="code-label">Your Referral Code</div>
                                <div class="code-display">
                                    <span
                                        class="code-value">{{ $user->referral_code ?? 'No referral code available' }}</span>
                                    <button id="copy-referral-code-btn"
                                        class="btn-profile btn-profile-secondary btn-profile-sm">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <span id="copy-feedback-message" style="display: none;">Copied!</span>
                                </div>
                            </div>
                            <div>
                                <div class="rewards-label">Total Rewards Earned</div>
                                <div class="rewards-value">{{ $user->referrals->count() }} successful referrals
                                    (${{ number_format($user->referrals->count() * 10, 2) }})</div>
                            </div>
                        </div>
                        <div class="referral-stats">
                            <div class="stat-item">
                                <span class="stat-number">${{ number_format($user->referral_balance, 2) }}</span>
                                <span class="stat-text">Available Credit Balance</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $user->remaining_referrals }}</span>
                                <span class="stat-text">Referrals Remaining</span>
                            </div>
                        </div>
                    </div>

                    <!-- Loyalty Points & Referral Credits Section -->
                    <div class="content-card" id="loyalty-section">
                        <div class="section-title">
                            My Rewards & Points
                        </div>

                        <div class="rewards-overview mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="rewards-card referral-card">
                                        <div class="rewards-icon">
                                            <i class="fas fa-user-friends"></i>
                                        </div>
                                        <div class="rewards-details">
                                            <h4>Referral Credit</h4>
                                            <div class="rewards-amount">${{ number_format($user->referral_balance, 2) }}
                                            </div>
                                            <p class="text-muted">You earn $10 per successful referral (max 5)</p>
                                            <div class="rewards-progress mt-2">
                                                <div class="progress">
                                                    @php
                                                        $referralCount = $user->referrals->count();
                                                        $maxReferrals = 5; // Assuming max 5 referrals
                                                        $percentage = ($referralCount / $maxReferrals) * 100;
                                                    @endphp
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $percentage }}%;"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <div class="progress-text">
                                                    <span>{{ $referralCount }} of {{ $maxReferrals }} referrals
                                                        used</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="rewards-card loyalty-card">
                                        <div class="rewards-icon">
                                            <i class="fas fa-award"></i>
                                        </div>
                                        <div class="rewards-details">
                                            <h4>Loyalty Points</h4>
                                            <div class="rewards-amount">{{ number_format($user->available_points) }} <span
                                                    class="points-value">points</span></div>
                                            <p class="text-muted">Worth
                                                ${{ number_format($user->available_points * 0.1, 2) }} in future bookings
                                            </p>
                                            <div class="rewards-info mb-2">
                                                {{-- Add any specific info if needed --}}
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('services') }}"
                                                    class="btn-profile btn-profile-primary btn-profile-sm">
                                                    <i class="fas fa-shopping-bag"></i> Redeem Points
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rewards-how-it-works">
                            <h5><i class="fas fa-info-circle me-2"></i>How it works</h5>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="how-it-works-card">
                                        <h6>Referral Program</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>Share your code:
                                                <strong>{{ $user->referral_code }}</strong>
                                            </li>
                                            <li><i class="fas fa-check text-success me-2"></i>Earn $10 per successful
                                                referral</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Maximum of 5 successful
                                                referrals</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="how-it-works-card">
                                        <h6>Loyalty Points</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>Earn 1 point for each $1
                                                spent</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Each point is worth $0.10
                                            </li>
                                            <li><i class="fas fa-check text-success me-2"></i>Redeem on future bookings
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
                                                <button type="submit" class="remove-btn" title="Remove from Wishlist">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="trip-details">
                                            <h4 class="trip-title">{{ $activity->name }}</h4>
                                            <div class="trip-info">
                                                <span class="trip-category">
                                                    <i class="fas fa-tag"></i>
                                                    {{ $activity->mainCategory->name ?? 'N/A' }}
                                                </span>
                                                <span class="trip-price">
                                                    <i class="fas fa-dollar-sign"></i>
                                                    {{ $activity->price ? number_format($activity->price, 2) : 'Varies' }}
                                                </span>
                                            </div>
                                            <div class="trip-actions">
                                                <a href="{{ route('activity.detail', $activity->id) }}"
                                                    class="btn-profile btn-profile-light btn-profile-sm view-details-btn">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('activity.detail', $activity->id) }}"
                                                    class="btn-profile btn-profile-primary btn-profile-sm book-now-btn">
                                                    <i class="fas fa-calendar-check"></i> Book Now
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
                                <a href="{{ route('services') }}"
                                    class="btn-profile btn-profile-primary browse-btn">Browse Activities</a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Cancel Booking Confirmation Modal -->
    <div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelBookingModalLabel">Confirm Cancellation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <h5>Are you sure you want to cancel this booking?</h5>
                    <p class="mb-1">Activity: <span id="activity-name-display"></span></p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-profile-light" data-bs-dismiss="modal">No, Keep My
                        Booking</button>
                    <form id="cancel-booking-form" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn-profile btn-profile-danger">Yes, Cancel Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Confirm delete review function
        function confirmDeleteReview(reviewId, activityName) {
            Swal.fire({
                title: 'Delete Review',
                text: `Are you sure you want to delete your review for ${activityName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn-profile btn-profile-danger',
                    cancelButton: 'btn-profile btn-profile-light me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-review-form-${reviewId}`).submit();
                }
            });
        }

        // Show inputs initially if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
            const personalInfoSection = document.getElementById('personal-info-section');
            const passwordSection = document.getElementById('password-section');

            if (hasErrors) {
                personalInfoSection.classList.add('edit-mode');
                const infoValues = personalInfoSection.querySelectorAll('.info-value');
                const infoInputs = personalInfoSection.querySelectorAll('.info-input');
                infoValues.forEach(value => {
                    value.style.display = 'none';
                });
                infoInputs.forEach(input => {
                    input.style.display = 'block';
                });
                document.getElementById('edit-info-btn').style.display = 'none';
                document.getElementById('save-info-btn').style.display = 'inline-block';
            }

            // Booking tabs functionality
            const bookingTabs = document.querySelectorAll('.booking-tab');
            bookingTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    bookingTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    document.querySelectorAll('.booking-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-content').classList.add('active');
                });
            });

            // Cancel booking modal functionality
            const cancelBookingButtons = document.querySelectorAll('.cancel-booking-btn');
            const cancelBookingForm = document.getElementById('cancel-booking-form');
            const activityNameDisplay = document.getElementById('activity-name-display');

            cancelBookingButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    const activityName = this.getAttribute('data-activity-name');
                    cancelBookingForm.action = `/booking/${bookingId}/cancel`;
                    activityNameDisplay.textContent = activityName;
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

                        document.querySelectorAll('.sidebar-menu .menu-item').forEach(i => {
                            i.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                });
            });

            // Copyable referral code functionality
            const copyCodeBtn = document.getElementById('copy-referral-code-btn');
            const codeFeedback = document.getElementById('copy-feedback-message');

            if (copyCodeBtn) {
                copyCodeBtn.addEventListener('click', function() {
                    // Get the referral code text
                    const codeElement = document.querySelector('.code-value');
                    const codeText = codeElement.textContent.trim();

                    // Create a temporary textarea element to copy from
                    const textarea = document.createElement('textarea');
                    textarea.value = codeText;
                    textarea.setAttribute('readonly', '');
                    textarea.style.position = 'absolute';
                    textarea.style.left = '-9999px';
                    document.body.appendChild(textarea);

                    // Select and copy the text
                    textarea.select();
                    document.execCommand('copy');

                    // Remove the temporary element
                    document.body.removeChild(textarea);

                    // Show feedback
                    codeFeedback.style.display = 'inline-block';

                    // Hide feedback after 2 seconds
                    setTimeout(function() {
                        codeFeedback.style.display = 'none';
                    }, 2000);
                });
            }
        });

        // Edit mode for personal information
        document.getElementById('edit-info-btn').addEventListener('click', function() {
            const section = document.getElementById('personal-info-section');
            section.classList.add('edit-mode');

            const infoValues = section.querySelectorAll('.info-value');
            const infoInputs = section.querySelectorAll('.info-input');

            infoValues.forEach(value => {
                value.style.display = 'none';
            });

            infoInputs.forEach(input => {
                input.style.display = 'block';
            });

            this.style.display = 'none';
            document.getElementById('save-info-btn').style.display = 'inline-block';
        });

        // Password edit functionality
        document.getElementById('edit-password-btn').addEventListener('click', function() {
            document.querySelector('.password-form-container').style.display = 'block';
            this.style.display = 'none';
            document.getElementById('save-password-btn').style.display = 'inline-block';
        });
    </script>
@endpush
