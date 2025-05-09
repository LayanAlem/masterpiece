@extends('admin.layouts.admin')

@section('title', 'Edit Activity')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Activities /</span> Edit Activity
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Activity: {{ $activity->name }}</h5>
                        <a href="{{ route('activities.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('activities.update', $activity->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Activity Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $activity->name) }}"
                                            placeholder="Enter activity name" required />
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_type_id" class="form-label">Category Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('category_type_id') is-invalid @enderror"
                                            id="category_type_id" name="category_type_id" required>
                                            <option value="">-- Select Category Type --</option>
                                            @foreach ($categoryTypes as $categoryType)
                                                <option value="{{ $categoryType->id }}"
                                                    {{ old('category_type_id', $activity->category_type_id) == $categoryType->id ? 'selected' : '' }}>
                                                    {{ $categoryType->name }}
                                                    ({{ $categoryType->mainCategory->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price ($) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                            id="price" name="price" value="{{ old('price', $activity->price) }}"
                                            min="0" step="0.01" required />
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cost" class="form-label">Cost ($) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('cost') is-invalid @enderror"
                                            id="cost" name="cost" value="{{ old('cost', $activity->cost) }}"
                                            min="0" step="0.01" required />
                                        @error('cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label">Capacity <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                            id="capacity" name="capacity"
                                            value="{{ old('capacity', $activity->capacity) }}" min="1" required />
                                        @error('capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location"
                                            value="{{ old('location', $activity->location) }}"
                                            placeholder="Enter activity location" required />
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date <span
                                                class="text-danger">*</span></label>
                                        <input type="datetime-local"
                                            class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                            name="start_date"
                                            value="{{ old('start_date', \Carbon\Carbon::parse($activity->start_date)->format('Y-m-d\TH:i')) }}"
                                            required />
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date <span
                                                class="text-danger">*</span></label>
                                        <input type="datetime-local"
                                            class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                            name="end_date"
                                            value="{{ old('end_date', \Carbon\Carbon::parse($activity->end_date)->format('Y-m-d\TH:i')) }}"
                                            required />
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label d-block">Activity Options</label>
                                        <div class="form-check form-check-inline mt-2">
                                            <input class="form-check-input" type="checkbox" id="is_family_friendly"
                                                name="is_family_friendly" value="1"
                                                {{ old('is_family_friendly', $activity->is_family_friendly) ? 'checked' : '' }} />
                                            <label class="form-check-label" for="is_family_friendly">Family
                                                Friendly</label>
                                        </div>
                                        <div class="form-check form-check-inline mt-2">
                                            <input class="form-check-input" type="checkbox" id="is_accessible"
                                                name="is_accessible" value="1"
                                                {{ old('is_accessible', $activity->is_accessible) ? 'checked' : '' }} />
                                            <label class="form-check-label" for="is_accessible">Accessible</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="5" placeholder="Enter activity description" required>{{ old('description', $activity->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Activity Images (Up to 3 images)</label>
                                <div class="activity-images">
                                    <div class="row mb-3">
                                        <!-- Primary Image Slot -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-2">Primary Image</h6>
                                                    <div class="image-preview mb-2" id="primaryImagePreview">
                                                        @php
                                                            $primaryImage = $activity->images
                                                                ->where('is_primary', true)
                                                                ->first();
                                                        @endphp
                                                        @if ($primaryImage)
                                                            <img src="{{ Storage::url($primaryImage->path) }}"
                                                                class="img-fluid rounded">
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $primaryImage->id }}">
                                                        @else
                                                            <img src="{{ asset('assetsAdmin/img/image-placeholder.jpg') }}"
                                                                class="img-fluid rounded">
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <input class="form-control @error('images.0') is-invalid @enderror"
                                                            type="file" id="primary_image" name="images[]"
                                                            accept="image/*"
                                                            onchange="previewImage(this, 'primaryImagePreview')">
                                                        <input type="hidden" name="is_primary[]" value="1">
                                                        @error('images.0')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <div class="form-text">This will be the main image displayed</div>
                                                    </div>
                                                    @if ($primaryImage)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="delete_primary_image" name="delete_images[]"
                                                                value="{{ $primaryImage->id }}">
                                                            <label class="form-check-label text-danger"
                                                                for="delete_primary_image">
                                                                Delete this image
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Image 1 -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-2">Additional Image 1</h6>
                                                    <div class="image-preview mb-2" id="image1Preview">
                                                        @php
                                                            $additionalImages = $activity->images
                                                                ->where('is_primary', false)
                                                                ->values();
                                                            $image1 = $additionalImages->get(0);
                                                        @endphp
                                                        @if ($image1)
                                                            <img src="{{ Storage::url($image1->path) }}"
                                                                class="img-fluid rounded">
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $image1->id }}">
                                                        @else
                                                            <img src="{{ asset('assetsAdmin/img/image-placeholder.jpg') }}"
                                                                class="img-fluid rounded">
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <input
                                                            class="form-control @error('images.1') is-invalid @enderror"
                                                            type="file" id="image1" name="images[]"
                                                            accept="image/*"
                                                            onchange="previewImage(this, 'image1Preview')">
                                                        <input type="hidden" name="is_primary[]" value="0">
                                                        @error('images.1')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    @if ($image1)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="delete_image1" name="delete_images[]"
                                                                value="{{ $image1->id }}">
                                                            <label class="form-check-label text-danger"
                                                                for="delete_image1">
                                                                Delete this image
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Image 2 -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-2">Additional Image 2</h6>
                                                    <div class="image-preview mb-2" id="image2Preview">
                                                        @php
                                                            $image2 = $additionalImages->get(1);
                                                        @endphp
                                                        @if ($image2)
                                                            <img src="{{ Storage::url($image2->path) }}"
                                                                class="img-fluid rounded">
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $image2->id }}">
                                                        @else
                                                            <img src="{{ asset('assetsAdmin/img/image-placeholder.jpg') }}"
                                                                class="img-fluid rounded">
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <input
                                                            class="form-control @error('images.2') is-invalid @enderror"
                                                            type="file" id="image2" name="images[]"
                                                            accept="image/*"
                                                            onchange="previewImage(this, 'image2Preview')">
                                                        <input type="hidden" name="is_primary[]" value="0">
                                                        @error('images.2')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    @if ($image2)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="delete_image2" name="delete_images[]"
                                                                value="{{ $image2->id }}">
                                                            <label class="form-check-label text-danger"
                                                                for="delete_image2">
                                                                Delete this image
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">Recommended size: 800x600 pixels. Max file size: 2MB per image.
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('activities.index') }}"
                                    class="btn btn-outline-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Activity</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add validation for end_date being after start_date
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function updateEndDateMin() {
                if (startDateInput.value) {
                    endDateInput.min = startDateInput.value;

                    // If end date is now before start date, update it
                    if (endDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = startDateInput.value;
                    }
                }
            }

            startDateInput.addEventListener('change', updateEndDateMin);

            // Initialize on page load
            updateEndDateMin();

            // Handle delete image checkboxes
            const deleteCheckboxes = document.querySelectorAll('input[name="delete_images[]"]');
            deleteCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const imagePreview = this.closest('.card-body').querySelector(
                        '.image-preview img');
                    if (this.checked) {
                        imagePreview.style.opacity = '0.3';
                    } else {
                        imagePreview.style.opacity = '1';
                    }
                });
            });
        });

        // Image preview function
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId).querySelector('img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.opacity = '1';

                    // If there's a delete checkbox for this image, uncheck it
                    const deleteCheckbox = input.closest('.card-body').querySelector('input[type="checkbox"]');
                    if (deleteCheckbox) {
                        deleteCheckbox.checked = false;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "{{ asset('assetsAdmin/img/image-placeholder.jpg') }}";
            }
        }
    </script>
@endsection
