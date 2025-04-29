@extends('admin.layouts.admin')

@section('title', 'Reviews')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Activities /</span> Reviews</h4>
        <div class="card">
            <div class="card">

                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">User Reviews</h5>
                    <div>
                        <form method="GET" action="{{ route('reviews.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                            <select name="status" class="form-select me-2">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Booking No.</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($reviews as $review)
                            <tr>
                                <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                                <td>{{ $review->user->first_name ?? '' }} {{ $review->user->last_name ?? 'N/A' }}</td>
                                <td>{{ $review->activity->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-label-info">#{{ $review->booking ? $review->booking->booking_number : 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class='bx bxs-star text-warning'></i>
                                            @else
                                                <i class='bx bx-star text-muted'></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1">({{ $review->rating }})</span>
                                    </div>
                                </td>
                                <td>{{ Str::limit($review->comment, 30) }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn btn-sm dropdown-toggle {{ $review->status === 'approved' ? 'btn-success' : ($review->status === 'rejected' ? 'btn-danger' : 'btn-warning') }}"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ ucfirst($review->status) }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:void(0);"
                                                    onclick="updateStatus({{ $review->id }}, 'pending')"
                                                    class="dropdown-item d-flex align-items-center {{ $review->status === 'pending' ? 'active' : '' }}">
                                                    <i class="bx bx-time-five me-2 text-warning"></i>Pending
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    onclick="updateStatus({{ $review->id }}, 'approved')"
                                                    class="dropdown-item d-flex align-items-center {{ $review->status === 'approved' ? 'active' : '' }}">
                                                    <i class="bx bx-check me-2 text-success"></i>Approve
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    onclick="updateStatus({{ $review->id }}, 'rejected')"
                                                    class="dropdown-item d-flex align-items-center {{ $review->status === 'rejected' ? 'active' : '' }}">
                                                    <i class="bx bx-x me-2 text-danger"></i>Reject
                                                </a>
                                            </li>
                                        </ul>
                                        <form action="{{ route('reviews.update', $review->id) }}" method="POST"
                                            id="statusForm-{{ $review->id }}" style="display: none;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" id="status-value-{{ $review->id }}">
                                        </form>
                                    </div>
                                </td>
                                <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="dropdown ms-3">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('reviews.show', $review->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>
                                            <button class="dropdown-item delete-btn" type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal" data-review-id="{{ $review->id }}"
                                                data-activity-name="{{ $review->activity->name ?? 'this review' }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($reviews->isEmpty())
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">No reviews found</div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $reviews->links() }}
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
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <p>Are you sure you want to delete the review for <span id="activity-name-display"></span>?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteReviewForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const activityNameDisplay = document.getElementById('activity-name-display');
            const deleteForm = document.getElementById('deleteReviewForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const reviewId = this.getAttribute('data-review-id');
                    const activityName = this.getAttribute('data-activity-name');

                    // Display the activity name in the modal
                    activityNameDisplay.textContent = activityName;

                    // Set the form action to the correct route
                    deleteForm.action = "{{ route('reviews.destroy', '') }}/" + reviewId;
                });
            });
        });

        function updateStatus(reviewId, status) {
            // Set the hidden input value
            document.getElementById('status-value-' + reviewId).value = status;

            // Submit the form
            document.getElementById('statusForm-' + reviewId).submit();
        }
    </script>
@endsection
