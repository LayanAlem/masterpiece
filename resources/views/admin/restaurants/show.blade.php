@extends('admin.layouts.admin')

@section('title', 'Restaurant Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
            <h4 class="fw-bold m-0">
                <span class="text-muted fw-light">Restaurants /</span> {{ $restaurant->name }}
            </h4>
            <div>
                <a href="{{ route('restaurants.edit', $restaurant->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <a href="{{ route('restaurants.index') }}" class="btn btn-secondary ms-2">
                    <i class="bx bx-arrow-back me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Restaurant Information Card -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Restaurant Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Info -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Name:</h6>         <p class="fs-5">{{ $restaurant->name }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Cuisine Type:</h6>
        <p>{{ $restaurant->cuisine_type }}</p>
                            </div>

                            <div class="col-12 mb-3">
                                <h6 class="text-muted">Description:</h6>
         p>{{ $restaurant->description }}</p>
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Location:</h6>
          >{{ $restaurant->location }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Contact Number:</h6>
                        t->contact_number }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Email:</h6>
                       nt->email }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Website:</h6>
                                @if ($restaurant->website)
                                    <p><a href="{{ $restaurant->website }}" target="_blank">{{ $restaurant->website }}</a>
                                    </p>
                                @else
                             ted">Not provided</p>
                                @endif
                            </div>

                            <!-- Price and Status -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Price R                   <p>{{ $restaurant->price_range }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Status:</h6>
                                @if ($restaurant->is_active)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <sl-danger">Inactive</span>
                                @endif
                            </div>

                            <!-- Created/Updated -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Created at:</h6>
        <p>{{ $restaurant->created_at->format('M d, Y h:i A') }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Last Updated:</h6>
                                <p>{{ $restaurant->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Card for Image and Featured Status -->
            <div class="col-md-4">
                <!-- Image Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Restaurant Image</h5>
                    </div>
                    <div clss="card-body text-center">
                        @if ($retaurant->image)
                            <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}"
                                class="img-fluid rounded mb-3" style="max-height: 250px">
                        @else
                            <div class="p-5 bg-light rounded text-center">
                                <i class='bx bx-image-alt' style="font-size: 3rem;"></i>
                                <p class="mt-2">No ble</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Featured Status Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Featured Status</h5>
                    </div>
                    <div class="card-body">
                        @if ($restaurant->isCurrentlyFeatured())
                            <div class="alert alert-success" role="alert">
                                <div class="d-flex">
                                    <i class='bx bx-star me-2 fs-5'></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Currently Featured</h6>
                                        <p class="mb-0">
                                            This restaurant is featured until
                                            {{ $restaurant->currentFeaturePeriod()->end_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-secondary" role="alert">
                                <div class="d-flex">
                                    <i class='bx bx-info-circle me-2 fs-5'></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Not Featured</h6>
                                        <p cl                                    This restaurant is currently not featured.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-3">
                            <h6>Feature History</h6>
                            @if (count($restaurant->featuredPeriods) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($restaurant->featuredPeriods as $period)
                                                <tr>
                                                    <td>{{ $period->start_date->format('M d, Y') }}</td>
                                                    <td>{{ $period->end_date->format('M d, Y') }}</td>
                                                    <td>
                                                        @if ($period->is_active)
                                                            <span class="badge bg-label-success">Active</span>
                                                        @else
                                                            <span class="badge bg-label-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No feature history available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this restaurant? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('restaurants.destroy', $restaurant->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
