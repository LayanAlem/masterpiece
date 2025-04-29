@extends('admin.layouts.admin')

@section('title', 'Create User')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Users /</span> Create User
        </h4>

        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Create New User</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-3 py-2">
                        <i class='bx bx-arrow-back'></i> Back to Users
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control"
                                id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="loyalty_points" class="form-label">Loyalty Points</label>
                            <input type="number" class="form-control @error('loyalty_points') is-invalid @enderror"
                                id="loyalty_points" name="loyalty_points" value="{{ old('loyalty_points', 0) }}">
                            @error('loyalty_points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="referral_code" class="form-label">Referral Code</label>
                            <input type="text" class="form-control @error('referral_code') is-invalid @enderror"
                                id="referral_code" name="referral_code" value="{{ old('referral_code') }}">
                            @error('referral_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="referred_by" class="form-label">Referred By</label>
                            <select class="form-select @error('referred_by') is-invalid @enderror"
                                id="referred_by" name="referred_by">
                                <option value="">None</option>
                                @foreach($referrers as $referrer)
                                    <option value="{{ $referrer->id }}" {{ old('referred_by') == $referrer->id ? 'selected' : '' }}>
                                        {{ $referrer->first_name }} {{ $referrer->last_name }} ({{ $referrer->referral_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('referred_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class='bx bx-save'></i> Save User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
