@extends('admin.layouts.admin')

@section('title', 'View Comment')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Hidden Gem / Comments /</span> View Comment
        </h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Comment Details</h5>
                        <div>
                            <a href="{{ route('blog-comments.index') }}" class="btn btn-outline-secondary">
                                <i class='bx bx-arrow-back me-1'></i> Back to Comments
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="p-4 border rounded mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-0">{{ $comment->user->first_name ?? '' }}
                                                {{ $comment->user->last_name ?? 'Anonymous' }}</h6>
                                            <small
                                                class="text-muted">{{ $comment->created_at->format('F d, Y \a\t h:i A') }}</small>
                                        </div>
                                        <div>
                                            @if ($comment->status == 'accepted')
                                                <span class="badge bg-success">Accepted</span>
                                            @elseif ($comment->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>

                                <h6>Associated Blog Post</h6>
                                <div class="border rounded p-3 mb-4">
                                    @if ($comment->blogPost)
                                        <div class="d-flex align-items-center mb-2">
                                            @if ($comment->blogPost->image)
                                                <img src="{{ asset('storage/' . $comment->blogPost->image) }}"
                                                    alt="{{ $comment->blogPost->title }}" class="me-3 rounded"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h5 class="mb-1">{{ $comment->blogPost->title }}</h5>
                                                <small class="text-muted">
                                                    <i class='bx bx-calendar me-1'></i>
                                                    {{ $comment->blogPost->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        <p>{{ Str::limit(strip_tags($comment->blogPost->content), 200) }}</p>
                                        <div class="mt-2">
                                            <a href="{{ route('blog-posts.show', $comment->blogPost->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class='bx bx-show me-1'></i> View Post
                                            </a>
                                            <a href="{{ route('blog.show', $comment->blogPost->id) }}"
                                                class="btn btn-sm btn-outline-secondary" target="_blank">
                                                <i class='bx bx-link-external me-1'></i> View on Site
                                            </a>
                                        </div>
                                    @else
                                        <p class="mb-0 text-muted">The associated blog post is not available or has been
                                            deleted.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">Moderation Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <!-- Status Update Dropdown -->
                                            <div class="mb-3">
                                                <label for="commentStatus" class="form-label">Update Status</label>
                                                <form action="{{ route('blog-comments.update-status', $comment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group">
                                                        <select class="form-select" id="commentStatus" name="status">
                                                            <option value="pending"
                                                                {{ $comment->status == 'pending' ? 'selected' : '' }}>
                                                                Pending</option>
                                                            <option value="accepted"
                                                                {{ $comment->status == 'accepted' ? 'selected' : '' }}>
                                                                Accepted</option>
                                                            <option value="rejected"
                                                                {{ $comment->status == 'rejected' ? 'selected' : '' }}>
                                                                Rejected</option>
                                                        </select>
                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="divider my-3">
                                                <div class="divider-text">Quick Actions</div>
                                            </div>

                                            @if ($comment->status != 'accepted')
                                                <form action="{{ route('blog-comments.accept', $comment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                                        <i class='bx bx-check-circle me-1'></i> Accept Comment
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($comment->status != 'rejected')
                                                <form action="{{ route('blog-comments.reject', $comment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger w-100 mb-2">
                                                        <i class='bx bx-x-circle me-1'></i> Reject Comment
                                                    </button>
                                                </form>
                                            @endif

                                            <button type="button" class="btn btn-outline-danger w-100"
                                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                                <i class='bx bx-trash me-1'></i> Delete Comment
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Information Card -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">User Information</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($comment->user)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar me-3">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ substr($comment->user->first_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $comment->user->first_name }}
                                                        {{ $comment->user->last_name }}</h6>
                                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <p class="mb-1"><strong>Joined:</strong>
                                                    {{ $comment->user->created_at->format('F d, Y') }}</p>
                                            </div>
                                            <a href="{{ route('users.show', $comment->user->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class='bx bx-user me-1'></i> View Profile
                                            </a>
                                        @else
                                            <p class="text-center mb-0">User information not available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
                    <div class="row">
                        <div class="col mb-3">
                            <p>Are you sure you want to delete this comment?</p>
                            <p class="text-muted mb-0">This action cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <form action="{{ route('blog-comments.destroy', $comment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
