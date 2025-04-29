@extends('admin.layouts.admin')

@section('title', 'Category Types')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Categories /</span> Category Types
        </h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters and Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('category-types.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" placeholder="Search by name"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Main Category</label>
                        <select class="form-select" name="main_category">
                            <option value="">All Categories</option>
                            @foreach ($mainCategories ?? [] as $mainCategory)
                                <option value="{{ $mainCategory->id }}"
                                    {{ request('main_category') == $mainCategory->id ? 'selected' : '' }}>
                                    {{ $mainCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" name="sort">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                            </option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Activities</label>
                        <select class="form-select" name="has_activities">
                            <option value="">All</option>
                            <option value="with" {{ request('has_activities') == 'with' ? 'selected' : '' }}>With
                                Activities</option>
                            <option value="without" {{ request('has_activities') == 'without' ? 'selected' : '' }}>Without
                                Activities</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('category-types.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Category Types</h5>
                    <span class="badge bg-label-primary ms-2">{{ $categoryTypes->total() ?? count($categoryTypes) }}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('category-types.trashed') }}" class="btn btn-outline-secondary">
                        <i class='bx bx-trash'></i> Trash
                    </a>
                    <a href="{{ route('category-types.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Add New Category Type
                    </a>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Main Category</th>
                            <th>Description</th>
                            <th>Activities</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($categoryTypes as $categoryType)
                            <tr>
                                <td>{{ $categoryTypes->firstItem() + $loop->index ?? $loop->iteration }}</td>
                                <td>
                                    @if ($categoryType->image)
                                        <img src="{{ asset('storage/' . $categoryType->image) }}"
                                            alt="{{ $categoryType->name }}"
                                            class="rounded shadow-sm border object-fit-cover" width="52" height="52"
                                            style="object-fit: cover;">
                                    @else
                                        <div class="avatar bg-label-primary">
                                            <span
                                                class="avatar-initial rounded">{{ substr($categoryType->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('category-types.show', $categoryType->id) }}"
                                        class="fw-semibold text-body">
                                        {{ $categoryType->name }}
                                    </a>
                                </td>
                                <td>
                                    @if ($categoryType->mainCategory)
                                        <a href="{{ route('main-categories.show', $categoryType->mainCategory->id) }}"
                                            class="text-body">
                                            {{ $categoryType->mainCategory->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($categoryType->description, 30) }}</td>
                                <td>
                                    <a href="{{ route('activities.index', ['category_type' => $categoryType->id]) }}"
                                        class="badge bg-label-secondary px-3 py-2">
                                        {{ $categoryType->activities_count ?? ($categoryType->activities->count() ?? 0) }}
                                    </a>
                                </td>
                                <td>{{ $categoryType->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('category-types.show', $categoryType->id) }}">
                                                <i class="bx bx-show-alt me-1"></i> View
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('category-types.edit', $categoryType->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <button class="dropdown-item delete-btn" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-category-id="{{ $categoryType->id }}"
                                                data-category-name="{{ $categoryType->name }}"
                                                data-route="{{ route('category-types.destroy', $categoryType->id) }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class='bx bx-folder-open text-secondary mb-2' style="font-size: 2rem"></i>
                                        <h6 class="mb-1 text-secondary">No category types found</h6>
                                        <p class="mb-0 text-muted">Try adjusting your search or filter to find what you're
                                            looking for</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($categoryTypes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $categoryTypes->firstItem() ?? 0 }} to {{ $categoryTypes->lastItem() ?? 0 }} of
                            {{ $categoryTypes->total() }} entries
                        </div>
                        <div>
                            {{ $categoryTypes->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
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
                        <p>Are you sure you want to delete the category type
                            <span id="category-name" class="fw-bold text-danger"></span>?
                        </p>
                        <p class="text-warning small mb-0"><i class='bx bx-info-circle me-1'></i> This will move the
                            category type to trash. You can restore it later.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deleteCategoryForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button clicks
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const categoryNameSpan = document.getElementById('category-name');
            const deleteForm = document.getElementById('deleteCategoryForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const categoryName = this.getAttribute('data-category-name');

                    deleteForm.action = route;
                    categoryNameSpan.textContent = categoryName;
                });
            });
        });
    </script>
@endsection
