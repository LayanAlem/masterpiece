@extends('admin.layouts.admin')

@section('title', 'Trashed Admins')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Administration / Admins /</span> Trash
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
                <h5 class="mb-0">Trashed Administrators</h5>
                <a href="{{ route('admins.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Admins
                </a>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 bg-light-secondary">
                                            <span
                                                class="avatar-initial rounded-circle">{{ substr($admin->name, 0, 1) }}</span>
                                        </div>
                                        {{ $admin->name }}
                                    </div>
                                </td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    <span
                                        class="badge bg-label-secondary">{{ ucwords(str_replace('_', ' ', $admin->role)) }}</span>
                                </td>
                                <td>{{ $admin->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item restore-btn" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#restoreConfirmModal"
                                                data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}"
                                                data-route="{{ route('admins.restore', $admin->id) }}">
                                                <i class="bx bx-revision me-1"></i> Restore
                                            </a>
                                            <a class="dropdown-item delete-permanently-btn" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deletePermanentlyConfirmModal"
                                                data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}"
                                                data-route="{{ route('admins.forceDelete', $admin->id) }}">
                                                <i class="bx bx-trash me-1"></i> Delete Permanently
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-trash-alt text-secondary mb-2" style="font-size: 3rem;"></i>
                                        <h6 class="mb-1">No Trashed Admins</h6>
                                        <p class="text-muted small">Trash is empty</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $admins->links() }}
                </div>
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
                        <p>Are you sure you want to restore the admin
                            <span id="restore-admin-name" class="fw-bold text-primary"></span>?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="restoreAdminForm" method="POST" action="">
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
                        <p>Are you sure you want to permanently delete the admin
                            <span id="delete-permanently-admin-name" class="fw-bold text-danger"></span>?
                        </p>
                        <p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deletePermanentlyAdminForm" method="POST" action="">
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
            const restoreAdminNameSpan = document.getElementById('restore-admin-name');
            const restoreForm = document.getElementById('restoreAdminForm');

            restoreButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const adminName = this.getAttribute('data-admin-name');

                    restoreForm.action = route;
                    restoreAdminNameSpan.textContent = adminName;
                });
            });

            // Handle delete permanently button clicks
            const deletePermanentlyButtons = document.querySelectorAll('.delete-permanently-btn');
            const deletePermanentlyAdminNameSpan = document.getElementById('delete-permanently-admin-name');
            const deletePermanentlyForm = document.getElementById('deletePermanentlyAdminForm');

            deletePermanentlyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const adminName = this.getAttribute('data-admin-name');

                    deletePermanentlyForm.action = route;
                    deletePermanentlyAdminNameSpan.textContent = adminName;
                });
            });
        });
    </script>
@endsection
