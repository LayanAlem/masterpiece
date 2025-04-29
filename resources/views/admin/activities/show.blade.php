@extends('admin.layouts.admin')

@section('title', 'Activity Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Activities /</span> Activity Details
    </h4>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Activity Information</h5>
                    <div>
                        <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                        <a href="{{ route('activities.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($activity->image)
                                <img src="{{ Storage::url($activity->image) }}"
                                    alt="{{ $activity->name }}"
                                    class="img-fluid rounded"
                                    style="max-height: 250px; object-fit: cover;">
                            @else
                                <div class="avatar bg-label-primary"
                                     style="width: 200px; height: 200px; margin: 0 auto;">
                                    <span class="avatar-initial rounded fs-1">{{ substr($activity->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" style="width: 35%;">Name:</td>
                                            <td>{{ $activity->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Category Type:</td>
                                            <td>{{ $activity->categoryType->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Main Category:</td>
                                            <td>{{ $activity->categoryType->mainCategory->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Price:</td>
                                            <td>${{ number_format($activity->price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Cost:</td>
                                            <td>${{ number_format($activity->cost, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Capacity:</td>
                                            <td>{{ $activity->capacity }} {{ Str::plural('person', $activity->capacity) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Location:</td>
                                            <td>{{ $activity->location }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date Range:</td>
                                            <td>
                                                {{ date('M d, Y H:i', strtotime($activity->start_date)) }} -
                                                {{ date('M d, Y H:i', strtotime($activity->end_date)) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Options:</td>
                                            <td>
                                                @if($activity->is_family_friendly)
                                                    <span class="badge bg-label-success me-1">Family Friendly</span>
                                                @endif
                                                @if($activity->is_accessible)
                                                    <span class="badge bg-label-info me-1">Accessible</span>
                                                @endif
                                                @if(!$activity->is_family_friendly && !$activity->is_accessible)
                                                    <span class="text-muted">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Created At:</td>
                                            <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Updated:</td>
                                            <td>{{ $activity->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Activity Description</h5>
                </div>
                <div class="card-body">
                    {!! $activity->description !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
