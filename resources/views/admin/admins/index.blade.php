@extends('admin.layouts.admin')

@section('title', 'Admin Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Administration /</span> Admins</h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admins.index') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search"
                                    placeholder="Name or email" value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">All Roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="reset-filter" class="btn btn-outline-secondary w-100">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Administrators</h5>
                <div>
                    <a href="{{ route('admins.trashed') }}" class="btn btn-outline-secondary me-2">
                        <i class="bx bx-trash me-1"></i> Trash
                    </a>
                    @if (Auth::guard('admin')->user()->isSuperAdmin())
                        <a href="{{ route('admins.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> Add New Admin
                        </a>
                    @endif
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2 bg-label-primary">
                                            <span
                                                class="avatar-initial rounded-circle">{{ substr($admin->name, 0, 1) }}</span>
                                        </div>
                                        <span>{{ $admin->name }}</span>
                                        @if ($admin->id === Auth::guard('admin')->id())
                                            <span class="badge bg-label-info ms-1">You</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if ($admin->role === 'super_admin')
                                        <span class="badge bg-label-primary">Super Admin</span>
                                    @elseif($admin->role === 'content_manager')
                                        <span class="badge bg-label-success">Content Manager</span>
                                    @elseif($admin->role === 'booking_manager')
                                        <span class="badge bg-label-warning">Booking Manager</span>
                                    @else
                                        <span
                                            class="badge bg-label-secondary">{{ ucwords(str_replace('_', ' ', $admin->role)) }}</span>
                                    @endif
                                </td>
                                <td>{{ $admin->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admins.edit', $admin->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            @if (Auth::guard('admin')->user()->isSuperAdmin() && $admin->id !== Auth::guard('admin')->id())
                                                <button class="dropdown-item delete-btn" data-bs-toggle="modal"
                                                    data-bs-target="#deleteConfirmModal"
                                                    data-admin-id="{{ $admin->id }}"
                                                    data-admin-name="{{ $admin->name }}"
                                                    data-route="{{ route('admins.destroy', $admin->id) }}">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-user-x text-secondary mb-2" style="font-size: 3rem;"></i>
                                        <h6 class="mb-1">No Admins Found</h6>
                                        <p class="text-muted small">No administrators match your search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $admins->firstItem() ?? 0 }} to {{ $admins->lastItem() ?? 0 }} of
                        {{ $admins->total() }} admins
                    </div>
                    <div>
                        {{ $admins->links() }}
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
                    <div class="modal-body">
                        <p>Are you sure you want to delete the admin
                            <span id="admin-name" class="fw-bold text-danger"></span>?
                        </p>
                        <p class="text-warning">This will move the admin to trash. You can restore it later.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deleteAdminForm" method="POST" action="">
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
            const adminNameSpan = document.getElementById('admin-name');
            const deleteForm = document.getElementById('deleteAdminForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const adminName = this.getAttribute('data-admin-name');

                    deleteForm.action = route;
                    adminNameSpan.textContent = adminName;
                });
            });

            // Handle filter reset
            document.getElementById('reset-filter').addEventListener('click', function() {
                document.getElementById('search').value = '';
                document.getElementById('role').value = '';

                document.getElementById('filterForm').submit();
            });

            // Automatically submit form when select fields change
            document.getElementById('role').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endsection
