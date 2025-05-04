@extends('public.layouts.main')
@section('title', 'Review Details')

@push('styles')
    <style>
        .review-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .review-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .activity-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .activity-image {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 25px;
        }

        .activity-details h2 {
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .activity-meta {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .booking-meta {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .review-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
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
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .review-content {
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .review-meta {
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="review-container">
            <div class="page-header mb-4">
                <h1>Review Details</h1>
                <p class="text-muted">Your review for {{ $review->activity->name }}</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="review-card">
                <div class="activity-header">
                    <img src="{{ asset($review->activity->image ? 'storage/' . $review->activity->image : 'api/placeholder/120/120') }}"
                        alt="{{ $review->activity->name }}" class="activity-image">
                    <div class="activity-details">
                        <h2>{{ $review->activity->name }}</h2>
                        <div class="activity-meta">
                            <div><i class="fas fa-map-marker-alt me-1"></i> {{ $review->activity->location }}</div>
                            <div><i class="far fa-calendar-alt me-1"></i>
                                Activity Date: {{ \Carbon\Carbon::parse($review->activity->start_date)->format('F d, Y') }}
                            </div>
                        </div>
                        <div class="booking-meta">
                            <div>Booking #: {{ $review->booking->booking_number }}</div>
                            <div>Booking Date: {{ $review->booking->created_at->format('F d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="review-status status-{{ $review->status }}">
                    <i
                        class="fas {{ $review->status == 'approved' ? 'fa-check-circle' : ($review->status == 'rejected' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                    Status: {{ ucfirst($review->status) }}
                    @if ($review->status == 'pending')
                        <small class="ms-2">(Your review is awaiting approval)</small>
                    @elseif($review->status == 'rejected')
                        <small class="ms-2">(Your review may need revisions)</small>
                    @endif
                </div>

                <div class="review-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                    <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                </div>

                <div class="review-content">
                    <blockquote>
                        "{{ $review->comment }}"
                    </blockquote>
                </div>

                <div class="review-meta">
                    <div>
                        <div>Review Posted: {{ $review->created_at->format('F d, Y') }}</div>
                        @if ($review->created_at->notEqualTo($review->updated_at))
                            <div>Last Updated: {{ $review->updated_at->format('F d, Y') }}</div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to All Reviews
                    </a>

                    <div>
                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-pencil-alt me-1"></i> Edit Review
                        </a>

                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash-alt me-1"></i> Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
