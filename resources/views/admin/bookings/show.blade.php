@extends('admin.layouts.admin')

@section('title', 'View Booking')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold m-0">
                                    <i class="bx bx-calendar-check me-1 text-primary"></i> View Booking
                                </h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 mt-1">
                                        <li class="breadcrumb-item"><a href="#">Tourism</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Bookings</a>
                                        </li>
                                        <li class="breadcrumb-item active">View</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-2">
                                    <i class="bx bx-arrow-back me-1"></i> Back to List
                                </a>
                                <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-primary">
                                    <i class="bx bx-edit me-1"></i> Edit Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success shadow-sm border-0 alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0">Booking Details</h5>
                        <div
                            class="badge bg-label-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'cancelled' ? 'danger' : 'info')) }}">
                            <i
                                class="bx {{ $booking->status == 'confirmed' ? 'bx-check-circle' : ($booking->status == 'pending' ? 'bx-time' : ($booking->status == 'cancelled' ? 'bx-x-circle' : 'bx-info-circle')) }} me-1"></i>
                            {{ ucfirst($booking->status) }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Booking Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Booking Number:</strong></p>
                                            <p>{{ $booking->booking_number }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Booking Date:</strong></p>
                                            <p>
                                                <i class="bx bx-calendar me-1 text-muted"></i>
                                                {{ $booking->created_at->format('M d, Y h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Status:</strong></p>
                                            <p>
                                                @php
                                                    $statusClass =
                                                        [
                                                            'pending' => 'bg-label-warning',
                                                            'confirmed' => 'bg-label-success',
                                                            'cancelled' => 'bg-label-danger',
                                                            'completed' => 'bg-label-info',
                                                        ][$booking->status] ?? 'bg-label-secondary';

                                                    $statusIcon =
                                                        [
                                                            'completed' => 'bx-check-circle',
                                                            'confirmed' => 'bx-check-circle',
                                                            'pending' => 'bx-time',
                                                            'cancelled' => 'bx-x-circle',
                                                        ][$booking->status] ?? 'bx-question-mark';
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    <i class="bx {{ $statusIcon }} me-1"></i>
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Payment Status:</strong></p>
                                            <p>
                                                @php
                                                    $paymentClass =
                                                        [
                                                            'pending' => 'bg-label-warning',
                                                            'paid' => 'bg-label-success',
                                                            'refunded' => 'bg-label-info',
                                                            'failed' => 'bg-label-danger',
                                                        ][$booking->payment_status] ?? 'bg-label-secondary';
                                                @endphp
                                                <span
                                                    class="badge {{ $paymentClass }}">{{ ucfirst($booking->payment_status) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Customer Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Name:</strong></p>
                                            <p>{{ $booking->user->first_name ?? '' }}
                                                {{ $booking->user->last_name ?? 'Guest' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Email:</strong></p>
                                            <p>{{ $booking->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Phone:</strong></p>
                                            <p>{{ $booking->user->phone ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Customer ID:</strong></p>
                                            <p>{{ $booking->user_id ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-semibold">Financial Details</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Total Tickets:</strong></p>
                                        <p>
                                            <span
                                                class="badge bg-label-secondary rounded-pill">{{ $booking->ticket_count }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Total Price:</strong></p>
                                        <p>${{ number_format($booking->total_price, 2) }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Discount Amount:</strong></p>
                                        <p>${{ number_format($booking->discount_amount, 2) }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Loyalty Points:</strong></p>
                                        <p>{{ $booking->loyalty_points_earned }} earned /
                                            {{ $booking->loyalty_points_used }} used</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider my-4">
                            <div class="divider-text">Booking Items</div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Activity</th>
                                                <th class="text-center">Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Subtotal</th>
                                                <th>Date/Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($booking->activities as $activity)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2" style="width: 40px; height: 40px;">
                                                                @if ($activity && $activity->image)
                                                                    <img src="{{ asset('storage/' . $activity->image) }}"
                                                                        alt="{{ $activity->name ?? 'Activity' }}"
                                                                        class="rounded-circle"
                                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                                                        style="width: 40px; height: 40px;">
                                                                        <i class="bx bx-map-pin text-primary"
                                                                            style="font-size: 20px;"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="overflow-hidden">
                                                                <div class="fw-semibold text-truncate">
                                                                    {{ $activity->name ?? 'Unknown Activity' }}
                                                                </div>
                                                                <div class="text-muted small text-truncate">
                                                                    @if ($activity && $activity->location)
                                                                        {{ $activity->location }}
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-label-secondary rounded-pill">1</span>
                                                    </td>
                                                    <td>${{ number_format($activity->price ?? 0, 2) }}</td>
                                                    <td>${{ number_format($activity->price ?? 0, 2) }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-calendar me-1 text-muted"></i>
                                                            <span>{{ date('M d, Y', strtotime($activity->start_date ?? now())) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-label-secondary">
                                                            <i class="bx bx-check-circle me-1"></i>
                                                            Confirmed
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="bx bx-calendar-x text-secondary mb-2"
                                                                style="font-size: 3rem;"></i>
                                                            <h6 class="mb-1">No Items Found</h6>
                                                            <p class="text-muted small">No items found for this booking</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                                <td colspan="3">
                                                    ${{ number_format($booking->total_price, 2) }}</td>
                                            </tr>
                                            @if ($booking->discount_amount > 0)
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                                    <td colspan="3" class="text-success">
                                                        -${{ number_format($booking->discount_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                            @if ($booking->loyalty_points_used > 0)
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Loyalty Points
                                                            Used:</strong></td>
                                                    <td colspan="3">{{ $booking->loyalty_points_used }} points</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                                <td colspan="3">
                                                    <strong>${{ number_format($booking->total_price, 2) }}</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="divider my-4">
                            <div class="divider-text">Booking Participants</div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Participant Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Added On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($booking->participants as $participant)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm me-2 bg-label-primary">
                                                                <span class="avatar-initial rounded-circle">
                                                                    {{ substr($participant->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div>{{ $participant->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $participant->email ?? 'N/A' }}</td>
                                                    <td>{{ $participant->phone ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-calendar me-1 text-muted"></i>
                                                            <span>{{ $participant->created_at->format('M d, Y') }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="bx bx-user-x text-secondary mb-2"
                                                                style="font-size: 3rem;"></i>
                                                            <h6 class="mb-1">No Participants Found</h6>
                                                            <p class="text-muted small">No participant records for this
                                                                booking</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if ($booking->status != 'cancelled' && $booking->payment_status != 'refunded')
                                    <button type="button" class="btn btn-warning cancel-btn" data-bs-toggle="modal"
                                        data-bs-target="#cancelConfirmModal">
                                        <i class="bx bx-x-circle me-1"></i> Cancel Booking
                                    </button>
                                @endif
                                <button type="button" class="btn btn-danger delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal">
                                    <i class="bx bx-trash me-1"></i> Delete Booking
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Confirmation Modal -->
        <div class="modal fade" id="cancelConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 bg-warning bg-opacity-10">
                        <h5 class="modal-title text-warning">
                            <i class="bx bx-error-circle me-1"></i>
                            Confirm Cancellation
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="text-center mb-3">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 mb-3">
                                <i class="bx bx-x-circle text-warning" style="font-size: 1.5rem"></i>
                            </div>
                            <h5>Cancel Booking</h5>
                            <p>Are you sure you want to cancel booking <strong
                                    class="text-warning">{{ $booking->booking_number }}</strong>?</p>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <i class="bx bx-info-circle me-1"></i>
                            This will update the booking status to cancelled. If payment was made, you
                            may need to process a refund separately.
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> No, Keep Booking
                        </button>
                        <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-check me-1"></i> Yes, Cancel Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 bg-danger bg-opacity-10">
                        <h5 class="modal-title text-danger">
                            <i class="bx bx-error-circle me-1"></i>
                            Confirm Delete
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="text-center mb-3">
                            <div class="avatar avatar-lg bg-danger bg-opacity-10 mb-3">
                                <i class="bx bx-trash text-danger" style="font-size: 1.5rem"></i>
                            </div>
                            <h5>Delete Booking</h5>
                            <p>Are you sure you want to delete booking <strong
                                    class="text-danger">{{ $booking->booking_number }}</strong>?</p>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <i class="bx bx-info-circle me-1"></i>
                            This will permanently remove the booking record from the system. This
                            action cannot be undone.
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Cancel
                        </button>
                        <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}">
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
    </div>
@endsection
