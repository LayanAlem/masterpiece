@extends('admin.layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Hidden Gem / Blog Posts /</span> Edit Post
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Blog Post</h5>
                        <div>
                            <a href="{{ route('blog-posts.index') }}" class="btn btn-outline-secondary">
                                <i class='bx bx-arrow-back me-1'></i> Back to Posts
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('blog-posts.update', $post->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="title" class="form-label">Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $post->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="published"
                                            {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published
                                        </option>
                                        <option value="draft"
                                            {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending"
                                            {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8"
                                    required>{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">HTML tags are allowed for formatting.</small>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Featured Image</label>
                                @if ($post->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                            class="img-thumbnail" style="max-height: 150px;">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" id="remove_image"
                                                name="remove_image">
                                            <label class="form-check-label" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max file size: 2MB. Allowed formats: JPEG, PNG, JPG, GIF.</small>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class='bx bx-save me-1'></i> Update Post
                                </button>
                                <a href="{{ route('blog-posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        // You can add a rich text editor here if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any JS components
        });
    </script>
@endsection
