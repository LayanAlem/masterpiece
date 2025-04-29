@extends('admin.layouts.admin')

@section('title', 'View Main Category')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Categories /</span>
        <a href="{{ route('main-categories.index') }}" class="text-muted fw-light">Main Categories</a> /
        <span>{{ $mainCategory->name }}</span>
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Category Details</h5>
            <div>
                <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                    <i class="bx bx-trash me-1"></i> Delete
                </button>
                <a href="{{ route('main-categories.edit', $mainCategory->id) }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <h6 class="fw-semibold">Name</h6>
                        <p>{{ $mainCategory->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold">Description</h6>
                        <p>{{ $mainCategory->description }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold">Created At</h6>
                        <p>{{ $mainCategory->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold">Last Updated</h6>
                        <p>{{ $mainCategory->updated_at->format('F d, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold">Status</h6>
                        <span class="badge bg-label-{{ $mainCategory->status ? 'success' : 'danger' }}">
                            {{ $mainCategory->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        @if($mainCategory->image)
                            <img src="{{ asset('storage/'.$mainCategory->image) }}"
                                alt="{{ $mainCategory->name }}"
                                class="img-fluid rounded shadow-sm border mt-5"
                                style="max-height: 250px; object-fit: cover;">
                        @else
                            <div class="border rounded p-5 mt-2">
                                <span class="badge bg-label-warning fs-6">No Image Available</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub Categories Section -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sub Categories ({{ $mainCategory->category_types_count ?? 0 }})</h5>
            <a href="{{ route('category-types.create', ['main_category_id' => $mainCategory->id]) }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Add Sub Category
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            @if(count($mainCategory->categoryTypes) > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($mainCategory->categoryTypes as $index => $subCategory)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subCategory->name }}</td>
                                <td>{{ Str::limit($subCategory->description, 30) }}</td>
                                <td>
                                    @if($subCategory->image)
                                        <img class="rounded shadow-sm border"
                                            src="{{ asset('storage/'.$subCategory->image) }}"
                                            alt="{{ $subCategory->name }}"
                                            width="60"
                                            height="60"
                                            style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-label-warning">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('category-types.edit', $subCategory->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('category-types.show', $subCategory->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>
                                            <button class="dropdown-item delete-subcategory-btn" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#deleteSubCategoryModal"
                                                    data-subcategory-id="{{ $subCategory->id }}"
                                                    data-subcategory-name="{{ $subCategory->name }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-4 text-center">
                    <div class="mb-3">
                        <i class="bx bx-folder-open text-secondary" style="font-size: 4rem;"></i>
                    </div>
                    <p class="mb-3">No sub categories found for this main category.</p>
                    <a href="{{ route('category-types.create', ['main_category_id' => $mainCategory->id]) }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add First Sub Category
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Main Category Confirmation Modal -->
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
                        <p>Are you sure you want to delete the category <strong>{{ $mainCategory->name }}</strong>?</p>
                        @if($mainCategory->category_types_count > 0)
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="bx bx-error-circle me-2 mt-1"></i>
                                    <span>This category has {{ $mainCategory->category_types_count }} sub-categories. Deleting this category will also delete all associated sub-categories.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form method="POST" action="{{ route('main-categories.destroy', $mainCategory->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Sub Category Confirmation Modal -->
<div class="modal fade" id="deleteSubCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <p>Are you sure you want to delete the sub-category <span id="subcategory-name-display"></span>?</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="deleteSubCategoryForm" method="POST" action="">
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
    const deleteSubCategoryButtons = document.querySelectorAll('.delete-subcategory-btn');
    const subCategoryNameDisplay = document.getElementById('subcategory-name-display');
    const deleteSubCategoryForm = document.getElementById('deleteSubCategoryForm');

    deleteSubCategoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const subCategoryId = this.getAttribute('data-subcategory-id');
            const subCategoryName = this.getAttribute('data-subcategory-name');

            // Display the sub-category name in the modal
            subCategoryNameDisplay.textContent = subCategoryName;

            // Set the form action to the correct route
            deleteSubCategoryForm.action = "{{ route('category-types.destroy', '') }}/" + subCategoryId;
        });
    });
});
</script>
@endsection
