@extends('admin.layouts.admin')

@section('title', 'Trashed Reviews')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Reviews /</span> Trash
        </h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Trashed Reviews</h5>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Reviews
                </a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($reviews as $review)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $review->user->first_name ?? '' }} {{ $review->user->last_name ?? 'N/A' }}</td>
                                <td>{{ $review->activity->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class='bx bxs-star text-warning'></i>
                                            @else
                                                <i class='bx bx-star text-muted'></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td>{{ Str::limit($review->comment, 30) }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'approved' => 'bg-label-success',
                                            'pending' => 'bg-label-warning',
                                            'rejected' => 'bg-label-danger',
                                        ][$review->status] ?? 'bg-label-secondary';

                                        $statusIcon = [
                                            'approved' => 'bx-check-circle',
                                            'pending' => 'bx-time',
                                            'rejected' => 'bx-x-circle',
                                        ][$review->status] ?? 'bx-question-mark';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        <i class='bx {{ $statusIcon }} me-1'></i>
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td>{{ $review->deleted_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <form action="{{ route('reviews.restore', $review->id) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Restore">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-outline-danger force-delete-btn"
                                                data-bs-toggle="modal" data-bs-target="#forceDeleteModal"
                                                data-review-id="{{ $review->id }}"
                                                data-activity-name="{{ $review->activity->name ?? 'this review' }}">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">No trashed reviews found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Force Delete Confirmation Modal -->
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-opacity-10">
                    <h5 class="modal-title text-danger">Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-lg bg-danger bg-opacity-10 mb-3">
                            <i class="bx bx-trash-alt text-danger fs-3"></i>
                        </div>
                        <h5>Permanently Delete Review</h5>
                        <p>Are you sure you want to permanently delete the review for <strong id="activity-name-display"></strong>?</p>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bx bx-error-circle me-1"></i>
                        Warning: This action cannot be undone. The review will be permanently deleted from the database.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="forceDeleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Permanently Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Force delete modal functionality
        const deleteButtons = document.querySelectorAll('.force-delete-btn');
        const activityNameDisplay = document.getElementById('activity-name-display');
        const deleteForm = document.getElementById('forceDeleteForm');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reviewId = this.getAttribute('data-review-id');
                const activityName = this.getAttribute('data-activity-name');

                // Display the activity name in the modal
                activityNameDisplay.textContent = activityName;

                // Set the form action to the correct route
                deleteForm.action = "{{ route('reviews.force-delete', '') }}/" + reviewId;
            });
        });
    });
    </script>
@endsection
