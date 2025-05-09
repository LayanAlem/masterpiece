@extends('admin.layouts.admin')

@section('title', 'Referral Program')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Referrals /</span> Management
        </h4>

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

        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('referrals.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Name, Email or Referral Code" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="filter" class="form-label">Filter By</label>
                        <select class="form-select" id="filter" name="filter">
                            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Users</option>
                            <option value="has_referred" {{ request('filter') == 'has_referred' ? 'selected' : '' }}>Has
                                Referred Others</option>
                            <option value="was_referred" {{ request('filter') == 'was_referred' ? 'selected' : '' }}>Was
                                Referred</option>
                            <option value="has_balance" {{ request('filter') == 'has_balance' ? 'selected' : '' }}>Has
                                Balance</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                            </option>
                            <option value="balance_asc" {{ request('sort') == 'balance_asc' ? 'selected' : '' }}>Balance
                                (Low-High)</option>
                            <option value="balance_desc" {{ request('sort') == 'balance_desc' ? 'selected' : '' }}>Balance
                                (High-Low)</option>
                            <option value="referrals_desc" {{ request('sort') == 'referrals_desc' ? 'selected' : '' }}>Most
                                Referrals</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-search me-1"></i> Search
                            </button>
                            <a href="{{ route('referrals.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-reset"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- All Users Referral Data Table -->
        <div class="card">
            <h5 class="card-header">All Users Referral Data</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Referral Code</th>
                            <th>Referral Balance</th>
                            <th>Has Referred</th>
                            <th>Referred By</th>
                            <th>Remaining Uses</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($user->first_name ?? '', 0, 1) }}{{ substr($user->last_name ?? '', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('users.show', $user->id) }}" class="text-body text-truncate">
                                                <span class="fw-semibold">{{ $user->first_name }}
                                                    {{ $user->last_name }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-label-info">{{ $user->referral_code ?? 'No Code' }}</span>
                                </td>
                                <td>${{ number_format($user->referral_balance ?? 0, 2) }}</td>
                                <td>
                                    @if ($user->referrals_count > 0)
                                        <span class="badge bg-label-success">{{ $user->referrals_count }} Users</span>
                                    @else
                                        <span class="badge bg-label-secondary">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->referrer)
                                        <a href="{{ route('users.show', $user->referrer->id) }}">
                                            {{ $user->referrer->first_name }} {{ $user->referrer->last_name }}
                                        </a>
                                    @else
                                        <span class="badge bg-label-secondary">None</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $remaining = max(0, $settings['max_uses'] - $user->referrals_count);
                                    @endphp
                                    <span class="badge bg-label-{{ $remaining > 0 ? 'primary' : 'warning' }}">
                                        {{ $remaining }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No users found</td>
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
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Referral Program Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('referrals.settings') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="referral_enabled"
                                        name="referral_enabled" value="1"
                                        {{ $settings['enabled'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="referral_enabled">Enable Referral Program</label>
                                </div>
                                <small class="text-muted">Toggle the referral program on or off</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="referral_reward_amount" class="form-label">Reward Amount ($)</label>
                                <input type="number" id="referral_reward_amount" name="referral_reward_amount"
                                    class="form-control" min="0" step="0.01"
                                    value="{{ $settings['reward_amount'] }}" required>
                                <small class="text-muted">Amount in dollars awarded for each successful referral</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="referral_max_uses" class="form-label">Maximum Uses Per User</label>
                                <input type="number" id="referral_max_uses" name="referral_max_uses"
                                    class="form-control" min="1" value="{{ $settings['max_uses'] }}" required>
                                <small class="text-muted">Maximum number of successful referrals per user</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
