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

                            <div class="mb-3">
                                <label for="image" class="form-label">Activity Image</label>
                                @if ($activity->image)
                                    <div class="mb-3">
                                        <label class="form-label">Current Image</label><br>
                                        <img src="{{ Storage::url($activity->image) }}" style="width: 120px; height: 90px; object-fit: cover;"
                                            class="rounded border">
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="image" class="form-label">Change Image</label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Recommended size: 800x600 pixels. Max file size: 2MB.</div>
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
        });
    </script>
@endsection
