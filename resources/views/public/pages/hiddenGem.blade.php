@extends('public.layouts.main')
@section('title', 'Hidden Gem')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/hiddenGem.css') }}">
    <style>
        /* Modal styling for the main page */
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
            pointer-events: auto;
            position: relative;
            z-index: 1060;
        }

        .modal {
            z-index: 1050;
        }

        /* Fix modal backdrop issue */
        .modal-backdrop {
            z-index: 1040;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Ensure modal content is clickable */
        .modal-content {
            position: relative;
            z-index: 1061;
            pointer-events: auto;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #eee;
            padding: 1.25rem 1.5rem;
            background-color: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: var(--dark);
        }

        .modal-body {
            padding: 1.5rem;
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

        .comment-btn {
            background: none;
            border: none;
            color: #793509;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.3s;
        }

        .comment-btn i {
            margin-right: 5px;
        }

        .comment-btn:hover {
            color: #5e2907;
            text-decoration: underline;
        }

        /* Heart upvote button styling */
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

        /* Form control styling */
        .form-control:focus {
            border-color: #793509;
            box-shadow: 0 0 0 0.2rem rgba(121, 53, 9, 0.15);
        }

        /* Add entry button styling */
        .add-entry-btn {
            background-color: #793509;
            color: white;
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .add-entry-btn:hover {
            background-color: #5e2907;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(121, 53, 9, 0.2);
        }

        /* Empty state styling */
        .empty-state-container {
            text-align: center;
            padding: 40px 0;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: 30px;
            transition: all 0.3s ease;
        }

        .empty-state-container:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .empty-state-icon {
            font-size: 3.5rem;
            color: #793509;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .empty-state-message {
            color: #6c757d;
            margin-bottom: 25px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Success modal styling */
        .success-modal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .success-modal .modal-header {
            background-color: #793509;
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
        }

        .success-modal .modal-title {
            font-weight: 600;
            color: white;
        }

        .success-modal .modal-body {
            padding: 2rem;
            text-align: center;
        }

        .success-modal .modal-footer {
            border-top: none;
            justify-content: center;
            padding: 1.5rem;
        }

        .success-modal .btn-close {
            color: white;
            filter: invert(1) brightness(200%);
        }

        .success-icon {
            font-size: 3rem;
            color: #793509;
            margin-bottom: 1rem;
        }

        .success-message {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')

    <div class="main-content">
        <div class="container">
            <div class="row">
                <!-- Main content area -->
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="page-title mb-0">Hidden Places Competition</h1>
                        <a href="{{ route('blog.create') }}" class="add-entry-btn">
                            <i class="fas fa-plus-circle"></i> Participate in Competition
                        </a>
                    </div>

                    <!-- Filter options -->
                    <div class="filter-section">
                        <div class="sort-dropdown dropdown">
                            <button class="dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ $sort === 'recent' ? 'Most Recent' : 'Most Popular' }} <i
                                    class="fas fa-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item {{ $sort === 'recent' ? 'active' : '' }}"
                                        href="{{ route('blog.index', ['sort' => 'recent']) }}">Most Recent</a></li>
                                <li><a class="dropdown-item {{ $sort === 'popular' ? 'active' : '' }}"
                                        href="{{ route('blog.index', ['sort' => 'popular']) }}">Most Popular</a></li>
                            </ul>
                        </div>
                        <div class="view-toggle btn-group">
                            <button type="button" class="btn active">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button type="button" class="btn">
                                <i class="fas fa-th-list"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Entry cards -->
                    @if ($posts->count() > 0)
                        @foreach ($posts as $post)
                            <div class="entry-card">
                                <div class="entry-image">
                                    @if ($post->image)
                                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                                    @else
                                        <img src="/api/placeholder/800/600" alt="{{ $post->title }}">
                                    @endif
                                </div>
                                <div class="entry-content">
                                    <div class="entry-header">
                                        <div>
                                            <h2 class="entry-title">{{ $post->title }}</h2>
                                            <div class="entry-author">
                                                <div class="author-avatar">
                                                    @if ($post->user->profile_image)
                                                        <img src="{{ Storage::url($post->user->profile_image) }}"
                                                            alt="{{ $post->user->first_name }} {{ $post->user->last_name }}">
                                                    @else
                                                        <img src="/api/placeholder/100/100"
                                                            alt="{{ $post->user->first_name }} {{ $post->user->last_name }}">
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="author-name">{{ $post->user->first_name }}
                                                        {{ $post->user->last_name }}</div>
                                                    <div class="entry-date">Posted {{ $post->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="upvote" data-post-id="{{ $post->id }}">
                                            <i class="fas fa-arrow-up upvote-icon"></i>
                                            <span class="upvote-count"
                                                data-post-id="{{ $post->id }}">{{ $post->vote_count }}</span>
                                        </div>
                                    </div>
                                    <p class="entry-description">
                                        {{ Str::limit($post->content, 150) }}
                                    </p>
                                    <div class="entry-footer">
                                        <div class="d-flex gap-4">
                                            <div class="heart-upvote" data-post-id="{{ $post->id }}">
                                                <i class="far fa-heart"></i> <span class="heart-count"
                                                    data-post-id="{{ $post->id }}">{{ $post->vote_count }}</span>
                                            </div>
                                            <div class="comments">
                                                <i class="far fa-comment"></i> {{ $post->comments->count() }} comments
                                            </div>
                                            <div class="location">
                                                <i class="fas fa-map-marker-alt"></i> {{ $post->location }}
                                            </div>
                                            @auth
                                                <button type="button" class="comment-btn" data-bs-toggle="modal"
                                                    data-bs-target="#commentModal{{ $post->id }}">
                                                    <i class="far fa-comment-dots"></i> Add Comment
                                                </button>
                                            @endauth
                                        </div>
                                        <a href="{{ route('blog.show', $post->id) }}" class="read-more">Read More →</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Comment Modal for each post -->
                            @auth
                                <div class="modal fade" id="commentModal{{ $post->id }}" tabindex="-1"
                                    aria-labelledby="commentModalLabel{{ $post->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('blog.comment.store', $post->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="commentModalLabel{{ $post->id }}">Comment
                                                        on "{{ $post->title }}"</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="content{{ $post->id }}" class="form-label">Your
                                                            Comment</label>
                                                        <textarea class="form-control" id="content{{ $post->id }}" name="content" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn-add-comment">Submit Comment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        @endforeach

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->appends(['sort' => $sort])->links() }}
                        </div>
                    @else
                        <!-- Empty state with styled container instead of alert -->
                        <div class="empty-state-container">
                            <i class="fas fa-map-marked-alt empty-state-icon"></i>
                            <h3 class="empty-state-title">No Hidden Gems Yet</h3>
                            <p class="empty-state-message">Be the first to share your hidden gem and participate in our
                                competition!</p>
                            <a href="{{ route('blog.create') }}" class="add-entry-btn">
                                <i class="fas fa-plus-circle"></i> Share Your First Hidden Gem
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
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
                            <li>
                                <i class="fas fa-clipboard-list"></i>
                                <div class="rule-detail">
                                    <div class="rule-title">Requirements</div>
                                    <ul class="requirement-list">
                                        <li>
                                            <i class="fas fa-check"></i> Location must be in Jordan
                                        </li>
                                        <li>
                                            <i class="fas fa-check"></i> Original photos only
                                        </li>
                                        <li>
                                            <i class="fas fa-check"></i> Detailed description required
                                        </li>
                                        <li>
                                            <i class="fas fa-check"></i> GPS coordinates optional
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Top Contributors Section -->
                    <div class="sidebar contributors-section">
                        <h3 class="sidebar-title">Top Contributors</h3>
                        @if (isset($topContributors) && $topContributors->count() > 0)
                            @foreach ($topContributors as $contributor)
                                <div class="contributor-item">
                                    <div class="contributor-avatar">
                                        @if ($contributor->profile_image)
                                            <img src="{{ Storage::url($contributor->profile_image) }}"
                                                alt="{{ $contributor->first_name }} {{ $contributor->last_name }}">
                                        @else
                                            <img src="/api/placeholder/100/100"
                                                alt="{{ $contributor->first_name }} {{ $contributor->last_name }}">
                                        @endif
                                    </div>
                                    <div class="contributor-info">
                                        <div class="contributor-name">{{ $contributor->first_name }}
                                            {{ $contributor->last_name }}</div>
                                        <div class="contributor-stats">{{ $contributor->blog_posts_count }} places shared
                                        </div>
                                    </div>
                                    <div class="contributor-votes">{{ $contributor->blog_votes_count ?? 0 }} votes</div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-muted">No contributors yet. Be the first to share your hidden gem!</div>
                        @endif
                    </div>

                    @auth
                        <div class="sidebar mt-4">
                            <h3 class="sidebar-title">Your Entries</h3>
                            <p>View and manage your submitted hidden gems.</p>
                            <a href="{{ route('blog.user-posts') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list-ul"></i> My Entries
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal to be shown when redirected with success message -->
    @if (session('success'))
        <div class="modal fade success-modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <i class="fas fa-check-circle success-icon"></i>
                        <p class="success-message">{{ session('success') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script to auto-show the success modal -->
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                });
            </script>
        @endpush
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle arrow upvote functionality
                const upvoteButtons = document.querySelectorAll('.upvote');

                upvoteButtons.forEach(button => {
                    const postId = button.getAttribute('data-post-id');
                    const upvoteIcon = button.querySelector('.upvote-icon');
                    const upvoteCount = button.querySelector('.upvote-count');

                    // Check if user has already voted
                    fetch(`{{ url('/VisitJo/hiddengem') }}/${postId}/check-vote`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(
                                    `Server returned ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.voted) {
                                upvoteIcon.classList.add('voted');
                                // Also update heart icons for consistency
                                const heartButton = document.querySelector(
                                    `.heart-upvote[data-post-id="${postId}"]`);
                                if (heartButton) {
                                    heartButton.classList.add('voted');
                                    const heartIcon = heartButton.querySelector('i');
                                    if (heartIcon) {
                                        heartIcon.classList.remove('far');
                                        heartIcon.classList.add('fas');
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error checking vote status:', error);
                        });

                    // Handle click event
                    button.addEventListener('click', function() {
                        fetch(`{{ url('/VisitJo/hiddengem') }}/${postId}/vote`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                // Send empty body to avoid issues
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (response.status === 401) {
                                    // User not authenticated, redirect to login
                                    window.location.href = '{{ route('login') }}';
                                    throw new Error('Not authenticated');
                                }
                                if (!response.ok) {
                                    throw new Error(
                                        `Server returned ${response.status}: ${response.statusText}`
                                    );
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update UI for all vote elements of this post
                                    document.querySelectorAll(
                                            `.upvote-count[data-post-id="${postId}"], .heart-count[data-post-id="${postId}"]`
                                        )
                                        .forEach(el => el.textContent = data.voteCount);

                                    // Update both heart and arrow for the same post
                                    if (data.voted) {
                                        upvoteIcon.classList.add('voted');
                                        document.querySelectorAll(
                                                `.heart-upvote[data-post-id="${postId}"] i`)
                                            .forEach(icon => {
                                                icon.classList.remove('far');
                                                icon.classList.add('fas');
                                                icon.parentElement.classList.add('voted');
                                            });
                                    } else {
                                        upvoteIcon.classList.remove('voted');
                                        document.querySelectorAll(
                                                `.heart-upvote[data-post-id="${postId}"] i`)
                                            .forEach(icon => {
                                                icon.classList.remove('fas');
                                                icon.classList.add('far');
                                                icon.parentElement.classList.remove('voted');
                                            });
                                    }
                                } else if (data.message) {
                                    console.error('Vote failed:', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });

                // Handle heart upvote functionality
                const heartButtons = document.querySelectorAll('.heart-upvote');

                heartButtons.forEach(button => {
                    const postId = button.getAttribute('data-post-id');
                    const heartIcon = button.querySelector('i');
                    const heartCount = button.querySelector('.heart-count');

                    // Check if user has already voted
                    fetch(`{{ url('/VisitJo/hiddengem') }}/${postId}/check-vote`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(
                                    `Server returned ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.voted) {
                                button.classList.add('voted');
                                heartIcon.classList.remove('far');
                                heartIcon.classList.add('fas');
                            }
                        })
                        .catch(error => {
                            console.error('Error checking vote status:', error);
                        });

                    // Handle click event
                    button.addEventListener('click', function() {
                        fetch(`{{ url('/VisitJo/hiddengem') }}/${postId}/vote`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                // Send empty body to avoid issues
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (response.status === 401) {
                                    // User not authenticated, redirect to login
                                    window.location.href = '{{ route('login') }}';
                                    throw new Error('Not authenticated');
                                }
                                if (!response.ok) {
                                    throw new Error(
                                        `Server returned ${response.status}: ${response.statusText}`
                                    );
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update UI for all vote elements of this post
                                    document.querySelectorAll(
                                            `.upvote-count[data-post-id="${postId}"], .heart-count[data-post-id="${postId}"]`
                                        )
                                        .forEach(el => el.textContent = data.voteCount);

                                    // Update both heart and arrow for the same post
                                    if (data.voted) {
                                        button.classList.add('voted');
                                        heartIcon.classList.remove('far');
                                        heartIcon.classList.add('fas');

                                        // Also update arrow upvote
                                        const arrowButton = document.querySelector(
                                            `.upvote[data-post-id="${postId}"]`);
                                        if (arrowButton) {
                                            arrowButton.querySelector('.upvote-icon').classList.add(
                                                'voted');
                                        }
                                    } else {
                                        button.classList.remove('voted');
                                        heartIcon.classList.remove('fas');
                                        heartIcon.classList.add('far');

                                        // Also update arrow upvote
                                        const arrowButton = document.querySelector(
                                            `.upvote[data-post-id="${postId}"]`);
                                        if (arrowButton) {
                                            arrowButton.querySelector('.upvote-icon').classList
                                                .remove('voted');
                                        }
                                    }
                                } else if (data.message) {
                                    console.error('Vote failed:', data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    });
                });
            });
        </script>
    @endpush
@endsection
