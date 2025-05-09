@extends('admin.layouts.admin')

@section('title', 'Blog Posts')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Hidden Gem /</span> Blog Posts</h4>
        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Blog Posts</h5>
                    <div class="d-flex align-items-center">
                        <div>
                            <a href="{{ route('blog-posts.trashed') }}" class="btn btn-outline-secondary me-2">
                                <i class='bx bx-trash me-1'></i> Trash
                            </a>
                            <a href="{{ route('blog-posts.create') }}" class="btn btn-primary px-3 py-2">
                                <i class='bx bx-plus'></i> Add Post
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('blog-posts.index') }}" method="GET" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="user_id" class="form-label">Author</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">All Authors</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sort_by" class="form-label">Sort By</label>
                                <select class="form-select" id="sort_by" name="sort_by">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                        Date Created</option>
                                    <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title
                                    </option>
                                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Post title or content...">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-filter-alt me-1'></i> Apply Filters
                                </button>
                                <a href="{{ route('blog-posts.index') }}" class="btn btn-outline-secondary">
                                    <i class='bx bx-reset me-1'></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                    @if (request()->anyFilled(['status', 'user_id', 'search']))
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class='bx bx-filter me-2'></i>
                            <div>
                                Active filters:
                                @if (request('status'))
                                    <span class="badge bg-primary me-1">Status: {{ ucfirst(request('status')) }}</span>
                                @endif
                                @if (request('user_id') && $users->firstWhere('id', request('user_id')))
                                    <span class="badge bg-primary me-1">Author:
                                        {{ $users->firstWhere('id', request('user_id'))->first_name }}
                                        {{ $users->firstWhere('id', request('user_id'))->last_name }}</span>
                                @endif
                                @if (request('search'))
                                    <span class="badge bg-primary me-1">Search: "{{ request('search') }}"</span>
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
                            <th>Title</th>
                            <th>Image</th>
                            <th>Author</th>
                            <th>Comments</th>
                            <th>Votes</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($posts as $post)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($post->title, 30) }}</td>
                                <td>
                                    @if ($post->image)
                                        <img class="rounded shadow-sm border object-fit-cover"
                                            src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                            width="65" height="65">
                                    @else
                                        <span class="badge bg-label-warning">No Image</span>
                                    @endif
                                </td>
                                <td>{{ $post->user->first_name ?? '' }} {{ $post->user->last_name ?? 'Unknown' }}</td>
                                <td>
                                    <a href="{{ route('blog-comments.index', ['post_id' => $post->id]) }}"
                                        class="badge bg-label-info">
                                        {{ $post->comments_count ?? $post->comments->count() }}
                                    </a>
                                </td>
                                <td>{{ $post->votes_count ?? $post->votes->count() }}</td>
                                <td>
                                    <form action="{{ route('blog-posts.update-status', $post->id) }}" method="POST"
                                        class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm status-select"
                                            style="min-width: 120px; border: 1px solid #ddd;
                                            @if ($post->status == 'pending') background-color: #fff8e1; color: #FF9800; font-weight: 500;
                                            @elseif($post->status == 'rejected') background-color: #feebee; color: #F44336; font-weight: 500;
                                            @elseif($post->status == 'published') background-color: #e8f5e9; color: #4CAF50; font-weight: 500; @endif">
                                            <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}
                                                style="background-color: #e8f5e9; color: #4CAF50; font-weight: 500;">
                                                Published</option>
                                            <option value="pending" {{ $post->status == 'pending' ? 'selected' : '' }}
                                                style="background-color: #fff8e1; color: #FF9800; font-weight: 500;">Pending
                                            </option>
                                            <option value="rejected" {{ $post->status == 'rejected' ? 'selected' : '' }}
                                                style="background-color: #feebee; color: #F44336; font-weight: 500;">
                                                Rejected</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('blog-posts.edit', $post->id) }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('blog-posts.show', $post->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>
                                            <a class="dropdown-item" href="{{ route('blog.show', $post->id) }}"
                                                target="_blank">
                                                <i class="bx bx-link-external me-1"></i> View on Site
                                            </a>
                                            <button class="dropdown-item delete-btn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                data-post-id="{{ $post->id }}"
                                                data-post-title="{{ $post->title }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3">
                                    No blog posts found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Add pagination links below the table -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links() }}
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
                            <p>Are you sure you want to delete the blog post <span id="post-title-display"></span>?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deletePostForm" method="POST" action="">
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
            // Delete functionality
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const postTitleDisplay = document.getElementById('post-title-display');
            const deleteForm = document.getElementById('deletePostForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const postTitle = this.getAttribute('data-post-title');

                    postTitleDisplay.textContent = postTitle;
                    deleteForm.action = "{{ route('blog-posts.destroy', '') }}/" + postId;
                });
            });

            // Auto-submit status forms when selection changes
            const statusSelects = document.querySelectorAll('.status-select');
            statusSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });
    </script>
@endsection
