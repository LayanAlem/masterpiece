```php
@extends('admin.layouts.admin')

@section('title', 'Edit Main Category')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Categories /</span>
            <a href="{{ route('main-categories.index') }}" class="text-muted fw-light">Main Categories</a> /
            Edit {{ $mainCategory->name }}
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Edit Main Category</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('main-categories.update', $mainCategory->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $mainCategory->name) }}" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="description">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $mainCategory->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="status">Status</label>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $mainCategory->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="image">Image</label>
                                <div class="col-sm-10">
                                    @if($mainCategory->image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/'.$mainCategory->image) }}" alt="{{ $mainCategory->name }}" width="100" class="border rounded p-1">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" />
                                    <small class="text-muted">Leave empty to keep the current image</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Related Info</label>
                                <div class="col-sm-10">
                                    <div class="card bg-light p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-label-secondary me-2">{{ $mainCategory->category_types_count ?? 0 }}</span>
                                            <span>Sub Categories</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-label-info me-2">{{ $mainCategory->created_at->format('Y-m-d') }}</span>
                                            <span>Created</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <a href="{{ route('main-categories.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```
