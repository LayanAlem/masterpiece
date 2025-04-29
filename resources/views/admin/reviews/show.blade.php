@extends('admin.layouts.admin')

@section('title', 'Review Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Reviews /</span> View Review
        </h4>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Review Details</h5>
                        <div>
                            <a href="{{ route('reviews.index') }}" class="btn btn-secondary me-2">
                                <i class="bx bx-arrow-back me-1"></i> Back to Reviews
                            </a>
                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-primary">
                                <i class="bx bx-edit-alt me-1"></i> Edit Review
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">User Information</h6>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="avatar avatar-sm me-3 bg-label-primary">
                                            <span class="avatar-initial rounded-circle">
                                                {{ strtoupper(substr($review->user->first_name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">
                                                {{ $review->user->first_name ?? '' }} {{ $review->user->last_name ?? 'N/A' }}
                                            </h6>
                                            <small class="text-muted">{{ $review->user->email ?? 'No email available' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Activity Information</h6>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="me-3" style="width: 40px; height: 40px;">
                                            @if ($review->activity && $review->activity->image)
                                                <img src="{{ asset('storage/' . $review->activity->image) }}"
                                                    alt="{{ $review->activity->name }}"
                                                    class="rounded"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bx bx-map-pin text-primary fs-4"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $review->activity->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $review->activity->location ?? 'No location available' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">Review Content</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="ps-0" style="width: 180px;">Booking Number</th>
                                        <td>
                                            <span class="badge bg-label-info">{{ $review->booking_number ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Rating</th>
                                        <td>
                                            <div class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class='bx bxs-star text-warning'></i>
                                                    @else
                                                        <i class='bx bx-star text-muted'></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ $review->rating }})</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Status</th>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'approved' => 'bg-label-success',
                                                    'pending' => 'bg-label-warning',
                                                    'rejected' => 'bg-label-danger',
                                                ][$review->status] ?? 'bg-label-secondary';

                                                $statusIcon = [
                                                    'approved' => 'bx-check-circle',
                                                    'pending' => 'bx-time',
                                                    'rejected' => 'bx-x-circle',
                                                ][$review->status] ?? 'bx-question-mark';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                <i class='bx {{ $statusIcon }} me-1'></i>
                                                {{ ucfirst($review->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Created At</th>
                                        <td>{{ $review->created_at->format('F d, Y \a\t h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0">Last Updated</th>
                                        <td>{{ $review->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold mb-3">Review Comment</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <i class='bx bxs-quote-alt-left text-primary me-1'></i>
                                                @if ($review->comment)
                                                    {{ $review->comment }}
                                                @else
                                                    <span class="text-muted fst-italic">No comment provided.</span>
                                                @endif
                                                <i class='bx bxs-quote-alt-right text-primary ms-1'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="comment" value="{{ $review->comment }}">
                                    <input type="hidden" name="rating" value="{{ $review->rating }}">

                                    <div class="d-flex justify-content-end mt-3">
                                        @if ($review->status !== 'approved')
                                            <button type="submit" name="status" value="approved" class="btn btn-success me-2">
                                                <i class='bx bx-check-circle me-1'></i> Approve Review
                                            </button>
                                        @endif

                                        @if ($review->status !== 'rejected')
                                            <button type="submit" name="status" value="rejected" class="btn btn-danger me-2">
                                                <i class='bx bx-x-circle me-1'></i> Reject Review
                                            </button>
                                        @endif

                                        @if ($review->status !== 'pending')
                                            <button type="submit" name="status" value="pending" class="btn btn-warning">
                                                <i class='bx bx-time me-1'></i> Mark as Pending
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
