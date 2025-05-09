@extends('public.layouts.main')
@section('title', $post->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/hiddenGem.css') }}">
    <style>
        .blog-detail {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .blog-detail:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: rgba(121, 53, 9, 0.1);
        }

        .blog-header {
            margin-bottom: 20px;
        }

        .blog-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .blog-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .blog-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            border: 2px solid #f7f1e5;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-image {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-detail:hover .blog-image img {
            transform: scale(1.03);
        }

        .blog-content {
            line-height: 1.8;
            color: var(--dark);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .blog-location {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #793509;
            transition: all 0.3s ease;
        }

        .blog-location:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .blog-location i {
            color: #793509;
            margin-right: 8px;
        }

        .blog-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .blog-share {
            display: flex;
            align-items: center;
        }

        .blog-share span {
            margin-right: 15px;
            font-weight: 500;
        }

        .blog-share a {
            margin-right: 15px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #793509;
            transition: all 0.3s;
            text-decoration: none;
        }

        .blog-share a:hover {
            background-color: #793509;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(121, 53, 9, 0.2);
        }

        .blog-vote {
            display: flex;
            align-items: center;
        }

        .upvote-btn {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #6c757d;
            padding: 8px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .upvote-btn:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .upvote-btn i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .upvote-btn:hover i {
            transform: translateY(-2px);
        }

        .voted,
        .upvote-btn.voted {
            color: #793509;
            border-color: #793509;
            background-color: rgba(121, 53, 9, 0.05);
        }

        .upvote-btn.voted:hover {
            background-color: rgba(121, 53, 9, 0.1);
        }

        /* Heart upvote styling */
        .heart-upvote {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            color: #999;
            font-size: 1rem;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .heart-upvote:hover {
            transform: scale(1.1);
        }

        .heart-upvote i {
            margin-right: 5px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .heart-upvote.voted {
            color: #793509;
        }

        .heart-upvote.voted i {
            animation: heartBeat 0.3s ease-in-out;
        }

        @keyframes heartBeat {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Comments section */
        .comments-section {
            margin-top: 40px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .comments-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: rgba(121, 53, 9, 0.1);
        }

        .comments-heading {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .comment-list {
            margin-top: 30px;
        }

        .comment-item {
            display: flex;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            transform: translateX(5px);
        }

        .comment-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
            border: 2px solid #f7f1e5;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .comment-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .comment-content {
            flex-grow: 1;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .comment-author {
            font-weight: 600;
            color: #2d2424;
        }

        .comment-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .comment-text {
            line-height: 1.6;
            color: #555;
        }

        /* Related posts */
        .related-heading {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
            position: relative;
            padding-bottom: 10px;
        }

        .related-heading:after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: #793509;
            border-radius: 3px;
        }

        .related-post {
            display: flex;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
            border: 1px solid rgba(0, 0, 0, 0.05);
            text-decoration: none !important;
        }

        .related-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: rgba(121, 53, 9, 0.1);
        }

        .related-image {
            width: 120px;
            height: 100px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .related-post:hover .related-image img {
            transform: scale(1.05);
        }

        .related-content {
            padding: 15px;
            flex-grow: 1;
        }

        .related-title {
            font-weight: 600;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #2d2424;
            transition: color 0.3s ease;
        }

        .related-post:hover .related-title {
            color: #793509;
        }

        .related-meta {
            display: flex;
            justify-content: space-between;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .blog-author-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .blog-author-actions .btn {
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .blog-author-actions .btn i {
            margin-right: 6px;
        }

        .blog-author-actions .btn-primary {
            background-color: #793509 !important;
            border-color: #793509 !important;
            color: white;
        }

        .blog-author-actions .btn-primary:hover {
            background-color: #5e2907 !important;
            border-color: #5e2907 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
        }

        .blog-author-actions .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2);
        }

        /* Comment modal styles */
        .modal-header {
            border-bottom: 1px solid #eee;
            padding: 1.25rem 1.5rem;
            background-color: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: #2d2424;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-footer {
            border-top: 1px solid #eee;
            padding: 1.25rem 1.5rem;
            border-radius: 0 0 12px 12px;
        }

        .btn-add-comment {
            background-color: #793509;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-add-comment:hover {
            background-color: #5e2907;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
        }

        .modal-backdrop {
            z-index: 1040;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .btn-comment {
            background-color: #793509;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-comment i {
            margin-right: 8px;
        }

        .btn-comment:hover {
            background-color: #5e2907;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
        }

        /* Center the modal in the page */
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
            pointer-events: auto;
            position: relative;
            z-index: 1060;
        }

        /* Ensure modal appears over other elements */
        .modal {
            z-index: 1050;
        }

        /* Ensure modal content is clickable */
        .modal-content {
            position: relative;
            z-index: 1061;
            pointer-events: auto;
        }

        /* Form control styling */
        .form-control:focus {
            border-color: #793509;
            box-shadow: 0 0 0 0.2rem rgba(121, 53, 9, 0.15);
        }

        /* Back to all hidden gems button */
        .btn-outline-primary {
            border-color: #793509 !important;
            color: #793509 !important;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #793509 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(121, 53, 9, 0.2);
        }

        /* Badge styles */
        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .badge.bg-success {
            background-color: #198754 !important;
            color: white !important;
        }

        /* Competition rules styling */
        .sidebar {
            margin-bottom: 30px;
        }

        .prizes-list {
            margin-top: 12px;
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 6px;
            border-left: 3px solid #793509;
        }

        .prize-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .prize-item span:first-child {
            color: #6c757d;
        }

        .prize-item span:last-child {
            font-weight: 600;
            color: #793509;
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <!-- Main content area -->
                <div class="col-lg-8">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="blog-detail">
                        <div class="blog-header">
                            <h1 class="blog-title">{{ $post->title }}</h1>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="author-avatar me-3">
                                        @if ($post->user->profile_image)
                                            <img src="{{ Storage::url($post->user->profile_image) }}"
                                                alt="{{ $post->user->first_name }} {{ $post->user->last_name }}">
                                        @else
                                            <img src="/api/placeholder/100/100"
                                                alt="{{ $post->user->first_name }} {{ $post->user->last_name }}">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="author-name">{{ $post->user->first_name }} {{ $post->user->last_name }}
                                        </div>
                                        <div class="entry-date">Posted {{ $post->created_at->format('F d, Y') }}</div>
                                    </div>
                                </div>

                                @if ($post->status !== 'published')
                                    <div class="badge bg-warning text-dark">Pending Approval</div>
                                @endif

                                @if ($post->is_winner)
                                    <div class="badge bg-success">Winner</div>
                                @endif
                            </div>
                        </div>

                        <div class="blog-image">
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                            @else
                                <img src="/api/placeholder/800/600" alt="{{ $post->title }}">
                            @endif
                        </div>

                        <div class="blog-content">
                            {!! nl2br(e($post->content)) !!}
                        </div>

                        <div class="blog-location">
                            <i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> {{ $post->location }}
                        </div>

                        <div class="blog-actions">
                            <div class="blog-share">
                                <span class="me-2">Share:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->id)) }}"
                                    target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->id)) }}&text={{ urlencode($post->title) }}"
                                    target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->id)) }}&title={{ urlencode($post->title) }}"
                                    target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(route('blog.show', $post->id)) }}&media={{ urlencode($post->image) }}&description={{ urlencode($post->title) }}"
                                    target="_blank"><i class="fab fa-pinterest-p"></i></a>
                            </div>

                            <div class="blog-vote d-flex align-items-center">
                                {{-- <div class="heart-upvote me-3" data-post-id="{{ $post->id }}">
                                    <i class="far fa-heart"></i> <span class="heart-count" data-post-id="{{ $post->id }}">{{ $post->vote_count }}</span>
                                </div> --}}

                                <button class="upvote-btn" id="upvoteBtn" data-post-id="{{ $post->id }}">
                                    <i class="fas fa-arrow-up upvote-icon"></i> Upvote <span class="ms-1" id="voteCount"
                                        data-post-id="{{ $post->id }}">{{ $post->vote_count }}</span>
                                </button>
                            </div>
                        </div>

                        @if (Auth::check() && Auth::id() == $post->user_id)
                            <div class="blog-author-actions">
                                <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit Post
                                </a>
                                <form action="{{ route('blog.destroy', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="comments-heading mb-0">Comments ({{ $post->comments->count() }})</h3>

                            @auth
                                <button type="button" class="btn-comment" data-bs-toggle="modal"
                                    data-bs-target="#commentModal">
                                    <i class="far fa-comment"></i> Add Comment
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-comment">
                                    <i class="far fa-comment"></i> Login to Comment
                                </a>
                            @endauth
                        </div>

                        <div class="comment-list">
                            @if ($post->comments->count() > 0)
                                @foreach ($post->comments as $comment)
                                    <div class="comment-item">
                                        <div class="comment-avatar">
                                            @if ($comment->user->profile_image)
                                                <img src="{{ Storage::url($comment->user->profile_image) }}"
                                                    alt="{{ $comment->user->first_name }} {{ $comment->user->last_name }}">
                                            @else
                                                <img src="/api/placeholder/100/100"
                                                    alt="{{ $comment->user->first_name }} {{ $comment->user->last_name }}">
                                            @endif
                                        </div>
                                        <div class="comment-content">
                                            <div class="comment-header">
                                                <div class="comment-author">{{ $comment->user->first_name }}
                                                    {{ $comment->user->last_name }}</div>
                                                <div class="comment-date">{{ $comment->created_at->diffForHumans() }}</div>
                                            </div>
                                            <div class="comment-text">
                                                {{ $comment->comment }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-muted">No comments yet. Be the first to comment!</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Related Posts -->
                    <div class="sidebar">
                        <h3 class="related-heading">Related Hidden Gems</h3>

                        @if ($relatedPosts->count() > 0)
                            @foreach ($relatedPosts as $relatedPost)
                                <a href="{{ route('blog.show', $relatedPost->id) }}" class="text-decoration-none">
                                    <div class="related-post">
                                        <div class="related-image">
                                            @if ($relatedPost->image)
                                                <img src="{{ asset($relatedPost->image) }}"
                                                    alt="{{ $relatedPost->title }}">
                                            @else
                                                <img src="/api/placeholder/800/600" alt="{{ $relatedPost->title }}">
                                            @endif
                                        </div>
                                        <div class="related-content">
                                            <h5 class="related-title">{{ $relatedPost->title }}</h5>
                                            <div class="related-meta">
                                                <div><i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $relatedPost->location }}</div>
                                                <div><i class="fas fa-arrow-up me-1"></i> {{ $relatedPost->vote_count }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="text-muted">No related posts found.</div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('blog.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-1"></i> Back to All Hidden Gems
                            </a>
                        </div>
                    </div>

                    <!-- Competition Rules -->
                    <div class="sidebar mt-4">
                        <h3 class="sidebar-title">Competition Rules</h3>
                        <ul class="rules-list">
                            <li>
                                <i class="far fa-calendar-alt"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Submission Deadline</div>
                                    <div class="rule-text">December 15, 2025</div>
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-trophy"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Prizes</div>
                                    <div class="prizes-list">
                                        <div class="prize-item">
                                            <span>1st Place:</span>
                                            <span>$2000</span>
                                        </div>
                                        <div class="prize-item">
                                            <span>2nd Place:</span>
                                            <span>$1000</span>
                                        </div>
                                        <div class="prize-item">
                                            <span>3rd Place:</span>
                                            <span>$500</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment Modal -->
    @auth
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('blog.comment.store', $post->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="content" class="form-label">Your Comment</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4"
                                    required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-add-comment">Submit Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle upvote functionality
                const upvoteBtn = document.getElementById('upvoteBtn');
                const upvoteIcon = document.querySelector('.upvote-icon');
                const voteCount = document.getElementById('voteCount');
                const heartUpvote = document.querySelector('.heart-upvote');
                const heartIcon = heartUpvote ? heartUpvote.querySelector('i') : null;

                if (upvoteBtn) {
                    const postId = upvoteBtn.getAttribute('data-post-id');

                    // Check if user has already voted
                    fetch(`{{ url('/VisitJo/hiddengem') }}/${postId}/check-vote`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.voted) {
                                upvoteIcon.classList.add('voted');
                                upvoteBtn.classList.add('voted');

                                // Also update heart icon
                                if (heartIcon) {
                                    heartUpvote.classList.add('voted');
                                    heartIcon.classList.remove('far');
                                    heartIcon.classList.add('fas');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error checking vote status:', error);
                        });

                    // Handle click event on arrow button
                    upvoteBtn.addEventListener('click', function() {
                        handleVote(postId);
                    });

                    // Handle click event on heart button
                    if (heartUpvote) {
                        heartUpvote.addEventListener('click', function() {
                            handleVote(postId);
                        });
                    }

                    // Unified vote handling function
                    function handleVote(postId) {
                        fetch(`{{ url('/VisitJo/hiddengem') }}/${postId}/vote`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                // Empty body to avoid issues
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (response.status === 401) {
                                    // User not authenticated, redirect to login
                                    window.location.href = '{{ route('login') }}';
                                    throw new Error('Not authenticated');
                                }
                                if (!response.ok) {
                                    throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update vote counts
                                    document.querySelectorAll(`[data-post-id="${postId}"].heart-count, #voteCount`)
                                        .forEach(el => el.textContent = data.voteCount);

                                    // Update UI for arrow upvote
                                    if (data.voted) {
                                        upvoteIcon.classList.add('voted');
                                        upvoteBtn.classList.add('voted');
                                    } else {
                                        upvoteIcon.classList.remove('voted');
                                        upvoteBtn.classList.remove('voted');
                                    }

                                    // Update UI for heart upvote
                                    if (heartUpvote) {
                                        if (data.voted) {
                                            heartUpvote.classList.add('voted');
                                            heartIcon.classList.remove('far');
                                            heartIcon.classList.add('fas');
                                        } else {
                                            heartUpvote.classList.remove('voted');
                                            heartIcon.classList.remove('fas');
                                            heartIcon.classList.add('far');
                                        }
                                    }
                                } else if (data.message) {
                                    console.error('Vote failed:', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                }

                // Show comment modal if there are validation errors
                @if ($errors->has('content'))
                    const commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
                    commentModal.show();
                @endif
            });
        </script>
    @endpush
@endsection
