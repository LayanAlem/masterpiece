@extends('admin.layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Edit User</h4>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit <span style="color: #6f4417">{{ $user->first_name .' '. $user->last_name }}</span> Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="first_name">First Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name"
                                    name="first_name"
                                    placeholder="First Name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                />
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input
                                    type="text"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name"
                                    name="last_name"
                                    placeholder="Last Name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                />
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="example@example.com"
                                value="{{ old('email', $user->email) }}"
                            />
                            <div class="form-text">You can use letters, numbers & periods</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone No</label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="658 799 8941"
                                value="{{ old('phone', $user->phone) }}"
                            />
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="loyalty_points">Loyalty Points</label>
                            <input
                                type="number"
                                id="loyalty_points"
                                name="loyalty_points"
                                class="form-control @error('loyalty_points') is-invalid @enderror"
                                placeholder="0"
                                value="{{ old('loyalty_points', $user->loyalty_points) }}"
                            />
                            @error('loyalty_points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="referral_code">Referral Code</label>
                            <input
                                type="text"
                                id="referral_code"
                                name="referral_code"
                                class="form-control @error('referral_code') is-invalid @enderror"
                                placeholder="ABC123"
                                value="{{ old('referral_code', $user->referral_code) }}"
                                readonly
                            />
                            <div class="form-text">Referral code cannot be edited</div>
                            @error('referral_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="referred_by">Referred By</label>
                            <select
                                id="referred_by"
                                name="referred_by"
                                class="form-select @error('referred_by') is-invalid @enderror"
                            >
                                <option value="">None</option>
                                @foreach(\App\Models\User::where('id', '!=', $user->id)->get() as $referrer)
                                    <option value="{{ $referrer->id }}" {{ old('referred_by', $user->referred_by) == $referrer->id ? 'selected' : '' }}>
                                        {{ $referrer->first_name }} {{ $referrer->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('referred_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {

    });
</script>
