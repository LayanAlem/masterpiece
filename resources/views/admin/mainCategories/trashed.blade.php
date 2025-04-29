@extends('admin.layouts.admin')

@section('title', 'Trashed Main Categories')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Categories /</span> Trashed Main Categories</h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('main-categories.trashed') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="search" placeholder="Search by name"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" name="sort">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                            </option>
                            <option value="deleted_newest" {{ request('sort') == 'deleted_newest' ? 'selected' : '' }}>
                                Recently Deleted</option>
                            <option value="deleted_oldest" {{ request('sort') == 'deleted_oldest' ? 'selected' : '' }}>
                                Oldest Deleted</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('main-categories.trashed') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <a href="{{ route('main-categories.index') }}" class="btn btn-outline-primary w-100">
                            <i class='bx bx-arrow-back me-1'></i> Back to Categories
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Trashed Main Categories</h5>
                    <span
                        class="badge bg-label-danger ms-2">{{ $trashedCategories->total() ?? count($trashedCategories) }}</span>
                </div>

                @if (count($trashedCategories) > 0)
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#emptyTrashModal">
                        <i class='bx bx-trash-alt me-1'></i> Empty Trash
                    </button>
                @endif
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
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($trashedCategories as $category)
                            <tr>
                                <td>{{ $trashedCategories->firstItem() + $loop->index ?? $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
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
                                    <span
                                        class="badge bg-label-secondary">{{ $category->category_types_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="text-danger">{{ $category->deleted_at->format('Y-m-d H:i') }}</span><br>
                                    <small class="text-muted">{{ $category->deleted_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <form action="{{ route('main-categories.restore', $category->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary me-2"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Restore">
                                                <i class='bx bx-revision'></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-outline-danger delete-btn" data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmModal" data-category-id="{{ $category->id }}"
                                            data-category-name="{{ $category->name }}">
                                            <i class='bx bx-trash-alt'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class='bx bx-check-circle text-success mb-2' style="font-size: 2rem"></i>
                                        <h6 class="mb-1">Trash is empty</h6>
                                        <p class="mb-0 text-muted">All your deleted categories will appear here</p>
                                        <a href="{{ route('main-categories.index') }}" class="btn btn-primary mt-3">
                                            <i class='bx bx-arrow-back me-1'></i> Back to Categories
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($trashedCategories instanceof \Illuminate\Pagination\LengthAwarePaginator && $trashedCategories->count() > 0)
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $trashedCategories->firstItem() ?? 0 }} to
                            {{ $trashedCategories->lastItem() ?? 0 }} of {{ $trashedCategories->total() }} entries
                        </div>
                        <div>
                            {{ $trashedCategories->withQueryString()->links() }}
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
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class='bx bx-error-circle text-danger' style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="text-center mb-3">Are you absolutely sure?</h4>
                    <p class="text-center">This will permanently delete <span id="category-name-display"
                            class="fw-bold text-danger"></span> from the system.</p>
                    <p class="text-center text-danger fw-semibold">This action CANNOT be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteCategoryForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Yes, Permanently Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty Trash Confirmation Modal -->
    <div class="modal fade" id="emptyTrashModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Empty Trash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class='bx bx-trash-alt text-danger' style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="text-center mb-3">Empty the entire trash?</h4>
                    <p class="text-center">You are about to permanently delete <span
                            class="fw-bold">{{ $trashedCategories->count() }} categories</span> from the system.</p>
                    <p class="text-center text-danger fw-semibold">This action CANNOT be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form action="{{ route('main-categories.empty-trash') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Yes, Empty Trash
                        </button>
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
                    deleteForm.action = "{{ route('main-categories.forceDelete', '') }}/" +
                        categoryId;
                });
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
