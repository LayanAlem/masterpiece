@extends('admin.layouts.admin')

@section('title', 'View Blog Post')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Hidden Gem / Blog Posts /</span> View Post
        </h4>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Post Details</h5>
                        <div class="btn-group">
                            <a href="{{ route('blog-posts.edit', $post->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </a>
                            <a href="{{ route('blog.show', $post->id) }}" target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-link-external me-1"></i> View on Site
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($post->image)
                            <div class="mb-4 text-center">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                    class="img-fluid rounded shadow" style="max-height: 300px;">
                            </div>
                        @endif

                        <div class="mb-3">
                            <h1>{{ $post->title }}</h1>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class='bx bx-calendar me-1'></i> {{ $post->created_at->format('F d, Y') }}
                                <i class='bx bx-user ms-3 me-1'></i> {{ $post->user->first_name ?? '' }}
                                {{ $post->user->last_name ?? 'Unknown' }}

                                <div class="ms-3 d-flex align-items-center">
                                    <span class="me-2 text-nowrap"><strong>Status:</strong></span>
                                    <form action="{{ route('blog-posts.update-status', $post->id) }}" method="POST"
                                        class="d-flex align-items-center" id="status-form">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" id="status"
                                            class="form-select form-select-sm status-select"
                                            style="width: auto; min-width: 130px; border: 2px solid #ddd;">
                                            <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>
                                                Published</option>
                                            <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Draft
                                            </option>
                                            <option value="pending" {{ $post->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-3">
                                    <i class='bx bx-comment me-1'></i> {{ $post->comments->count() }} Comments
                                </div>
                                <div>
                                    <i class='bx bx-like me-1'></i> {{ $post->votes->count() }} Votes
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="blog-content">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Comments Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Comments ({{ $post->comments->count() }})</h5>
                        <a href="{{ route('blog-comments.index', ['post_id' => $post->id]) }}"
                            class="btn btn-sm btn-outline-primary">
                            All Comments
                        </a>
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($post->comments->take(5) as $comment)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <strong>{{ $comment->user->first_name ?? '' }}
                                                {{ $comment->user->last_name ?? 'Anonymous' }}</strong>
                                            <small
                                                class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            @if ($comment->status == 'approved')
                                                <span class="badge bg-label-success">Approved</span>
                                            @elseif($comment->status == 'pending')
                                                <span class="badge bg-label-warning">Pending</span>
                                            @else
                                                <span class="badge bg-label-danger">Rejected</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($comment->content, 100) }}</p>
                                    <div class="d-flex">
                                        @if ($comment->status == 'pending')
                                            <form action="{{ route('blog-comments.approve', $comment->id) }}"
                                                method="POST" class="me-1">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-xs btn-outline-success btn-sm">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('blog-comments.reject', $comment->id) }}" method="POST"
                                                class="me-1">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-xs btn-outline-danger btn-sm">
                                                    <i class='bx bx-x'></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('blog-comments.show', $comment->id) }}"
                                            class="btn btn-xs btn-outline-info btn-sm">
                                            <i class='bx bx-show'></i>
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item px-0">
                                    <p class="text-center mb-0">No comments yet</p>
                                </li>
                            @endforelse
                        </ul>

                        @if ($post->comments->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('blog-comments.index', ['post_id' => $post->id]) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    View All Comments ({{ $post->comments->count() }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Author Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Author Information</h5>
                    </div>
                    <div class="card-body">
                        @if ($post->user)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ substr($post->user->first_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $post->user->first_name }} {{ $post->user->last_name }}</h6>
                                    <small class="text-muted">{{ $post->user->email }}</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1"><strong>Total Posts:</strong> {{ $post->user->blogPosts->count() }}</p>
                                <p class="mb-0"><strong>Joined:</strong> {{ $post->user->created_at->format('F d, Y') }}
                                </p>
                            </div>
                            <a href="{{ route('users.show', $post->user->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class='bx bx-user me-1'></i> View Profile
                            </a>
                        @else
                            <p class="text-center mb-0">Author information not available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <style>
        .blog-content {
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .blog-content img {
            max-width: 100%;
            height: auto;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when status dropdown changes
            const statusSelect = document.querySelector('.status-select');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    document.getElementById('status-form').submit();
                });
            }
        });
    </script>
@endsection
