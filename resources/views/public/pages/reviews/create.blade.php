@extends('public.layouts.main')
@section('title', isset($review) ? 'Edit Review' : 'Write a Review')

@push('styles')
    <style>
        .review-form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 40px;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            font-size: 30px;
            color: #ddd;
            padding: 0 5px;
        }

        .star-rating label:hover,
        .star-rating label:hover~label,
        .star-rating input:checked~label {
            color: #FFC107;
        }

        .activity-summary {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .activity-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .activity-details h3 {
            margin-bottom: 5px;
            font-size: 1.25rem;
        }

        .activity-meta {
            color: #666;
            font-size: 0.9rem;
        }

        .review-guidelines {
            background-color: #f8f9fa;
            border-left: 4px solid var(--primary);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
        }

        .review-guidelines h5 {
            margin-bottom: 10px;
            color: var(--primary);
        }

        .review-guidelines ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .review-label {
            font-weight: 600;
            margin-bottom: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="page-heading mb-4">
                    <h1>{{ isset($review) ? 'Edit Your Review' : 'Write a Review' }}</h1>
                    <p class="text-muted">Share your experience to help other travelers</p>
                </div>

                <div class="review-form-container">
                    <div class="activity-summary">
                        <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'api/placeholder/100/100') }}"
                            alt="{{ $activity->name }}" class="activity-image">
                        <div class="activity-details">
                            <h3>{{ $activity->name }}</h3>
                            <div class="activity-meta">
                                <div><i class="fas fa-map-marker-alt me-1"></i> {{ $activity->location }}</div>
                                <div><i class="far fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('F d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="review-guidelines">
                        <h5><i class="fas fa-lightbulb me-2"></i>Tips for a Great Review</h5>
                        <ul>
                            <li>Focus on your personal experience</li>
                            <li>Be specific about what you liked and any suggestions for improvement</li>
                            <li>Keep it honest and helpful for other travelers</li>
                        </ul>
                    </div>

                    <form action="{{ isset($review) ? route('reviews.update', $review->id) : route('reviews.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($review))
                            @method('PUT')
                        @endif

                        <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                        <div class="mb-4">
                            <p class="review-label">How would you rate your experience?</p>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5"
                                    {{ isset($review) && $review->rating == 5 ? 'checked' : '' }} required>
                                <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star4" name="rating" value="4"
                                    {{ isset($review) && $review->rating == 4 ? 'checked' : '' }}>
                                <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star3" name="rating" value="3"
                                    {{ isset($review) && $review->rating == 3 ? 'checked' : '' }}>
                                <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star2" name="rating" value="2"
                                    {{ isset($review) && $review->rating == 2 ? 'checked' : '' }}>
                                <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>

                                <input type="radio" id="star1" name="rating" value="1"
                                    {{ isset($review) && $review->rating == 1 ? 'checked' : '' }}>
                                <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="review-label">Your Review</label>
                            <textarea id="comment" name="comment" rows="6" class="form-control @error('comment') is-invalid @enderror"
                                placeholder="Share your experience, what you enjoyed, and any suggestions for improvement..." required>{{ isset($review) ? $review->comment : old('comment') }}</textarea>
                            <div class="form-text">Minimum 10 characters, maximum 1000 characters.</div>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ isset($review) ? 'Update Review' : 'Submit Review' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
