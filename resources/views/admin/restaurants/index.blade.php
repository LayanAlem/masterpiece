@extends('admin.layouts.admin')

@section('title', 'Restaurants')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dining /</span> Restaurants</h4>
        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Restaurants</h5>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <a href="{{ request()->fullUrlWithQuery(['featured' => 'true']) }}"
                                class="btn btn-outline-primary {{ request('featured') == 'true' ? 'active' : '' }}">
                                <i class='bx bx-star me-1'></i> Featured Only
                            </a>
                            <a href="{{ route('restaurants.index') }}"
                                class="btn btn-outline-secondary {{ !request('featured') ? 'active' : '' }}">
                                <i class='bx bx-list-ul me-1'></i> All
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('restaurants.trashed') }}" class="btn btn-outline-secondary me-2">
                                <i class='bx bx-trash me-1'></i> Trash
                            </a>
                            <a href="{{ route('restaurants.create') }}" class="btn btn-primary px-3 py-2">
                                <i class='bx bx-plus'></i> Add Restaurant
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('restaurants.index') }}" method="GET" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="cuisine_type" class="form-label">Cuisine Type</label>
                                <select class="form-select" id="cuisine_type" name="cuisine_type">
                                    <option value="">All Cuisines</option>
                                    @foreach ($cuisineTypes as $cuisine)
                                        <option value="{{ $cuisine }}"
                                            {{ request('cuisine_type') == $cuisine ? 'selected' : '' }}>
                                            {{ $cuisine }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="price_range" class="form-label">Price Range</label>
                                <select class="form-select" id="price_range" name="price_range">
                                    <option value="">All Prices</option>
                                    <option value="$" {{ request('price_range') == '$' ? 'selected' : '' }}>$</option>
                                    <option value="$$" {{ request('price_range') == '$$' ? 'selected' : '' }}>$$
                                    </option>
                                    <option value="$$$" {{ request('price_range') == '$$$' ? 'selected' : '' }}>$$$
                                    </option>
                                    <option value="$$$$" {{ request('price_range') == '$$$$' ? 'selected' : '' }}>$$$$
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Restaurant name...">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-filter-alt me-1'></i> Apply Filters
                                </button>
                                <a href="{{ route('restaurants.index') }}" class="btn btn-outline-secondary">
                                    <i class='bx bx-reset me-1'></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                    @if (request()->anyFilled(['cuisine_type', 'price_range', 'status', 'search', 'featured']))
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class='bx bx-filter me-2'></i>
                            <div>
                                Active filters:
                                @if (request('cuisine_type'))
                                    <span class="badge bg-primary me-1">Cuisine: {{ request('cuisine_type') }}</span>
                                @endif
                                @if (request('price_range'))
                                    <span class="badge bg-primary me-1">Price: {{ request('price_range') }}</span>
                                @endif
                                @if (request('status'))
                                    <span class="badge bg-primary me-1">Status: {{ ucfirst(request('status')) }}</span>
                                @endif
                                @if (request('search'))
                                    <span class="badge bg-primary me-1">Search: "{{ request('search') }}"</span>
                                @endif
                                @if (request('featured') == 'true')
                                    <span class="badge bg-primary me-1">Featured Only</span>
                                @endif
                            </div>
                        </div>
                    @endif
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
                            <th>Price Range</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($restaurants as $restaurant)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ Str::limit($restaurant->location, 30) }}</td>
                                <td>
                                    @if ($restaurant->image)
                                        <img class="rounded shadow-sm border object-fit-cover"
                                            src="{{ asset('storage/' . $restaurant->image) }}"
                                            alt="{{ $restaurant->name }}" width="65" height="65">
                                    @else
                                        <span class="badge bg-label-warning">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $restaurant->cuisine_type }}</td>
                                <td>{{ $restaurant->price_range }}</td>
                                <td>
                                    @if ($restaurant->is_active)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($restaurant->isCurrentlyFeatured())
                                        <span class="badge bg-label-primary">Featured</span>
                                    @else
                                        <span class="badge bg-label-secondary">Not Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown ms-3">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('restaurants.edit', $restaurant->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('restaurants.show', $restaurant->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>
                                            @if (!$restaurant->isCurrentlyFeatured())
                                                <button class="dropdown-item add-feature-btn" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#addFeatureModal"
                                                    data-restaurant-id="{{ $restaurant->id }}"
                                                    data-restaurant-name="{{ $restaurant->name }}">
                                                    <i class="bx bx-star me-1"></i> Add to Featured
                                                </button>
                                            @else
                                                <button class="dropdown-item remove-feature-btn" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#removeFeatureModal"
                                                    data-restaurant-id="{{ $restaurant->id }}"
                                                    data-restaurant-name="{{ $restaurant->name }}">
                                                    <i class="bx bx-star-half me-1"></i> Remove from Featured
                                                </button>
                                            @endif
                                            <button class="dropdown-item delete-btn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                data-restaurant-id="{{ $restaurant->id }}"
                                                data-restaurant-name="{{ $restaurant->name }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">
                                    @if (request('featured') == 'true')
                                        No featured restaurants found
                                    @else
                                        No restaurants found
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Add pagination links below the table -->
            <div class="d-flex justify-content-center mt-4">
                {{ $restaurants->appends(request()->query())->links() }}
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
                    <div class="row">
                        <div class="col mb-3">
                            <p>Are you sure you want to delete the restaurant <span id="restaurant-name-display"></span>?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteRestaurantForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add to Featured Modal -->
    <div class="modal fade" id="addFeatureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add to Featured Restaurants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addFeatureForm" method="POST" action="{{ route('restaurants.feature.add') }}">
                    @csrf
                    <input type="hidden" name="restaurant_id" id="feature-restaurant-id">
                    <div class="modal-body">
                        <p>You are about to feature <strong id="feature-restaurant-name"></strong>.</p>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" checked>
                            <label class="form-check-label" for="is_active">Active Status</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Featured</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Remove from Featured Modal -->
    <div class="modal fade" id="removeFeatureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove from Featured</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove <strong id="remove-feature-restaurant-name"></strong> from featured
                        restaurants?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="removeFeatureForm" method="POST" action="{{ route('restaurants.feature.remove') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="restaurant_id" id="remove-feature-restaurant-id">
                        <button type="submit" class="btn btn-danger">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete functionality
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const restaurantNameDisplay = document.getElementById('restaurant-name-display');
            const deleteForm = document.getElementById('deleteRestaurantForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const restaurantId = this.getAttribute('data-restaurant-id');
                    const restaurantName = this.getAttribute('data-restaurant-name');

                    restaurantNameDisplay.textContent = restaurantName;
                    deleteForm.action = "{{ route('restaurants.destroy', '') }}/" + restaurantId;
                });
            });

            // Add to featured functionality
            const addFeatureButtons = document.querySelectorAll('.add-feature-btn');
            const featureRestaurantIdInput = document.getElementById('feature-restaurant-id');
            const featureRestaurantNameSpan = document.getElementById('feature-restaurant-name');

            addFeatureButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const restaurantId = this.getAttribute('data-restaurant-id');
                    const restaurantName = this.getAttribute('data-restaurant-name');

                    featureRestaurantIdInput.value = restaurantId;
                    featureRestaurantNameSpan.textContent = restaurantName;
                });
            });

            // Remove from featured functionality
            const removeFeatureButtons = document.querySelectorAll('.remove-feature-btn');
            const removeFeatureRestaurantIdInput = document.getElementById('remove-feature-restaurant-id');
            const removeFeatureRestaurantNameSpan = document.getElementById('remove-feature-restaurant-name');

            removeFeatureButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const restaurantId = this.getAttribute('data-restaurant-id');
                    const restaurantName = this.getAttribute('data-restaurant-name');

                    removeFeatureRestaurantIdInput.value = restaurantId;
                    removeFeatureRestaurantNameSpan.textContent = restaurantName;
                });
            });
        });
    </script>
@endsection
