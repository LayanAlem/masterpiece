@extends('admin.layouts.admin')

@section('title', 'Add New Restaurant')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Restaurants /</span> Add New</h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Restaurant Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('restaurants.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Basic Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Restaurant Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="cuisine_type">Cuisine Type <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cuisine_type" name="cuisine_type"
                                        value="{{ old('cuisine_type') }}" required />
                                    @error('cuisine_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="location">Location <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        value="{{ old('location') }}" required />
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="contact_number">Contact Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        value="{{ old('contact_number') }}" required />
                                    @error('contact_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email & Website -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" required />
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="website">Website</label>
                                    <input type="url" class="form-control" id="website" name="website"
                                        value="{{ old('website') }}" />
                                    @error('website')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price Range & Status -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="price_range">Price Range <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="price_range" name="price_range" required>
                                        <option value="">Select price range</option>
                                        <option value="$" {{ old('price_range') == '$' ? 'selected' : '' }}>$
                                            (Budget)</option>
                                        <option value="$$" {{ old('price_range') == '$$' ? 'selected' : '' }}>$$
                                            (Moderate)</option>
                                        <option value="$$$" {{ old('price_range') == '$$$' ? 'selected' : '' }}>$$$
                                            (Expensive)</option>
                                        <option value="$$$$" {{ old('price_range') == '$$$$' ? 'selected' : '' }}>$$$$
                                            (Luxury)</option>
                                    </select>
                                    @error('price_range')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active') ? 'checked' : '' }} />
                                        <label class="form-check-label" for="is_active">Active Status</label>
                                    </div>
                                    @error('is_active')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Restaurant Image</label>
                                <input class="form-control" type="file" id="image" name="image"
                                    accept="image/*">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Create Restaurant</button>
                                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
