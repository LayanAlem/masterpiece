@extends('admin.layouts.admin')

@section('title', 'Deleted Restaurants')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Restaurants /</span> Trash</h4>
        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Deleted Restaurants</h5>
                    <a href="{{ route('restaurants.index') }}" class="btn btn-primary">
                        <i class='bx bx-arrow-back me-1'></i> Back to Restaurants
                    </a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Image</th>
                            <th>Cuisine Type</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($trashedRestaurants as $restaurant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ Str::limit($restaurant->location, 30) }}</td>
                                <td>
                                    @if ($restaurant->image)
                                        <img class="rounded shadow-sm border object-fit-cover"
                                            src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}"
                                            width="65" height="65">
                                    @else
                                        <span class="badge bg-label-warning">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $restaurant->cuisine_type }}</td>
                                <td>{{ $restaurant->deleted_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown ms-3">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item restore-btn" type="button" data-bs-toggle="modal"
                                                data-bs-target="#restoreConfirmModal"
                                                data-restaurant-id="{{ $restaurant->id }}"
                                                data-restaurant-name="{{ $restaurant->name }}">
                                                <i class="bx bx-revision me-1"></i> Restore
                                            </button>
                                            <button class="dropdown-item force-delete-btn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#forceDeleteConfirmModal"
                                                data-restaurant-id="{{ $restaurant->id }}"
                                                data-restaurant-name="{{ $restaurant->name }}">
                                                <i class="bx bx-trash me-1"></i> Delete Permanently
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">No deleted restaurants found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Restore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <p>Are you sure you want to restore the restaurant <span id="restore-name-display"></span>?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="restoreRestaurantForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Force Delete Confirmation Modal -->
    <div class="modal fade" id="forceDeleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <p class="text-danger">Warning: This action cannot be undone!</p>
                            <p>Are you sure you want to permanently delete the restaurant <span
                                    id="force-delete-name-display"></span>?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="forceDeleteRestaurantForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restore button functionality
            const restoreButtons = document.querySelectorAll('.restore-btn');
            const restoreNameDisplay = document.getElementById('restore-name-display');
            const restoreForm = document.getElementById('restoreRestaurantForm');

            restoreButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const restaurantId = this.getAttribute('data-restaurant-id');
                    const restaurantName = this.getAttribute('data-restaurant-name');

                    restoreNameDisplay.textContent = restaurantName;
                    restoreForm.action = "{{ url('restaurants') }}/" + restaurantId + "/restore";
                });
            });

            // Force delete button functionality
            const forceDeleteButtons = document.querySelectorAll('.force-delete-btn');
            const forceDeleteNameDisplay = document.getElementById('force-delete-name-display');
            const forceDeleteForm = document.getElementById('forceDeleteRestaurantForm');

            forceDeleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const restaurantId = this.getAttribute('data-restaurant-id');
                    const restaurantName = this.getAttribute('data-restaurant-name');

                    forceDeleteNameDisplay.textContent = restaurantName;
                    forceDeleteForm.action = "{{ url('restaurants') }}/" + restaurantId +
                        "/force-delete";
                });
            });
        });
    </script>
@endsection
