@extends('admin.layouts.admin')

@section('title', 'User Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold text-white mb-0">
                                    User Profile: {{ $user->first_name }} {{ $user->last_name }}
                                </h4>
                                <p class="text-white-50 mb-0">Manage user details, loyalty points, and referrals</p>
                            </div>
                            <div>
                                <a href="{{ route('users.index') }}" class="btn btn-light">
                                    <i class="bx bx-arrow-back me-1"></i> Back to Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible shadow-sm" role="alert">
                <div class="d-flex">
                    <i class="bx bx-check-circle me-2 bx-sm"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible shadow-sm" role="alert">
                <div class="d-flex">
                    <i class="bx bx-error-circle me-2 bx-sm"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        <div class="row">
            <!-- User Details Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-user-circle me-2 text-primary"></i>User Information
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center"
                                        href="{{ route('users.edit', $user->id) }}">
                                        <i class="bx bx-edit-alt me-2 text-primary"></i> Edit User
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center text-danger" type="button"
                                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                        <i class="bx bx-trash me-2"></i> Delete User
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <div class="avatar avatar-xl mb-3">
                                @if ($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->first_name }}"
                                        class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                            <span class="badge bg-label-primary">User ID: {{ $user->id }}</span>
                        </div>

                        <div class="user-info-list border-top pt-3">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 text-primary">
                                    <i class="bx bx-envelope me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Email</div>
                                    <div>{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 text-primary">
                                    <i class="bx bx-phone me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Phone</div>
                                    <div>{{ $user->phone ?? 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 text-primary">
                                    <i class="bx bx-check-circle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Status</div>
                                    <div><span class="badge bg-success">Active</span></div>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 text-primary">
                                    <i class="bx bx-calendar me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Registered</div>
                                    <div>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-primary">
                                    <i class="bx bx-refresh me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Last Updated</div>
                                    <div>{{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loyalty Points Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-star me-2 text-warning"></i>Loyalty Points
                        </h5>
                        <a href="{{ route('loyalty-points.edit-user', $user->id) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-edit me-1"></i> Manage Points
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="card bg-primary shadow-sm text-white text-center p-3 mx-auto"
                                style="max-width: 300px; border-radius: 15px;">
                                <div class="card-body">
                                    <div class="display-4 mb-2 fw-bold">{{ number_format($user->loyalty_points ?? 0) }}
                                    </div>
                                    <p class="mb-0 fs-6">Total Points Available</p>
                                </div>
                            </div>
                        </div>

                        <div class="points-history mt-4">
                            <h6 class="mb-3 fw-bold border-bottom pb-2 d-flex align-items-center">
                                <i class="bx bx-history me-2 text-primary"></i>Points History
                            </h6>

                            @if (isset($pointsHistory) && count($pointsHistory) > 0)
                                <div class="timeline-container"
                                    style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                    @foreach ($pointsHistory as $history)
                                        <div class="timeline-item mb-3">
                                            <div class="timeline-item-marker">
                                                <div
                                                    class="timeline-item-marker-indicator bg-{{ $history->points > 0 ? 'success' : 'danger' }}">
                                                    <i class="bx {{ $history->points > 0 ? 'bx-plus' : 'bx-minus' }}"></i>
                                                </div>
                                            </div>
                                            <div class="timeline-item-content">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span
                                                        class="fw-bold">{{ $history->points > 0 ? 'Points Earned' : 'Points Used' }}</span>
                                                    <span
                                                        class="fw-bold text-{{ $history->points > 0 ? 'success' : 'danger' }}">
                                                        {{ $history->points > 0 ? '+' : '' }}{{ $history->points }}
                                                    </span>
                                                </div>
                                                <p class="mb-1 small">{{ $history->description }}</p>
                                                <small
                                                    class="text-muted">{{ $history->created_at ? $history->created_at->format('M d, Y h:i A') : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="bx bx-star text-warning" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6>No Points Activity Yet</h6>
                                    <p class="text-muted small">Points activities will appear here once the user earns or
                                        uses points.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Information Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-gift me-2 text-info"></i>Referral Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">Referral Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light" id="referralCode"
                                    value="{{ $user->referral_code }}" readonly>
                                <button class="btn btn-outline-primary" type="button" id="copyReferralCode">
                                    <i class="bx bx-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">Share this code to earn referral rewards</small>
                        </div>

                        <div class="card bg-info text-white text-center p-3 mb-4">
                            <div class="card-body">
                                <h2 class="mb-2">${{ number_format($user->referral_balance ?? 0, 2) }}</h2>
                                <p class="mb-0">Referral Balance</p>
                            </div>
                        </div>

                        @if ($user->referred_by)
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">Referred By</label>
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-secondary">
                                            {{ strtoupper(substr($user->referrer->first_name ?? '', 0, 1) . substr($user->referrer->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $user->referrer->first_name ?? '' }}
                                            {{ $user->referrer->last_name ?? '' }}</h6>
                                        <small class="text-muted">ID: {{ $user->referred_by }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="form-label fw-bold mb-2 d-flex align-items-center">
                                <i class="bx bx-user-plus me-2 text-primary"></i>Referred Users
                            </label>
                            @if (isset($referredUsers) && count($referredUsers) > 0)
                                <div class="referred-users-grid">
                                    <div class="row g-3">
                                        @foreach ($referredUsers as $referredUser)
                                            <div class="col-md-6">
                                                <div class="card border shadow-sm h-100 referred-user-card">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md me-3">
                                                                @if ($referredUser->profile_image)
                                                                    <img src="{{ asset('storage/' . $referredUser->profile_image) }}"
                                                                        alt="{{ $referredUser->first_name }}"
                                                                        class="rounded-circle img-fluid">
                                                                @else
                                                                    <span class="avatar-initial rounded-circle bg-primary">
                                                                        {{ strtoupper(substr($referredUser->first_name, 0, 1) . substr($referredUser->last_name, 0, 1)) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">
                                                                    {{ $referredUser->first_name }}
                                                                    {{ $referredUser->last_name }}
                                                                </h6>
                                                                <small class="text-muted">
                                                                    <i
                                                                        class="bx bx-envelope me-1"></i>{{ $referredUser->email }}
                                                                </small>
                                                                <div class="mt-1">
                                                                    <span class="badge bg-label-info">
                                                                        <i class="bx bx-calendar me-1"></i>
                                                                        Joined
                                                                        {{ $referredUser->created_at ? $referredUser->created_at->format('M d, Y') : 'N/A' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <a href="{{ route('users.show', $referredUser->id) }}"
                                                                class="btn btn-sm btn-primary rounded-pill">
                                                                <i class="bx bx-show me-1"></i> View
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if (count($referredUsers) > 4)
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            id="loadMoreReferrals">
                                            <i class="bx bx-loader me-1"></i> Load More
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4 bg-light rounded">
                                    <div class="mb-3">
                                        <i class="bx bx-user-plus text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6>No Referred Users Yet</h6>
                                    <p class="text-muted mb-0">When users sign up using this user's referral code, they
                                        will appear here.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-activity me-2 text-primary"></i>User Activity
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Improved Tabs -->
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bookingsTab"
                                    role="tab" aria-selected="true">
                                    <i class="bx bx-calendar me-1"></i> Bookings
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activitiesTab"
                                    role="tab" aria-selected="false">
                                    <i class="bx bx-run me-1"></i> Activities
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviewsTab"
                                    role="tab" aria-selected="false">
                                    <i class="bx bx-star me-1"></i> Reviews
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#wishlistTab"
                                    role="tab" aria-selected="false">
                                    <i class="bx bx-heart me-1"></i> Wishlist
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content pt-4">
                            <!-- Bookings Tab -->
                            <div class="tab-pane fade show active" id="bookingsTab" role="tabpanel">
                                @if (isset($bookings) && count($bookings) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Booking ID</th>
                                                    <th>Restaurant</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bookings as $booking)
                                                    <tr>
                                                        <td>#{{ $booking->id }}</td>
                                                        <td>{{ $booking->restaurant->name ?? 'N/A' }}</td>
                                                        <td>{{ $booking->booking_date ? $booking->booking_date->format('M d, Y H:i') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($booking->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('bookings.show', $booking->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bx bx-calendar text-secondary mb-3" style="font-size: 3rem;"></i>
                                        <h6>No Bookings Found</h6>
                                        <p class="text-muted">This user hasn't made any restaurant bookings yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Activities Tab -->
                            <div class="tab-pane fade" id="activitiesTab" role="tabpanel">
                                @if (isset($activities) && count($activities) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Activity ID</th>
                                                    <th>Activity Name</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($activities as $activity)
                                                    <tr>
                                                        <td>#{{ $activity->id }}</td>
                                                        <td>{{ $activity->activity->name ?? 'N/A' }}</td>
                                                        <td>{{ $activity->booking_date ? $activity->booking_date->format('M d, Y H:i') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $activity->status == 'confirmed' ? 'success' : ($activity->status == 'pending' ? 'warning' : 'danger') }}">
                                                                {{ ucfirst($activity->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('activities.show', $activity->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bx bx-run text-secondary mb-3" style="font-size: 3rem;"></i>
                                        <h6>No Activities Found</h6>
                                        <p class="text-muted">This user hasn't booked any activities yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Reviews Tab -->
                            <div class="tab-pane fade" id="reviewsTab" role="tabpanel">
                                @if (isset($reviews) && count($reviews) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Review ID</th>
                                                    <th>Restaurant</th>
                                                    <th>Rating</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($reviews as $review)
                                                    <tr>
                                                        <td>#{{ $review->id }}</td>
                                                        <td>{{ $review->restaurant->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <div class="text-warning">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i
                                                                        class="bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                                                @endfor
                                                            </div>
                                                        </td>
                                                        <td>{{ $review->created_at ? $review->created_at->format('M d, Y') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('reviews.show', $review->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bx bx-star text-secondary mb-3" style="font-size: 3rem;"></i>
                                        <h6>No Reviews Found</h6>
                                        <p class="text-muted">This user hasn't submitted any reviews yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Wishlist Tab -->
                            <div class="tab-pane fade" id="wishlistTab" role="tabpanel">
                                @if (isset($wishlist) && count($wishlist) > 0)
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                        @foreach ($wishlist as $item)
                                            <div class="col">
                                                <div class="card h-100 border">
                                                    <div class="card-body">
                                                        <h5 class="card-title fw-semibold">
                                                            {{ $item->activity->name ?? 'N/A' }}</h5>
                                                        <p class="card-text text-muted small">
                                                            <i class="bx bx-calendar me-1"></i>Added on
                                                            {{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}
                                                        </p>
                                                        <a href="{{ route('activities.show', $item->activity_id) }}"
                                                            class="btn btn-sm btn-primary mt-2">
                                                            <i class="bx bx-run me-1"></i> View Activity
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bx bx-heart text-secondary mb-3" style="font-size: 3rem;"></i>
                                        <h6>No Wishlist Items Found</h6>
                                        <p class="text-muted">This user hasn't added any activities to their wishlist yet.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="bx bx-error-circle text-danger mb-3" style="font-size: 3rem;"></i>
                    <h4 class="text-danger mb-3">Confirm Delete</h4>
                    <p>Are you sure you want to delete the user <span id="user-name-display" class="fw-bold"></span>?</p>
                    <p class="text-muted small">This action cannot be undone and will remove all user data.</p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Cancel
                    </button>
                    <form id="deleteUserForm" method="POST" action="{{ route('users.destroy', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Copy referral code functionality
            document.getElementById('copyReferralCode').addEventListener('click', function() {
                var referralCode = document.getElementById('referralCode');
                referralCode.select();
                document.execCommand('copy');

                // Create and show temporary tooltip
                let tooltip = document.createElement('div');
                tooltip.textContent = 'Copied!';
                tooltip.style.position = 'absolute';
                tooltip.style.backgroundColor = '#28a745';
                tooltip.style.color = 'white';
                tooltip.style.padding = '5px 10px';
                tooltip.style.borderRadius = '3px';
                tooltip.style.fontSize = '12px';
                tooltip.style.zIndex = '1000';

                // Position near the button
                let button = this;
                let rect = button.getBoundingClientRect();
                tooltip.style.top = `${rect.top - 30}px`;
                tooltip.style.left = `${rect.left + (rect.width/2) - 30}px`;

                document.body.appendChild(tooltip);

                // Remove after 2 seconds
                setTimeout(() => {
                    tooltip.remove();
                }, 2000);
            });

            // Delete user modal
            const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteConfirmModal"]');
            const userNameDisplay = document.getElementById('user-name-display');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userName = this.getAttribute('data-user-name');
                    userNameDisplay.textContent = userName;
                });
            });

            // Timeline scrollbar styling
            const timelineContainer = document.querySelector('.timeline-container');
            if (timelineContainer) {
                timelineContainer.style.scrollbarWidth = 'thin';
                timelineContainer.style.scrollbarColor = '#dee2e6 #f8f9fa';
            }

            // Load more referred users functionality
            const loadMoreBtn = document.getElementById('loadMoreReferrals');
            if (loadMoreBtn) {
                let visibleItems = 4;
                const referredUserCards = document.querySelectorAll('.referred-user-card');
                const totalItems = referredUserCards.length;

                // Initial display - hide items beyond the initial count
                if (totalItems > visibleItems) {
                    for (let i = visibleItems; i < totalItems; i++) {
                        referredUserCards[i].closest('.col-md-6').style.display = 'none';
                    }
                }

                loadMoreBtn.addEventListener('click', function() {
                    // Show next batch of items
                    for (let i = visibleItems; i < visibleItems + 4 && i < totalItems; i++) {
                        referredUserCards[i].closest('.col-md-6').style.display = 'block';
                    }

                    visibleItems += 4;

                    // Hide button if all items are visible
                    if (visibleItems >= totalItems) {
                        loadMoreBtn.style.display = 'none';
                    }
                });
            }

            // Hover effect for referred user cards
            const referredUserCards = document.querySelectorAll('.referred-user-card');
            referredUserCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('shadow');
                    this.style.transform = 'translateY(-3px)';
                    this.style.transition = 'all 0.3s ease';
                });

                card.addEventListener('mouseleave', function() {
                    this.classList.remove('shadow');
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection
