@extends('admin.layouts.admin')

@section('title', 'View Category Type')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Categories / Category Types /</span> View
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Category Type Details</h5>
                    <div>
                        <a href="{{ route('category-types.edit', $categoryType->id) }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                        <a href="{{ route('category-types.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            @if($categoryType->image)
                                <img src="{{ asset('storage/' . $categoryType->image) }}"
                                    alt="{{ $categoryType->name }}"
                                    class="rounded mb-3"
                                    width="150">
                            @else
                                <div class="avatar bg-label-primary mb-3 mx-auto" style="width: 150px; height: 150px;">
                                    <span class="avatar-initial rounded" style="font-size: 5rem;">{{ substr($categoryType->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="200">Name</th>
                                            <td>{{ $categoryType->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Main Category</th>
                                            <td>{{ $categoryType->mainCategory->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-label-{{ $categoryType->status ? 'success' : 'secondary' }}">
                                                    {{ $categoryType->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Associated Activities</th>
                                            <td>{{ $categoryType->activities_count ?? $categoryType->activities->count() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $categoryType->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated</th>
                                            <td>{{ $categoryType->updated_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Description</h5>
                                </div>
                                <div class="card-body">
                                    @if($categoryType->description)
                                        {{ $categoryType->description }}
                                    @else
                                        <p class="text-muted">No description available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($categoryType->activities && $categoryType->activities->count() > 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Associated Activities</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($categoryType->activities as $key => $activity)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $activity->name }}</td>
                                                        <td>
                                                            <span class="badge bg-label-{{ $activity->status ? 'success' : 'secondary' }}">
                                                                {{ $activity->status ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('activities.show', $activity->id) }}" class="btn btn-sm btn-info">
                                                                <i class="bx bx-show-alt"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
