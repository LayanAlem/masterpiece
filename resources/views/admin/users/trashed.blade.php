@extends('admin.layouts.admin')

@section('title', 'Trashed Users')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Users /</span> Trashed Users
        </h4>

        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Trashed Users</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-primary px-3 py-2">
                        <i class='bx bx-arrow-back'></i> Back to Users
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="dropdown ms-3">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item restore-btn" type="button" data-bs-toggle="modal"
                                                data-bs-target="#restoreConfirmModal" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                <i class="bx bx-revision me-1"></i> Restore
                                            </button>
                                            <button class="dropdown-item delete-permanent-btn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#deletePermanentModal"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                <i class="bx bx-trash me-1"></i> Delete Permanently
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No trashed users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                        entries
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
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
                    <div class="row">
                        <div class="col mb-3">
                            <p>Are you sure you want to restore the user <span id="restore-user-name"></span>?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="restoreUserForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Permanent Confirmation Modal -->
    <div class="modal fade" id="deletePermanentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <p>Are you sure you want to permanently delete the user <span
                                    id="delete-permanent-user-name"></span>? This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deletePermanentForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restore functionality
            const restoreButtons = document.querySelectorAll('.restore-btn');
            const restoreUserName = document.getElementById('restore-user-name');
            const restoreForm = document.getElementById('restoreUserForm');

            restoreButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');

                    restoreForm.action = `{{ url('admin/users') }}/${userId}/restore`;
                    restoreUserName.textContent = userName;
                });
            });

            // Permanent delete functionality
            const deletePermanentButtons = document.querySelectorAll('.delete-permanent-btn');
            const deletePermanentUserName = document.getElementById('delete-permanent-user-name');
            const deletePermanentForm = document.getElementById('deletePermanentForm');

            deletePermanentButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');

                    deletePermanentForm.action = `{{ url('admin/users') }}/${userId}/force-delete`;
                    deletePermanentUserName.textContent = userName;
                });
            });
        });
    </script>
@endsection
