@extends('admin.layouts.admin')

@section('title', 'Loyalty Points Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Loyalty Program /</span> Dashboard
        </h4>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Users</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($totalUsers) }}</h4>
                                </div>
                                <small>All registered users</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="bx bx-user bx-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Total Points</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($totalPoints) }}</h4>
                                </div>
                                <small>Active loyalty points</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-star bx-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Used Points</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($totalUsedPoints) }}</h4>
                                </div>
                                <small>Points redeemed</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="bx bx-shopping-bag bx-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">Avg Points Per User</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($avgPointsPerUser) }}</h4>
                                </div>
                                <small>Average loyalty balance</small>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="bx bx-line-chart bx-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Statistics Cards -->

        <div class="row">
            <!-- Top Users Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Top Users by Points</h5>
                        <a href="{{ route('loyalty-points.users') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Points</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar avatar-sm me-2">
                                                            @if ($user->profile_image)
                                                                <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                                    alt="{{ $user->first_name }}" class="rounded-circle">
                                                            @else
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-primary">
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
                                                    class="badge bg-label-success">{{ number_format($user->loyalty_points) }}</span>
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
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
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.show', $user->id) }}">
                                                            <i class="bx bx-user me-1"></i> View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Top Users Card -->

            <!-- Recent Activity Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0">Recent Activity</h5>
                        <a href="{{ route('loyalty-points.settings') }}" class="btn btn-sm btn-outline-primary">Program
                            Settings</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Points</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentActivity as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar avatar-sm me-2">
                                                            @if ($user->profile_image)
                                                                <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                                    alt="{{ $user->first_name }}" class="rounded-circle">
                                                            @else
                                                                <span class="avatar-initial rounded-circle bg-label-info">
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
                                                    class="badge bg-label-primary">{{ number_format($user->loyalty_points) }}</span>
                                            </td>
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
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.show', $user->id) }}">
                                                            <i class="bx bx-user me-1"></i> View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Recent Activity Card -->
        </div>

        <!-- Quick Actions Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Loyalty Program Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary text-white shadow-none">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-user-plus bx-lg me-3"></i>
                                            <div>
                                                <h5 class="card-title text-white">Manage Users</h5>
                                                <p class="card-text">View, search and adjust individual user points</p>
                                                <a href="{{ route('loyalty-points.users') }}"
                                                    class="btn btn-sm btn-light">View Users</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card bg-success text-white shadow-none">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-cog bx-lg me-3"></i>
                                            <div>
                                                <h5 class="card-title text-white">Program Settings</h5>
                                                <p class="card-text">Configure loyalty point rates and redemption options
                                                </p>
                                                <button class="btn btn-sm btn-light" disabled>Coming Soon</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Quick Actions Card -->
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Additional JavaScript for loyalty points dashboard if needed
        });
    </script>
@endsection
