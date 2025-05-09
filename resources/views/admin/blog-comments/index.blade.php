@extends('admin.layouts.admin')

@section('title', 'Blog Comments')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Hidden Gem /</span> Comments</h4>
        <div class="card">
            <div class="card">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <h5 class="card-header m-0 bg-transparent border-0">Blog Comments</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('blog-comments.index') }}" method="GET" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Comment content...">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class='bx bx-filter-alt me-1'></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                    @if (request()->has('post_id'))
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class='bx bx-filter me-2'></i>
                            <div>
                                Showing comments for blog post ID: {{ request('post_id') }}
                                <a href="{{ route('blog-comments.index') }}" class="ms-2 btn btn-sm btn-outline-primary">
                                    <i class='bx bx-x'></i> Clear Filter
                                </a>
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
                            <th>Comment</th>
                            <th>User</th>
                            <th>Post</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($comments as $comment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($comment->content, 50) }}</td>
                                <td>{{ $comment->user->first_name ?? '' }} {{ $comment->user->last_name ?? 'Anonymous' }}
                                </td>
                                <td>
                                    <a href="{{ route('blog-posts.show', $comment->blogPost->id) }}">
                                        {{ Str::limit($comment->blogPost->title ?? 'Unknown Post', 30) }}
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('blog-comments.update-status', $comment->id) }}" method="POST"
                                        class="status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm status-select"
                                            style="width: 100px; border: 1px solid #ddd;
                                            @if ($comment->status == 'pending') background-color: #fff8e1; color: #FF9800; font-weight: 500;
                                            @elseif($comment->status == 'rejected') background-color: #feebee; color: #F44336; font-weight: 500;
                                            @elseif($comment->status == 'accepted') background-color: #e8f5e9; color: #4CAF50; font-weight: 500; @endif">
                                            <option value="accepted" {{ $comment->status == 'accepted' ? 'selected' : '' }}
                                                style="background-color: #e8f5e9; color: #4CAF50; font-weight: 500;">
                                                Accepted</option>
                                            <option value="pending" {{ $comment->status == 'pending' ? 'selected' : '' }}
                                                style="background-color: #fff8e1; color: #FF9800; font-weight: 500;">
                                                Pending</option>
                                            <option value="rejected" {{ $comment->status == 'rejected' ? 'selected' : '' }}
                                                style="background-color: #feebee; color: #F44336; font-weight: 500;">
                                                Rejected</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $comment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('blog-comments.show', $comment->id) }}">
                                                <i class="bx bx-show me-1"></i> View
                                            </a>

                                            @if ($comment->status == 'pending')
                                                <form action="{{ route('blog-comments.accept', $comment->id) }}"
                                                    method="POST" class="dropdown-item-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx bx-check me-1"></i> Accept
                                                    </button>
                                                </form>

                                                <form action="{{ route('blog-comments.reject', $comment->id) }}"
                                                    method="POST" class="dropdown-item-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx bx-x me-1"></i> Reject
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('blog-comments.update-status', $comment->id) }}"
                                                    method="POST" class="dropdown-item-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status"
                                                        value="{{ $comment->status == 'accepted' ? 'rejected' : 'accepted' }}">
                                                    <button type="submit" class="dropdown-item">
                                                        <i
                                                            class="bx bx-{{ $comment->status == 'accepted' ? 'x' : 'check' }} me-1"></i>
                                                        {{ $comment->status == 'accepted' ? 'Reject' : 'Accept' }}
                                                    </button>
                                                </form>
                                            @endif

                                            <a class="dropdown-item text-danger delete-item" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                                data-comment-id="{{ $comment->id }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No comments found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Add pagination links below the table -->
            <div class="d-flex justify-content-center mt-4">
                {{ $comments->appends(request()->query())->links() }}
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
                            <p>Are you sure you want to delete this comment?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form id="deleteCommentForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dropdown-item-form {
            margin: 0;
            padding: 0;
        }

        .dropdown-item-form .dropdown-item {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: .532rem 1.25rem;
        }

        .dropdown-item-form .dropdown-item:hover,
        .dropdown-item-form .dropdown-item:focus {
            color: #71dd37;
            background-color: rgba(113, 221, 55, 0.1);
        }

        .dropdown-item-form .dropdown-item.text-danger:hover,
        .dropdown-item-form .dropdown-item.text-danger:focus {
            color: #ff3e1d;
            background-color: rgba(255, 62, 29, 0.1);
        }

        /* Style for status dropdown */
        .status-dropdown {
            background: none;
            border: none;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }

        .status-dropdown::after {
            margin-left: 0.5rem;
        }

        .status-dropdown:hover,
        .status-dropdown:focus {
            box-shadow: none;
        }

        .status-menu {
            min-width: 10rem;
        }

        .dropdown-item.active {
            background-color: rgba(113, 221, 55, 0.1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete functionality
            const deleteButtons = document.querySelectorAll('.delete-item');
            const deleteForm = document.getElementById('deleteCommentForm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    deleteForm.action = "{{ route('blog-comments.destroy', '') }}/" + commentId;
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
