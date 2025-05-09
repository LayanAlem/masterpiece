@extends('admin.layouts.admin')

@section('title', 'Adjust User Loyalty Points')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Loyalty Program / <a href="{{ route('loyalty-points.users') }}">Users</a>
                /</span> Adjust Points
        </h4>

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

        <div class="row">
            <!-- User Info Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-column pt-3">
                            <div class="avatar avatar-xl mb-3">
                                @if ($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->first_name }}"
                                        class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-primary">
                                        {{ substr($user->first_name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <span class="text-muted">{{ $user->email }}</span>

                            <div class="mt-4 pt-3 border-top w-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Loyalty Points</span>
                                    <span class="badge bg-success">{{ number_format($user->loyalty_points ?? 0) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Used Points</span>
                                    <span class="badge bg-warning">{{ number_format($user->used_points ?? 0) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Member Since</span>
                                    <span class="badge bg-info">{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Last Updated</span>
                                    <span class="badge bg-secondary">{{ $user->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / User Info Card -->

            <!-- Adjust Points Card -->
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Adjust Loyalty Points</h5>
                        <a href="{{ route('loyalty-points.users') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-left-arrow-alt me-1"></i> Back to Users
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('loyalty-points.update-user', $user->id) }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Action</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="action" id="action_add"
                                            value="add" checked>
                                        <label class="form-check-label" for="action_add">Add Points</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="action" id="action_deduct"
                                            value="deduct">
                                        <label class="form-check-label" for="action_deduct">Deduct Points</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="points">Points</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="points" name="points" min="1"
                                        value="10" required>
                                    <div class="form-text" id="points_help">Enter the number of points to add or deduct.
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="reason">Reason</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="reason" name="reason" required>
                                        <option value="manual_adjustment">Manual Adjustment</option>
                                        <option value="booking_correction">Booking Correction</option>
                                        <option value="customer_service">Customer Service</option>
                                        <option value="promotional_bonus">Promotional Bonus</option>
                                        <option value="points_correction">Points Correction</option>
                                        <option value="referral_bonus">Referral Bonus</option>
                                        <option value="loyalty_reward">Loyalty Reward</option>
                                    </select>
                                    <div class="form-text">Select the reason for adjusting points.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="note">Additional Note</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="note" rows="3" placeholder="Optional details about this adjustment"></textarea>
                                    <div class="form-text">This note is for internal reference only.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                                    <a href="{{ route('loyalty-points.users') }}"
                                        class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Point History -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="m-0">Recent Point History</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Points</th>
                                    <th>Action</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- In a real implementation, this would show a history of point transactions -->
                                <tr>
                                    <td colspan="4" class="text-center">No history available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- / Point History -->
            </div>
            <!-- / Adjust Points Card -->
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionAdd = document.getElementById('action_add');
            const actionDeduct = document.getElementById('action_deduct');
            const pointsHelp = document.getElementById('points_help');

            actionAdd.addEventListener('change', updateHelpText);
            actionDeduct.addEventListener('change', updateHelpText);

            function updateHelpText() {
                if (actionAdd.checked) {
                    pointsHelp.textContent = "Enter the number of points to add to the user's balance.";
                } else {
                    pointsHelp.textContent = "Enter the number of points to deduct from the user's balance.";
                }
            }

            // Initialize help text
            updateHelpText();
        });
    </script>
@endsection
