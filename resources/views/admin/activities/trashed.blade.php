@extends('admin.layouts.admin')

@section('title', 'Trashed Activities')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tourism / Activities /</span> Trash
        </h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Trashed Activities</h5>
                <a href="{{ route('activities.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Activities
                </a>
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
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($activity->image)
                                        <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->name }}"
                                            style="width: 80px; height: 60px; object-fit: cover;"
                                            class="rounded shadow-sm border">
                                    @else
                                        <div class="avatar bg-light-primary" style="width: 40px; height: 40px;">
                                            <span class="avatar-initial rounded">{{ substr($activity->name, 0, 1) }}</span>
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
                                <td>{{ $activity->deleted_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item restore-btn" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#restoreConfirmModal"
                                                data-activity-id="{{ $activity->id }}"
                                                data-activity-name="{{ $activity->name }}"
                                                data-route="{{ route('activities.restore', $activity->id) }}">
                                                <i class="bx bx-revision me-1"></i> Restore
                                            </a>
                                            <a class="dropdown-item delete-permanently-btn" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deletePermanentlyConfirmModal"
                                                data-activity-id="{{ $activity->id }}"
                                                data-activity-name="{{ $activity->name }}"
                                                data-route="{{ route('activities.forceDelete', $activity->id) }}">
                                                <i class="bx bx-trash me-1"></i> Delete Permanently
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No trashed activities found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Restore Confirmation Modal -->
        <div class="modal fade" id="restoreConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Restore</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to restore the activity
                            <span id="restore-activity-name" class="fw-bold text-primary"></span>?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="restoreActivityForm" method="POST" action="">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary">
                                Restore
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Permanently Confirmation Modal -->
        <div class="modal fade" id="deletePermanentlyConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Permanent Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to permanently delete the activity
                            <span id="delete-permanently-activity-name" class="fw-bold text-danger"></span>?
                        </p>
                        <p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deletePermanentlyActivityForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete Permanently
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle restore button clicks
            const restoreButtons = document.querySelectorAll('.restore-btn');
            const restoreActivityNameSpan = document.getElementById('restore-activity-name');
            const restoreForm = document.getElementById('restoreActivityForm');

            restoreButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const activityName = this.getAttribute('data-activity-name');

                    restoreForm.action = route;
                    restoreActivityNameSpan.textContent = activityName;
                });
            });

            // Handle delete permanently button clicks
            const deletePermanentlyButtons = document.querySelectorAll('.delete-permanently-btn');
            const deletePermanentlyActivityNameSpan = document.getElementById('delete-permanently-activity-name');
            const deletePermanentlyForm = document.getElementById('deletePermanentlyActivityForm');

            deletePermanentlyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const activityName = this.getAttribute('data-activity-name');

                    deletePermanentlyForm.action = route;
                    deletePermanentlyActivityNameSpan.textContent = activityName;
                });
            });
        });
    </script>
@endsection
