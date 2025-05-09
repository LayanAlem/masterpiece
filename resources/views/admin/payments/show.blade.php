@extends('admin.layouts.admin')

@section('title', 'Payment Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Payments /</span> Payment Details
        </h4>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Payment Information -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Payment Information</h5>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Payment ID</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">{{ $payment->id }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Transaction ID</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">{{ $payment->transaction_id }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Amount</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">
                                    <span class="fw-bold">${{ number_format($payment->amount, 2) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Payment Method</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">{{ ucfirst($payment->payment_method) }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Status</label>
                            <div class="col-md-8">
                                <span
                                    class="badge bg-label-{{ $payment->status == 'completed'
                                        ? 'success'
                                        : ($payment->status == 'pending'
                                            ? 'warning'
                                            : ($payment->status == 'refunded'
                                                ? 'info'
                                                : 'danger')) }} py-2 px-3">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Currency</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">{{ strtoupper($payment->currency ?? 'USD') }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Created At</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">{{ $payment->created_at->format('M d, Y H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="mb-0 row">
                            <label class="col-md-4 col-form-label">Updated At</label>
                            <div class="col-md-8">
                                <p class="form-control-static mb-0 py-2">{{ $payment->updated_at->format('M d, Y H:i:s') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Booking and Actions -->
            <div class="col-md-6">
                @if ($payment->booking)
                    <div class="card mb-4">
                        <h5 class="card-header">Booking Information</h5>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">Booking Number</label>
                                <div class="col-md-8">
                                    <p class="form-control-static mb-0 py-2">
                                        <a href="{{ route('bookings.show', $payment->booking->id) }}">
                                            {{ $payment->booking->booking_number }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">User</label>
                                <div class="col-md-8">
                                    <p class="form-control-static mb-0 py-2">
                                        @if ($payment->booking->user)
                                            <a href="{{ route('users.show', $payment->booking->user->id) }}">
                                                {{ $payment->booking->user->first_name }}
                                                {{ $payment->booking->user->last_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">Booking Status</label>
                                <div class="col-md-8">
                                    <span
                                        class="badge bg-label-{{ $payment->booking->status == 'completed'
                                            ? 'success'
                                            : ($payment->booking->status == 'pending'
                                                ? 'warning'
                                                : ($payment->booking->status == 'cancelled'
                                                    ? 'danger'
                                                    : 'info')) }} py-2 px-3">
                                        {{ ucfirst($payment->booking->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">Payment Status</label>
                                <div class="col-md-8">
                                    <span
                                        class="badge bg-label-{{ $payment->booking->payment_status == 'paid'
                                            ? 'success'
                                            : ($payment->booking->payment_status == 'pending'
                                                ? 'warning'
                                                : ($payment->booking->payment_status == 'refunded'
                                                    ? 'info'
                                                    : 'danger')) }} py-2 px-3">
                                        {{ ucfirst($payment->booking->payment_status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label">Booking Date</label>
                                <div class="col-md-8">
                                    <p class="form-control-static mb-0 py-2">
                                        {{ $payment->booking->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="mb-0 row">
                                <label class="col-md-4 col-form-label">Total Price</label>
                                <div class="col-md-8">
                                    <p class="form-control-static mb-0 py-2">
                                        <span
                                            class="fw-bold">${{ number_format($payment->booking->total_price, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="card mb-4">
                    <h5 class="card-header">Actions</h5>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('payments.index') }}" class="btn btn-primary">
                                <i class="bx bx-arrow-back me-1"></i> Back to List
                            </a>

                            @if ($payment->booking)
                                <a href="{{ route('bookings.show', $payment->booking->id) }}" class="btn btn-info">
                                    <i class="bx bx-detail me-1"></i> View Booking
                                </a>
                            @endif

                            @if ($payment->status != 'completed')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#completePaymentModal">
                                    <i class="bx bx-check me-1"></i> Mark as Completed
                                </button>
                            @endif

                            @if ($payment->status != 'refunded')
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#refundPaymentModal">
                                    <i class="bx bx-undo me-1"></i> Mark as Refunded
                                </button>
                            @endif

                            @if ($payment->status != 'failed')
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#failPaymentModal">
                                    <i class="bx bx-x me-1"></i> Mark as Failed
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Information -->
        @if ($payment->booking && $payment->booking->activities->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Activities</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Location</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($payment->booking->activities as $activity)
                                        <tr>
                                            <td>{{ $activity->id }}</td>
                                            <td>
                                                <a href="{{ route('activities.show', $activity->id) }}">
                                                    {{ $activity->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($activity->categoryType)
                                                    {{ $activity->categoryType->name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($activity->price, 2) }}</td>
                                            <td>{{ $activity->location }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($activity->start_date)->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Complete Payment Modal -->
    <div class="modal fade" id="completePaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.update-status', $payment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <div class="modal-header">
                        <h5 class="modal-title">Mark Payment as Completed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark this payment as completed? This will also update the booking
                            payment status.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Refund Payment Modal -->
    <div class="modal fade" id="refundPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.update-status', $payment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="refunded">
                    <div class="modal-header">
                        <h5 class="modal-title">Mark Payment as Refunded</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark this payment as refunded? This will also update the booking payment
                            status.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Fail Payment Modal -->
    <div class="modal fade" id="failPaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.update-status', $payment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="failed">
                    <div class="modal-header">
                        <h5 class="modal-title">Mark Payment as Failed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark this payment as failed? This will also update the booking payment
                            status.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
