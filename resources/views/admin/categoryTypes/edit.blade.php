@extends('admin.layouts.admin')

@section('title', 'Edit Category Type')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Categories / Category Types /</span> Edit
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Category Type: {{ $categoryType->name }}</h5>
                    <div>
                        <a href="{{ route('category-types.show', $categoryType->id) }}" class="btn btn-info me-2">
                            <i class="bx bx-show-alt me-1"></i> View
                        </a>
                        <a href="{{ route('category-types.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('category-types.update', $categoryType->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $categoryType->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="main_category_id" class="form-label">Main Category</label>
                                    <select class="form-select" id="main_category_id" name="main_category_id">
                                        <option value="">Select Main Category</option>
                                        @foreach($mainCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('main_category_id', $categoryType->main_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                            {{ old('status', $categoryType->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image (Optional)</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="form-text">Recommended size: 300x300px. Max file size: 2MB</div>
                                    @if($categoryType->image)
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image" value="1">
                                            <label class="form-check-label" for="delete_image">Delete current image</label>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Preview</label>
                                    <div class="border rounded p-3 text-center">
                                        @if($categoryType->image)
                                            <img id="image-preview" src="{{ asset('storage/' . $categoryType->image) }}"
                                                alt="{{ $categoryType->name }}" style="max-width: 100%; max-height: 200px;">
                                            <div id="image-placeholder" class="avatar bg-label-primary mx-auto"
                                                style="width: 150px; height: 150px; display: none;">
                                                <span class="avatar-initial rounded" style="font-size: 5rem;">
                                                    {{ substr($categoryType->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @else
                                            <img id="image-preview" src="#" alt="Preview"
                                                style="max-width: 100%; max-height: 200px; display: none;">
                                            <div id="image-placeholder" class="avatar bg-label-primary mx-auto"
                                                style="width: 150px; height: 150px;">
                                                <span class="avatar-initial rounded" style="font-size: 5rem;">
                                                    {{ substr($categoryType->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $categoryType->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-save me-1"></i> Update
                            </button>
                            <a href="{{ route('category-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview logic
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePlaceholder = document.getElementById('image-placeholder');
        const deleteImageCheckbox = document.getElementById('delete_image');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    imagePlaceholder.style.display = 'none';

                    if (deleteImageCheckbox) {
                        deleteImageCheckbox.checked = false;
                    }
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Handle delete image checkbox
        if (deleteImageCheckbox) {
            deleteImageCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    imagePreview.style.display = 'none';
                    imagePlaceholder.style.display = 'block';
                    imageInput.value = ''; // Clear the file input
                } else {
                    // If there's a current image, show it again
                    if (imagePreview.getAttribute('src') !== '#') {
                        imagePreview.style.display = 'block';
                        imagePlaceholder.style.display = 'none';
                    }
                }
            });
        }
    });
</script>
@endsection
