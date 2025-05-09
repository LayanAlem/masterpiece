@extends('admin.layouts.admin')

@section('title', 'Loyalty Points - User Management')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Loyalty Program /</span> User Points
        </h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('loyalty-points.users') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="search" class="form-label">Search Users</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Name or Email" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="min_points" class="form-label">Min Points</label>
                            <input type="number" class="form-control" id="min_points" name="min_points"
                                placeholder="Minimum" value="{{ request('min_points') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="max_points" class="form-label">Max Points</label>
                            <input type="number" class="form-control" id="max_points" name="max_points"
                                placeholder="Maximum" value="{{ request('max_points') }}">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-search-alt me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- / Search & Filter -->

        <!-- Users Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manage User Points</h5>
                <div>
                    <a href="{{ route('loyalty-points.index') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-home me-1"></i> Dashboard
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Current Points</th>
                            <th>Used Points</th>
                            <th>Registered</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-2">
                                                @if ($user->profile_image)
                                                    <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                        alt="{{ $user->first_name }}" class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ substr($user->first_name, 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $user->name }}</span>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-label-success">{{ number_format($user->loyalty_points ?? 0) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-label-warning">{{ number_format($user->used_points ?? 0) }}</span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>{{ $user->updated_at->diffForHumans() }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('loyalty-points.edit-user', $user->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Adjust Points
                                            </a>
                                            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                                <i class="bx bx-user me-1"></i> View Profile
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        <!-- / Users Table -->
    </div>
@endsection
