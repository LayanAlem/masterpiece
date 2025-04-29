@extends('admin.layouts.admin')

@section('title', 'Edit Administrator')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Administration /</span> Edit Admin
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Administrator</h5>
                        <a href="{{ route('admins.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admins.update', $admin->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $admin->name) }}" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $admin->email) }}" required />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password (leave blank to keep current)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" />
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" />
                                </div>
                            </div>

                            @if (Auth::guard('admin')->user()->isSuperAdmin())
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>
                                            Administrator</option>
                                        <option value="super_admin"
                                            {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>Super Admin
                                        </option>
                                        <option value="content_manager"
                                            {{ old('role', $admin->role) == 'content_manager' ? 'selected' : '' }}>Content
                                            Manager</option>
                                        <option value="booking_manager"
                                            {{ old('role', $admin->role) == 'booking_manager' ? 'selected' : '' }}>Booking
                                            Manager</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="role" value="{{ $admin->role }}" />
                            @endif

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Update Administrator
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
