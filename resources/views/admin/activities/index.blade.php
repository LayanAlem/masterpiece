@extends('admin.layouts.admin')

@section('title', 'Activities')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tourism /</span> Activities
        </h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('activities.index') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Name or location" value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach ($categoryTypes as $categoryType)
                                    <option value="{{ $categoryType->id }}"
                                        {{ request('category') == $categoryType->id ? 'selected' : '' }}>
                                        {{ $categoryType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="date_range" class="form-label">Date Range</label>
                            <select class="form-select" id="date_range" name="date_range">
                                <option value="">All Dates</option>
                                <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Past</option>
                                <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>
                                    Upcoming</option>
                                <option value="ongoing" {{ request('date_range') == 'ongoing' ? 'selected' : '' }}>Ongoing
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="price_min" class="form-label">Min Price</label>
                            <input type="number" class="form-control" id="price_min" name="price_min" min="0"
                                step="0.01" value="{{ request('price_min') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="price_max" class="form-label">Max Price</label>
                            <input type="number" class="form-control" id="price_max" name="price_max" min="0"
                                step="0.01" value="{{ request('price_max') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="sort_by" class="form-label">Sort By</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date
                                    Added</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                                <option value="start_date" {{ request('sort_by') == 'start_date' ? 'selected' : '' }}>Start
                                    Date</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort_direction" class="form-label">Order</label>
                            <select class="form-select" id="sort_direction" name="sort_direction">
                                <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending
                                </option>
                                <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>
                                    Descending</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" id="reset-filter" class="btn btn-outline-secondary w-100">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">All Activities</h5>
                    <span
                        class="badge bg-label-primary ms-2">{{ $activities instanceof \Illuminate\Pagination\LengthAwarePaginator ? $activities->total() : count($activities) }}</span>
                </div>
                <div>
                    <a href="{{ route('activities.trashed') }}" class="btn btn-outline-secondary me-2">
                        <i class="bx bx-trash me-1"></i> Trash
                    </a>
                    <a href="{{ route('activities.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Add New Activity
                    </a>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Date Range</th>
                            <th>Location</th>
                            <th>Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($activities as $activity)
                            <tr>
                                <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}</td>
                                <td>
                                    @if ($activity->image)
                                        <img src="{{ asset('storage/' . $activity->image) }}"
                                            alt="{{ $activity->name }}"
                                            style="width: 80px; height: 60px; object-fit: cover;"
                                            class="rounded shadow-sm border">
                                    @else
                                        <div class="avatar bg-light-primary" style="width: 40px; height: 40px;">
                                            <span
                                                class="avatar-initial rounded">{{ substr($activity->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $activity->name }}</strong>
                                </td>
                                <td>
                                    {{ $activity->categoryType->name ?? 'N/A' }}
                                    <small
                                        class="d-block text-muted">{{ $activity->categoryType->mainCategory->name ?? '' }}</small>
                                </td>
                                <td>${{ number_format($activity->price, 2) }}</td>
                                <td>
                                    <small>
                                        {{ date('M d, Y', strtotime($activity->start_date)) }} -<br>
                                        {{ date('M d, Y', strtotime($activity->end_date)) }}
                                    </small>
                                </td>
                                <td>{{ Str::limit($activity->location, 20) }}</td>
                                <td>
                                    <span class="badge bg-label-secondary">
                                        @if (method_exists($activity, 'bookings'))
                                            {{ $activity->bookings_count ?? 0 }}
                                        @else
                                            0
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('activities.show', $activity->id) }}">
                                                <i class="bx bx-show-alt me-1"></i> View
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('activities.edit', $activity->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <button class="dropdown-item delete-btn" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-activity-id="{{ $activity->id }}"
                                                data-activity-name="{{ $activity->name }}"
                                                data-route="{{ route('activities.destroy', $activity->id) }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class='bx bx-folder-open text-secondary mb-2' style="font-size: 2rem"></i>
                                        <h6 class="mb-1 text-secondary">No activities found</h6>
                                        <p class="mb-0 text-muted">Try adjusting your search or filter to find what you're
                                            looking for</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination - Add this section -->
            @if ($activities->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing
                            {{ $activities instanceof \Illuminate\Pagination\LengthAwarePaginator ? $activities->firstItem() : 0 }}
                            to
                            {{ $activities instanceof \Illuminate\Pagination\LengthAwarePaginator ? $activities->lastItem() : count($activities) }}
                            of
                            {{ $activities instanceof \Illuminate\Pagination\LengthAwarePaginator ? $activities->total() : count($activities) }}
                            entries
                        </div>
                        <div>
                            {{ $activities->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the activity
                            <span id="activity-name" class="fw-bold text-danger"></span>?
                        </p>
                        <p class="text-warning">This will move the activity to trash. You can restore it later.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deleteActivityForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button clicks
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const activityNameSpan = document.getElementById('activity-name');
            const deleteForm = document.getElementById('deleteActivityForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const activityName = this.getAttribute('data-activity-name');

                    deleteForm.action = route;
                    activityNameSpan.textContent = activityName;
                });
            });

            // Handle filter reset
            document.getElementById('reset-filter').addEventListener('click', function() {
                document.getElementById('search').value = '';
                document.getElementById('category').value = '';
                document.getElementById('date_range').value = '';
                document.getElementById('price_min').value = '';
                document.getElementById('price_max').value = '';
                // Reset the sort fields if they exist
                if (document.getElementById('sort_by')) document.getElementById('sort_by').value =
                    'created_at';
                if (document.getElementById('sort_direction')) document.getElementById('sort_direction')
                    .value = 'desc';

                document.getElementById('filterForm').submit();
            });

            // Automatically submit form when select fields change
            const autoSubmitFields = ['category', 'date_range', 'sort_by', 'sort_direction'];
            autoSubmitFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                }
            });
        });
    </script>
@endsection
