@extends('admin.layouts.admin')

@section('title', 'Main Categories')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Categories /</span> Main Categories</h4>

        <!-- Filters and Search Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('main-categories.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" placeholder="Search by name"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label class="form-label">Sub Categories</label>
                        <select class="form-select" name="has_subs">
                            <option value="">All</option>
                            <option value="with" {{ request('has_subs') == 'with' ? 'selected' : '' }}>With Sub Categories
                            </option>
                            <option value="without" {{ request('has_subs') == 'without' ? 'selected' : '' }}>Without Sub
                                Categories</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('main-categories.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center">
                    <h5 class="card-title m-0">Main Categories</h5>
                    <span
                        class="badge bg-label-primary ms-2">{{ $mainCategories instanceof \Illuminate\Pagination\LengthAwarePaginator ? $mainCategories->total() : count($mainCategories) }}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('main-categories.trashed') }}" class="btn btn-outline-secondary">
                        <i class='bx bx-trash'></i> Trash
                    </a>
                    <a href="{{ route('main-categories.create') }}" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Add Category
                    </a>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Sub Categories</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($mainCategories as $category)
                            <tr>
                                <td>{{ $mainCategories instanceof \Illuminate\Pagination\LengthAwarePaginator ? $mainCategories->firstItem() + $loop->index : $loop->iteration }}
                                </td>
                                <td>
                                    <a href="{{ route('main-categories.show', $category->id) }}"
                                        class="fw-semibold text-body">
                                        {{ $category->name }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($category->description, 30) }}</td>
                                <td>
                                    @if ($category->image)
                                        <img class="rounded shadow-sm border object-fit-cover"
                                            src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                            width="65" height="65">
                                    @else
                                        <span class="badge bg-label-warning">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('category-types.index', ['main_category' => $category->id]) }}"
                                        class="badge bg-label-secondary px-3 py-2">
                                        {{ $category->category_types_count ?? 0 }}
                                    </a>
                                </td>
                                <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('main-categories.edit', $category->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('main-categories.show', $category->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>
                                            <button class="dropdown-item delete-btn" type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal" data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class='bx bx-folder-open text-secondary mb-2' style="font-size: 2rem"></i>
                                        <h6 class="mb-1 text-secondary">No categories found</h6>
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
            @if ($mainCategories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $mainCategories->firstItem() ?? 0 }} to {{ $mainCategories->lastItem() ?? 0 }} of
                            {{ $mainCategories->total() }} entries
                        </div>
                        <div>
                            {{ $mainCategories->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
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
                            <p>Are you sure you want to delete the category <span id="category-name-display"
                                    class="fw-bold"></span>?</p>
                            <p class="text-warning small mb-0"><i class='bx bx-info-circle me-1'></i> This will move the
                                category to trash. You can restore it later.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteCategoryForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const categoryNameDisplay = document.getElementById('category-name-display');
            const deleteForm = document.getElementById('deleteCategoryForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-category-id');
                    const categoryName = this.getAttribute('data-category-name');

                    // Display the category name in the modal
                    categoryNameDisplay.textContent = categoryName;

                    // Set the form action to the correct route
                    deleteForm.action = "{{ route('main-categories.destroy', '') }}/" + categoryId;
                });
            });
        });
    </script>
@endsection
