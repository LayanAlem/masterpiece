@extends('public.layouts.main')
@section('title', 'My Reviews')

@push('styles')
    <style>
        .reviews-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .review-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
            transition: transform 0.2s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .activity-info {
            display: flex;
            align-items: center;
        }

        .activity-image {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 15px;
        }

        .activity-details h4 {
            margin-bottom: 5px;
            font-size: 1.25rem;
        }

        .activity-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .review-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-approved {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-rejected {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .review-rating {
            margin: 15px 0;
        }

        .review-content {
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .review-date {
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 15px;
            text-align: right;
        }

        .empty-reviews {
            text-align: center;
            padding: 40px 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-top: 30px;
        }

        .empty-reviews i {
            font-size: 3.5rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-reviews h4 {
            color: #495057;
            margin-bottom: 10px;
        }

        .empty-reviews p {
            color: #6c757d;
            margin-bottom: 20px;
        }
    </style>
@endpush

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(reviewId, activityName) {
            Swal.fire({
                title: 'Delete Review',
                text: `Are you sure you want to delete your review for ${activityName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${reviewId}`).submit();
                }
            });
        }
    </script>
@endpush

@section('content')
    <div class="container py-5">
        <div class="reviews-container">
            <div class="page-header mb-4">
                <h1>My Reviews</h1>
                <p class="text-muted">All the reviews you've shared about your experiences</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($reviews->isEmpty())
                <div class="empty-reviews">
                    <i class="fas fa-comment-slash"></i>
                    <h4>No Reviews Yet</h4>
                    <p>You haven't written any reviews for your completed activities.</p>
                    <a href="{{ route('profile.index') }}#trips-section" class="btn btn-primary">
                        <i class="fas fa-suitcase me-1"></i> View My Completed Activities
                    </a>
                </div>
            @else
                @foreach ($reviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="activity-info">
                                <img src="{{ asset($review->activity->image ? 'storage/' . $review->activity->image : 'api/placeholder/70/70') }}"
                                    alt="{{ $review->activity->name }}" class="activity-image">
                                <div class="activity-details">
                                    <h4>{{ $review->activity->name }}</h4>
                                    <div class="activity-meta">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $review->activity->location }}
                                    </div>
                                </div>
                            </div>
                            <div class="review-status status-{{ $review->status }}">
                                <i
                                    class="fas {{ $review->status == 'approved' ? 'fa-check-circle' : ($review->status == 'rejected' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                {{ ucfirst($review->status) }}
                            </div>
                        </div>

                        <div class="review-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                        </div>

                        <div class="review-content">
                            "{{ $review->comment }}"
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="review-date">
                                Posted on {{ $review->created_at->format('F d, Y') }}
                            </div>

                            <div class="review-actions">
                                <a href="{{ route('reviews.edit', $review->id) }}"
                                    class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-pencil-alt me-1"></i> Edit
                                </a>

                                <form id="delete-form-{{ $review->id }}"
                                    action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete({{ $review->id }}, '{{ $review->activity->name }}')">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
