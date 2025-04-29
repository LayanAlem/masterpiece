@extends('public.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/payment.css') }}">
@endpush

@section('content')
    <div class="container booking-container">
        <div class="row">
            <div class="col-12">
                <h1 class="checkout-title">Your Booking Cart</h1>
                <p class="checkout-subtitle">Review your selected activities before proceeding to checkout</p>

                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-label">Review Cart</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-label">Participant Details</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-label">Payment</div>
                    </div>
                </div>
            </div>
        </div>

        @if (count($pendingBookings) > 0)
            <div class="row">
                <div class="col-lg-8">
                    @foreach ($pendingBookings as $booking)
                        <div class="booking-item-card">
                            <div class="d-flex">
                                <div>
                                    @if ($booking->activities->isNotEmpty() && $booking->activities->first()->image)
                                        <img src="{{ asset('storage/' . $booking->activities->first()->image) }}"
                                            alt="{{ $booking->activities->first()->name }}" class="activity-image">
                                    @else
                                        <div class="placeholder-image bg-light rounded" style="width:100px;height:100px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="activity-info">
                                    <div class="activity-name">
                                        {{ $booking->activities->isNotEmpty() ? $booking->activities->first()->name : 'Booking #' . $booking->booking_number }}
                                    </div>
                                    <div class="booking-meta">
                                        <div class="booking-meta-item">
                                            <i class="fas fa-receipt"></i>
                                            <span>Booking #{{ $booking->booking_number }}</span>
                                        </div>
                                        <div class="booking-meta-item">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'No date' }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="booking-status status-pending">{{ ucfirst($booking->status) }}</span>
                                        <div class="booking-price">${{ number_format($booking->total_price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-actions">
                                <button class="btn-action btn-remove">
                                    <i class="fas fa-trash-alt me-1"></i> Remove
                                </button>
                                <button class="btn-action btn-view">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4">
                    <div class="checkout-sidebar">
                        <h3 class="sidebar-title">Order Summary</h3>
                        <table class="checkout-summary-table">
                            <tr>
                                <td class="label">Subtotal</td>
                                <td class="value">${{ number_format($totalAmount, 2) }}</td>
                            </tr>
                            @if ($totalAmount > 200)
                                <tr>
                                    <td class="label">Volume Discount (5%)</td>
                                    <td class="value discount-value">-${{ number_format($totalAmount * 0.05, 2) }}</td>
                                </tr>
                                @php $discountedAmount = $totalAmount * 0.95; @endphp
                            @else
                                @php $discountedAmount = $totalAmount; @endphp
                            @endif
                            <tr>
                                <td class="label">Tax (5%)</td>
                                <td class="value">${{ number_format($discountedAmount * 0.05, 2) }}</td>
                            </tr>
                            <tr class="total-row">
                                <td>Total</td>
                                <td class="value">${{ number_format($discountedAmount * 1.05, 2) }}</td>
                            </tr>
                        </table>

                        <div class="loyalty-section">
                            <div class="loyalty-header">
                                <div class="loyalty-title">Loyalty Points</div>
                                <div class="available-points">Available: 2,500 points</div>
                            </div>

                            <div class="loyalty-option">
                                <input type="checkbox" id="use-points">
                                <label for="use-points">Use my points (100 points = $1)</label>
                            </div>

                            <div class="loyalty-earn">
                                <i class="fas fa-check-circle"></i>
                                You'll earn {{ round($discountedAmount) }} points from this purchase
                            </div>
                        </div>

                        <a href="{{ route('payment') }}" class="proceed-button text-decoration-none">
                            Proceed to Details
                        </a>

                        <div class="continue-shopping">
                            <a href="{{ route('services') }}">
                                <i class="fas fa-arrow-left me-1"></i> Continue Exploring
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Your booking cart is empty</h3>
                <p>Looks like you haven't added any activities to your cart yet.</p>
                <a href="{{ route('services') }}" class="explore-button text-decoration-none">Explore Activities</a>
            </div>
        @endif
    </div>
@endsection
