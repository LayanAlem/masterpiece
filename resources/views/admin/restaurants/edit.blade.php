@extends('admin.layouts.admin')

@section('title', 'Edit Restaurant')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Restaurants /</span> Edit {{ $restaurant->name }}
        </h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Restaurant Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('restaurants.update', $restaurant->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Basic Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Restaurant Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $restaurant->name) }}" required />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="cuisine_type">Cuisine Type <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cuisine_type" name="cuisine_type"
                                        value="{{ old('cuisine_type', $restaurant->cuisine_type) }}" required />
                                    @error('cuisine_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $restaurant->description) }}</textarea>
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
                                        value="{{ old('location', $restaurant->location) }}" required />
                                    @error('location')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="contact_number">Contact Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        value="{{ old('contact_number', $restaurant->contact_number) }}" required />
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
                                        value="{{ old('email', $restaurant->email) }}" required />
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="website">Website</label>
                                    <input type="url" class="form-control" id="website" name="website"
                                        value="{{ old('website', $restaurant->website) }}" />
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
                                        <option value="$"
                                            {{ old('price_range', $restaurant->price_range) == '$' ? 'selected' : '' }}>$
                                            (Budget)</option>
                                        <option value="$$"
                                            {{ old('price_range', $restaurant->price_range) == '$$' ? 'selected' : '' }}>$$
                                            (Moderate)</option>
                                        <option value="$$$"
                                            {{ old('price_range', $restaurant->price_range) == '$$$' ? 'selected' : '' }}>
                                            $$$ (Expensive)</option>
                                        <option value="$$$$"
                                            {{ old('price_range', $restaurant->price_range) == '$$$$' ? 'selected' : '' }}>
                                            $$$$ (Luxury)</option>
                                    </select>
                                    @error('price_range')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1"
                                            {{ old('is_active', $restaurant->is_active) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="is_active">Active Status</label>
                                    </div>
                                    @error('is_active')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Current Image & Upload New -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="image" class="form-label">Restaurant Image</label>
                                    <input class="form-control" type="file" id="image" name="image"
                                        accept="image/*">
                                    <small class="text-muted">Leave blank to keep current image</small>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    @if ($restaurant->image)
                                        <div class="mt-2">
                                            <p>Current Image:</p>
                                            <img src="{{ asset('storage/' . $restaurant->image) }}"
                                                alt="{{ $restaurant->name }}" class="img-thumbnail"
                                                style="max-height: 150px">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Update Restaurant</button>
                                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
