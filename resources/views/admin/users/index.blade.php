@extends('admin.layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Manage Users</h4>

        <!-- Filters Card -->
        <div class="card mb-4">
            <h5 class="card-header">Filters</h5>
            <div class="card-body">
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Name, Email, Phone">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="points_min" class="form-label">Min Points</label>
                            <input type="number" class="form-control" id="points_min" name="points_min"
                                value="{{ request('points_min') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="points_max" class="form-label">Max Points</label>
                            <input type="number" class="form-control" id="points_max" name="points_max"
                                value="{{ request('points_max') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="sort_by" class="form-label">Sort By</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date
                                    Created</option>
                                <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First
                                    Name</option>
                                <option value="last_name" {{ request('sort_by') == 'last_name' ? 'selected' : '' }}>Last
                                    Name</option>
                                <option value="loyalty_points"
                                    {{ request('sort_by') == 'loyalty_points' ? 'selected' : '' }}>Loyalty Points</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <select class="form-select" id="sort_order" name="sort_order">
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending
                                </option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">Reset</a>
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Card -->
        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Users</h5>
                    <div>
                        <a href="{{ route('users.trashed') }}" class="btn btn-outline-secondary me-2">
                            <i class="bx bx-trash"></i> Trashed Users
                        </a>
                        <a href="{{ route('users.create') }}" class="btn btn-primary px-3 py-2">
                            <i class='bx bx-plus'></i> Add User
                        </a>
                    </div>
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
                            <th>Loyalty Points</th>
                            <th>Referral Balance</th>
                            <th>Referral Code</th>
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
                                <td>{{ $user->loyalty_points ?? 0 }}</td>
                                <td>${{ number_format($user->referral_balance ?? 0, 2) }}</td>
                                <td>
                                    <small class="text-muted">{{ $user->referral_code }}</small>
                                </td>
                                <td>
                                    <div class="dropdown ms-3">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>
                                            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <button class="dropdown-item delete-btn" type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No users found</td>
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
                            <p>Are you sure you want to delete the user <span id="user-name-display"></span>?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteUserForm" method="POST" action="">
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
            const userNameDisplay = document.getElementById('user-name-display');
            const deleteForm = document.getElementById('deleteUserForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');

                    deleteForm.action = `{{ url('admin/users') }}/${userId}`;
                    userNameDisplay.textContent = userName;
                });
            });
        });
    </script>
@endsection
