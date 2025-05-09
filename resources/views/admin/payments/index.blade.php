@extends('admin.layouts.admin')

@section('title', 'Payments Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Payments /</span> Management
        </h4>

        <!-- Payment Overview Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Revenue</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($totalRevenue, 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-dollar bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Monthly Revenue</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($monthlyRevenue, 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="bx bx-calendar bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Pending Amount</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($pendingAmount, 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="bx bx-time bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Refunded Amount</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">${{ number_format($refundedAmount, 2) }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="bx bx-undo bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Search & Filters</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search"
                            placeholder="Transaction ID or Booking Number" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <h5 class="card-header">Payments List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Booking</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->id }}</strong></td>
                                <td>
                                    @if ($payment->booking)
                                        <a href="{{ route('bookings.show', $payment->booking->id) }}">
                                            {{ $payment->booking->booking_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($payment->booking && $payment->booking->user)
                                        <a href="{{ route('users.show', $payment->booking->user->id) }}">
                                            {{ $payment->booking->user->first_name }}
                                            {{ $payment->booking->user->last_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>
                                    <span
                                        class="badge bg-label-{{ $payment->status == 'completed'
                                            ? 'success'
                                            : ($payment->status == 'pending'
                                                ? 'warning'
                                                : ($payment->status == 'refunded'
                                                    ? 'info'
                                                    : 'danger')) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('payments.show', $payment->id) }}">
                                                <i class="bx bx-show-alt me-1"></i> View Details
                                            </a>
                                            @if ($payment->status != 'completed')
                                                <a class="dropdown-item text-success" href="javascript:void(0);"
                                                    onclick="document.getElementById('complete-payment-form-{{ $payment->id }}').submit();">
                                                    <i class="bx bx-check me-1"></i> Mark as Completed
                                                </a>
                                                <form id="complete-payment-form-{{ $payment->id }}"
                                                    action="{{ route('payments.update-status', $payment->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="completed">
                                                </form>
                                            @endif
                                            @if ($payment->status != 'refunded')
                                                <a class="dropdown-item text-info" href="javascript:void(0);"
                                                    onclick="document.getElementById('refund-payment-form-{{ $payment->id }}').submit();">
                                                    <i class="bx bx-undo me-1"></i> Mark as Refunded
                                                </a>
                                                <form id="refund-payment-form-{{ $payment->id }}"
                                                    action="{{ route('payments.update-status', $payment->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="refunded">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of
                        {{ $payments->total() }} payments
                    </div>
                    <div>
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
