<!-- Step 3: Payment Section -->
<div class="booking-step" id="step3" style="display:none;">
    <div class="d-flex align-items-center mb-4">
        <div class="back-button" onclick="showStep(2)">
            <i class="fas fa-arrow-left me-2"></i> Back to participants
        </div>
    </div>

    <div class="payment-container">
        <!-- Booking Summary Column -->
        <div class="booking-summary">
            <h5 class="mb-4">Booking Summary</h5>

            <div class="d-flex align-items-center tour-info">
                <div class="tour-image me-3">
                    <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}">
                </div>
                <div>
                    <div class="tour-name">{{ $activity->title }}</div>
                    <div class="tour-date"><i class="far fa-calendar me-2"></i> <span id="bookingDateSummary"></span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span class="pricing-label">Participants:</span>
                <span class="pricing-value" id="participantCountSummary">0</span>
            </div>

            <table class="pricing-table">
                <tr>
                    <td class="pricing-label">Price per person</td>
                    <td class="pricing-value td-right">${{ $activity->price }}</td>
                </tr>
                <tr>
                    <td class="pricing-label">Subtotal</td>
                    <td class="pricing-value td-right" id="subtotalSummary">$0.00</td>
                </tr>
                <tr id="pointsDiscountRow" style="display:none;">
                    <td class="pricing-label">Points discount</td>
                    <td class="pricing-value td-right discount" id="pointsDiscountSummary">-$0.00</td>
                </tr>
                <tr id="referralDiscountRow" style="display:none;">
                    <td class="pricing-label">Referral credit</td>
                    <td class="pricing-value td-right discount" id="referralDiscountSummary">-$0.00</td>
                </tr>
                <tr class="total-row">
                    <td>Total</td>
                    <td class="td-right" id="totalSummary">$0.00</td>
                </tr>
            </table>

            <div class="reward-earn-info mt-4">
                <div class="d-flex align-items-center">
                    <div class="reward-badge me-3">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <div class="fw-500">Earn points with this booking</div>
                        <div class="text-muted small">You'll earn <span id="pointsToEarn"
                                class="fw-bold text-success">0</span> points after completing this booking</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Options Column -->
        <div class="payment-section">
            <h5 class="card-title mb-4">Payment Options</h5>

            <div class="rewards-options">
                @if (Auth::check() && Auth::user()->loyalty_points > 0)
                    <div class="reward-option card-hover-effect">
                        <div class="d-flex align-items-center mb-3">
                            <div class="reward-icon loyalty-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="reward-summary flex-grow-1">
                                <h6 class="mb-1">Use Loyalty Points</h6>
                                <p class="text-muted mb-0 small">You have <span
                                        class="points-badge">{{ Auth::user()->loyalty_points }}</span> points available
                                </p>
                            </div>
                            <div class="modern-switch form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="usePointsToggle"
                                    onchange="toggleUsePoints()">
                            </div>
                        </div>

                        <div id="pointsSliderContainer" style="display:none;">
                            <div class="mb-2 small">
                                <span>Select how many points to use:</span>
                                <span class="float-end" id="pointsValue">0</span>
                            </div>
                            <input type="range" class="custom-range w-100" min="0"
                                max="{{ Auth::user()->loyalty_points }}" value="0" id="pointsSlider"
                                oninput="updatePointsValue()">
                            <div class="d-flex justify-content-between slider-values">
                                <span>0</span>
                                <span>{{ Auth::user()->loyalty_points }}</span>
                            </div>
                            <div class="text-end mt-2 small text-muted">
                                Value: <span id="pointsValueInMoney">$0.00</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::check() && Auth::user()->referral_credit > 0)
                    <div class="reward-option card-hover-effect">
                        <div class="d-flex align-items-center">
                            <div class="reward-icon referral-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="reward-summary flex-grow-1">
                                <h6 class="mb-1">Apply Referral Credit</h6>
                                <p class="text-muted mb-0 small">You have
                                    ${{ number_format(Auth::user()->referral_credit, 2) }} referral credit</p>
                            </div>
                            <div class="modern-switch form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="useReferralToggle"
                                    onchange="toggleUseReferral()">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="section-divider">
                <span>Payment Details</span>
            </div>

            <div class="payment-methods d-flex">
                <div class="payment-method-option selected" data-method="card" onclick="selectPaymentMethod('card')">
                    <i class="far fa-credit-card"></i>
                    <span>Credit Card</span>
                </div>
                <div class="payment-method-option" data-method="paypal" onclick="selectPaymentMethod('paypal')">
                    <i class="fab fa-paypal"></i>
                    <span>PayPal</span>
                </div>
                <div class="payment-method-option" data-method="apple" onclick="selectPaymentMethod('apple')">
                    <i class="fab fa-apple-pay"></i>
                    <span>Apple Pay</span>
                </div>
                <div class="payment-method-option" data-method="google" onclick="selectPaymentMethod('google')">
                    <i class="fab fa-google-pay"></i>
                    <span>Google Pay</span>
                </div>
            </div>

            <div id="cardPaymentForm">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cardName" placeholder="Name on card">
                            <label for="cardName">Name on card</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cardNumber" placeholder="Card number">
                            <label for="cardNumber">Card number</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
                            <label for="cardExpiry">Expiration (MM/YY)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cardCvc" placeholder="CVC">
                            <label for="cardCvc">CVC</label>
                        </div>
                    </div>
                </div>
            </div>

            <div id="paypalPaymentForm" style="display:none;" class="text-center p-4">
                <img src="/assets/images/paypal-button.png" alt="PayPal" style="max-width: 200px;">
                <p class="text-muted mt-3">You'll be redirected to PayPal to complete your payment after clicking
                    "Complete Booking"</p>
            </div>

            <div id="applePaymentForm" style="display:none;" class="text-center p-4">
                <button class="btn btn-dark w-100 py-3" disabled>
                    <i class="fab fa-apple me-2"></i> Apple Pay
                </button>
                <p class="text-muted mt-3">This payment option will be available in our next update</p>
            </div>

            <div id="googlePaymentForm" style="display:none;" class="text-center p-4">
                <button class="btn btn-primary w-100 py-3" style="background-color: #4285F4;" disabled>
                    <i class="fab fa-google me-2"></i> Google Pay
                </button>
                <p class="text-muted mt-3">This payment option will be available in our next update</p>
            </div>

            <div class="terms-checkbox mt-4">
                <input type="checkbox" class="form-check-input" id="termsCheckbox">
                <div class="terms-text">
                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and
                        Conditions</a> and <a href="#" data-bs-toggle="modal"
                        data-bs-target="#privacyModal">Privacy Policy</a>. I understand that my personal data will be
                    processed as described in the Privacy Policy.
                </div>
            </div>
        </div>
    </div>

    <div class="complete-booking-container mt-4">
        <button id="completeBookingBtn" class="payment-button pulse-animation" onclick="completeBooking()">
            Complete Booking <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>These Terms and Conditions govern your booking and participation in activities offered through our
                    platform.</p>
                <h6>Booking Confirmation</h6>
                <p>Your booking is only confirmed once payment has been processed and you have received a confirmation
                    email.</p>
                <!-- Additional terms would go here -->
            </div>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>This Privacy Policy explains how we collect, use, and protect your personal information when you use
                    our platform.</p>
                <h6>Information We Collect</h6>
                <p>We collect personal information such as your name, email address, and payment details to process your
                    bookings.</p>
                <!-- Additional privacy details would go here -->
            </div>
        </div>
    </div>
</div>

<!-- Complete Your Journey Section -->
@php
    // Make sure we have a valid category_type_id before querying
    $categoryTypeId = $activity->category_type_id ?? null;
    $similarActivities = collect(); // Initialize as empty collection

    if ($categoryTypeId) {
        // Only get activities from the SAME category type, not from other categories
        $similarActivities = \App\Models\Activity::where('category_type_id', $categoryTypeId)
            ->where('id', '!=', $activity->id)
            ->take(3)
            ->get();
    }
@endphp

@if ($similarActivities->count() > 0)
    <div class="similar-activities-section mt-5 mb-5">
        <div class="container">
            <h2 class="section-title text-center mb-4">Complete Your Journey</h2>
            <p class="text-center text-muted mb-4">Discover more experiences similar to {{ $activity->name }}</p>

            <div class="row">
                @foreach ($similarActivities as $similarActivity)
                    <div class="col-md-4 mb-4">
                        <div class="similar-activity-card">
                            <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden hover-scale">
                                <div class="activity-image-container">
                                    <img src="{{ $similarActivity->image_url }}" class="card-img-top"
                                        alt="{{ $similarActivity->name }}">
                                    <div class="activity-category-badge">
                                        {{ $similarActivity->categoryType->name }}
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">{{ $similarActivity->name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="activity-location">
                                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                            {{ $similarActivity->location }}
                                        </div>
                                        <div class="activity-price">
                                            <span class="fw-bold">${{ $similarActivity->price }}</span> <small
                                                class="text-muted">per person</small>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted mb-4">
                                        {{ Str::limit($similarActivity->description, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-0">
                                    <a href="{{ route('activities.show', $similarActivity->id) }}"
                                        class="btn btn-primary btn-book-now w-100">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .similar-activities-section {
            background-color: #f8f9fa;
            padding: 60px 0;
            border-radius: 8px;
        }

        .similar-activity-card {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: translateY(-5px);
        }

        .activity-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .activity-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .hover-scale:hover img {
            transform: scale(1.1);
        }

        .activity-category-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .section-title {
            position: relative;
            font-weight: 600;
            color: #333;
        }

        .section-title:after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, #4CAF50, #8BC34A);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .btn-book-now {
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-book-now:hover {
            background-color: #3e9142;
            border-color: #3e9142;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endif
