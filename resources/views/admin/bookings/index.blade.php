@extends('admin.layouts.admin')

@section('title', 'Bookings')

@section('content')
    <style>
        /* Replace all your existing filter styles with these */
        .filter-container {
            margin-bottom: 1.5rem;
        }

        .filter-tabs {
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-tab {
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: #6c757d;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .filter-tab:hover {
            color: #696cff;
        }

        .filter-tab.active {
            color: #696cff;
            border-bottom-color: #696cff;
        }

        .filter-tab .counter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 18px;
            min-width: 18px;
            padding: 0 5px;
            font-size: 11px;
            font-weight: 600;
            background-color: #e9ecef;
            color: #6c757d;
            border-radius: 10px;
            margin-left: 5px;
        }

        .filter-tab.active .counter {
            background-color: #696cff;
            color: white;
        }

        .search-container {
            position: relative;
            max-width: 400px;
            margin-bottom: 1rem;
        }

        .search-container .form-control {
            padding-left: 40px;
            height: 42px;
            border-radius: 21px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .search-container .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 16px;
            font-size: 0.8125rem;
            font-weight: 500;
            background-color: #f8f9fa;
            color: #495057;
            border: 1px solid #e9ecef;
            transition: all 0.2s;
            cursor: pointer;
        }

        .filter-chip:hover {
            background-color: #e9ecef;
        }

        .filter-chip i {
            font-size: 1rem;
            margin-right: 5px;
        }

        .filter-chip.active {
            background-color: #e7f5ff;
            color: #0d6efd;
            border-color: #b6daff;
        }

        .filter-chip.active:hover {
            background-color: #d0e8ff;
        }

        .filter-drawer {
            background-color: #fff;
            border-radius: 0.375rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease-in-out;
        }

        .filter-drawer.open {
            max-height: 500px;
        }

        .filter-drawer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .filter-drawer-body {
            padding: 1rem;
        }

        .filter-drawer-footer {
            padding: 1rem;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .tag-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 4px;
            background: #f1f5f9;
            color: #475569;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .filter-tag i {
            margin-left: 5px;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .filter-tag i:hover {
            color: #dc3545;
        }

        .dropdown-select {
            position: relative;
        }

        .dropdown-select .select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.375rem 0.75rem;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            cursor: pointer;
            min-height: 38px;
        }

        .dropdown-menu {
            border-color: rgba(0, 0, 0, 0.08);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold m-0">
                                    <i class="bx bx-calendar-check me-1 text-primary"></i> Bookings
                                </h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 mt-1">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active">Bookings</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i> Create Booking
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

        <!-- Filters Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <form action="{{ route('bookings.index') }}" method="GET" id="bookingFilterForm">
                    <div class="row g-3 align-items-center">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bx bx-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Search booking, user, or activity" value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="col-md-3">
                            <select name="status" class="form-select" data-placeholder="Filter by status">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="date" name="date_from" class="form-control" placeholder="From date"
                                    value="{{ request('date_from') }}">
                                <span class="input-group-text bg-transparent px-1">-</span>
                                <input type="date" name="date_to" class="form-control" placeholder="To date"
                                    value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="col-md-2 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-filter-alt"></i> Apply
                            </button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-reset"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (request()->hasAny([
                'search',
                'status',
                'date_from',
                'date_to',
                'min_tickets',
                'max_tickets',
                'user_id',
                'activity_id',
            ]))
            <div class="filter-badges mb-3">
                <span class="text-muted small me-2">Active filters:</span>

                @if (request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="filter-badge">
                        <span>Search: {{ request('search') }}</span> <i class="bx bx-x"></i>
                    </a>
                @endif

                @if (request()->has('status') && is_array(request('status')))
                    @foreach (request('status') as $status)
                        <a href="{{ request()->fullUrlWithQuery(['status' => array_diff(request('status'), [$status])]) }}"
                            class="filter-badge"
                            style="background-color: rgba({{ ['completed' => '40, 167, 69', 'confirmed' => '0, 123, 255', 'pending' => '255, 193, 7', 'cancelled' => '220, 53, 69'][$status] ?? '108, 117, 125' }}, 0.1); color: rgb({{ ['completed' => '40, 167, 69', 'confirmed' => '0, 123, 255', 'pending' => '255, 193, 7', 'cancelled' => '220, 53, 69'][$status] ?? '108, 117, 125' }});">
                            <span>Status: {{ ucfirst($status) }}</span> <i class="bx bx-x"></i>
                        </a>
                    @endforeach
                @endif

                @if (request('date_from') || request('date_to'))
                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}"
                        class="filter-badge">
                        <span>Date: {{ request('date_from', 'Any') }} to {{ request('date_to', 'Any') }}</span> <i
                            class="bx bx-x"></i>
                    </a>
                @endif

                @if (request('min_tickets') || request('max_tickets'))
                    <a href="{{ request()->fullUrlWithQuery(['min_tickets' => null, 'max_tickets' => null]) }}"
                        class="filter-badge">
                        <span>Tickets: {{ request('min_tickets', 'Any') }} - {{ request('max_tickets', 'Any') }}</span> <i
                            class="bx bx-x"></i>
                    </a>
                @endif

                @if (request('user_id'))
                    @php
                        $filteredUser = $users->firstWhere('id', request('user_id'));
                        $userName = $filteredUser
                            ? $filteredUser->first_name . ' ' . $filteredUser->last_name
                            : 'User #' . request('user_id');
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['user_id' => null]) }}" class="filter-badge">
                        <span>User: {{ $userName }}</span> <i class="bx bx-x"></i>
                    </a>
                @endif

                @if (request('activity_id'))
                    @php
                        $filteredActivity = $activities->firstWhere('id', request('activity_id'));
                        $activityName = $filteredActivity
                            ? $filteredActivity->name
                            : 'Activity #' . request('activity_id');
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['activity_id' => null]) }}" class="filter-badge">
                        <span>Activity: {{ $activityName }}</span> <i class="bx bx-x"></i>
                    </a>
                @endif

                <a href="{{ route('bookings.index') }}" class="filter-badge ms-auto"
                    style="background-color: rgba(220, 53, 69, 0.1); color: rgb(220, 53, 69);">
                    <span>Clear all</span> <i class="bx bx-x"></i>
                </a>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0">All Bookings</h5>
                <div class="text-muted small">
                    Showing {{ $bookings->count() }} of {{ $bookings->total() }} bookings
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th style="min-width: 140px;">Booking #</th>
                            <th style="min-width: 200px;">Activity</th>
                            <th style="min-width: 180px;">User</th>
                            <th class="text-center" style="width: 80px;">Seats</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 130px;">Booked On</th>
                            <th class="text-center" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="text-center fw-semibold">{{ $bookings->firstItem() + $loop->index }}</td>
                                <td><span class="badge bg-label-primary">{{ $booking->booking_number }}</span></td>
                                <td>
                                    @forelse($booking->activities as $activity)
                                        <div
                                            class="activity-card mb-1 p-2 border-start border-3 border-primary rounded-end bg-light">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2" style="width: 40px; height: 40px;">
                                                    @if ($activity && $activity->image)
                                                        <img class="rounded-circle"
                                                            src="{{ asset('storage/' . $activity->image) }}"
                                                            alt="{{ $activity->name }}"
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
                                                        {{ $activity ? $activity->name : 'Unknown Activity' }}
                                                    </div>
                                                    <div class="text-muted small text-truncate">
                                                        {{ $activity ? $activity->location : 'Unknown Location' }}
                                                    </div>
                                                    <div class="mt-1">
                                                        <span
                                                            class="badge {{ $activity->remaining_capacity > 5 ? 'bg-success' : ($activity->remaining_capacity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                            <i
                                                                class="bx {{ $activity->remaining_capacity > 0 ? 'bx-user-check' : 'bx-user-x' }} me-1"></i>
                                                            {{ $activity->remaining_capacity }} of
                                                            {{ $activity->capacity }} seats available
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!$loop->last)
                                            <div class="my-1 ms-3 border-start border-2 border-dashed"
                                                style="height: 10px;"></div>
                                        @endif
                                    @empty
                                        <span class="text-muted fst-italic">No activities</span>
                                    @endforelse
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-semibold">{{ $booking->user->first_name ?? '' }}
                                                {{ $booking->user->last_name ?? 'Guest' }}</div>
                                            <div class="text-muted small">{{ $booking->user->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span
                                        class="badge bg-label-secondary rounded-pill">{{ $booking->ticket_count }}</span>
                                </td>

                                <td>
                                    @php
                                        $statusClass =
                                            [
                                                'completed' => 'success',
                                                'confirmed' => 'info',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                            ][$booking->status] ?? 'secondary';

                                        $statusIcon =
                                            [
                                                'completed' => 'bx-check-circle',
                                                'confirmed' => 'bx-check-circle',
                                                'pending' => 'bx-time',
                                                'cancelled' => 'bx-x-circle',
                                            ][$booking->status] ?? 'bx-question-mark';
                                    @endphp

                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn btn-sm bg-label-{{ $statusClass }} dropdown-toggle border-0"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx {{ $statusIcon }} me-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                                @if ($status != $booking->status)
                                                    <li>
                                                        <form action="{{ route('bookings.update', $booking->id) }}"
                                                            method="POST" class="status-update-form">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status"
                                                                value="{{ $status }}">
                                                            <input type="hidden" name="quick_update" value="1">
                                                            <button type="submit" class="dropdown-item">
                                                                <i
                                                                    class="bx {{ [
                                                                        'completed' => 'bx-check-circle',
                                                                        'confirmed' => 'bx-check-circle',
                                                                        'pending' => 'bx-time',
                                                                        'cancelled' => 'bx-x-circle',
                                                                    ][$status] }} me-1"></i>
                                                                Mark as {{ ucfirst($status) }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-calendar me-1 text-muted"></i>
                                        <span>{{ $booking->created_at->format('M d, Y') }}</span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('bookings.edit', $booking->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('bookings.show', $booking->id) }}">
                                                <i class="bx bx-show me-1"></i> View Details
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger delete-btn" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                data-route="{{ route('bookings.destroy', $booking->id) }}"
                                                data-user-name="{{ $booking->user->first_name ?? '' }} {{ $booking->user->last_name ?? 'Guest' }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-calendar-x text-secondary mb-2" style="font-size: 3rem;"></i>
                                        <h6 class="mb-1">No Bookings Found</h6>
                                        <p class="text-muted small">No booking records match your criteria</p>
                                        <a href="{{ route('bookings.index') }}"
                                            class="btn btn-sm btn-outline-primary mt-3">
                                            <i class="bx bx-reset me-1"></i> Clear Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing <span class="fw-semibold">{{ $bookings->firstItem() ?? 0 }}</span> to
                        <span class="fw-semibold">{{ $bookings->lastItem() ?? 0 }}</span> of
                        <span class="fw-semibold">{{ $bookings->total() }}</span> bookings
                    </div>
                    <div>
                        {{ $bookings->withQueryString()->links() }}
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
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            <i class="bx bx-error-circle text-danger" style="font-size: 6rem;"></i>
                        </div>
                        <h4>Are you sure?</h4>
                        <p>Do you really want to delete this booking for user <span id="user-name"
                                class="fw-bold"></span>?</p>
                        <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteBookingForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2 for dropdowns
                if (typeof $.fn.select2 !== 'undefined') {
                    $('.select2').select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: true,
                        placeholder: function() {
                            return $(this).data('placeholder');
                        }
                    });

                    // Auto-submit form when Select2 changes
                    $('.select2').on('change', function() {
                        this.form.submit();
                    });
                }

                // Auto-submit date range inputs
                const dateFrom = document.querySelector('input[name="date_from"]');
                const dateTo = document.querySelector('input[name="date_to"]');

                if (dateFrom && dateTo) {
                    dateFrom.addEventListener('change', function() {
                        if (dateTo.value) {
                            this.form.submit();
                        }
                    });

                    dateTo.addEventListener('change', function() {
                        if (dateFrom.value) {
                            this.form.submit();
                        }
                    });
                }

                // Search input debounce
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    let searchTimeout;
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            if (this.value.trim().length >= 2 || this.value.trim().length === 0) {
                                this.form.submit();
                            }
                        }, 500);
                    });
                }
            });
        </script>
    @endpush
@endsection
