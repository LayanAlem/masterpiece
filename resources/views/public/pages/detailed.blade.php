@extends('public.layouts.main')
@section('title', $activity->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/detailed.css') }}">
    <link rel="stylesheet" href="{{ asset('mainStyle/bookingParticipants.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
@endpush

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3.58579 13.4142C2.80474 12.6332 2.80474 11.3668 3.58579 10.5858L10.5858 3.58579C11.3668 2.80474 12.6332 2.80474 13.4142 3.58579L20.4142 10.5858C21.1953 11.3668 21.1953 12.6332 20.4142 13.4142L13.4142 20.4142C12.6332 21.1953 11.3668 21.1953 10.5858 20.4142L3.58579 13.4142Z"
                        fill="#92400b" />
                </svg>
                Hidden Jordan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('services') }}">Tours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                <button class="share-btn">
                    <i class="fas fa-share-alt"></i>
                    Share
                </button>
            </div>
        </div>
    </nav>

    <!-- Activity Content -->
    <div class="activity-content">
        <div class="container">
            <div class="activity-header">
                <h1 class="activity-title">{{ $activity->name }}</h1>
                <div class="activity-meta">
                    <div class="activity-meta-item">
                        <i class="far fa-clock"></i>
                        <span>{{ $activity->formatted_duration }}</span>
                    </div>
                    <div class="activity-meta-item">
                        <i class="fas fa-hiking"></i>
                        <span>{{ $activity->difficulty ?? 'Moderate' }}</span>
                    </div>
                    <div class="activity-meta-item">
                        <i class="fas fa-users"></i>
                        <span>{{ $activity->is_family_friendly ? 'Family Friendly' : 'Small groups' }}</span>
                    </div>
                    <div class="activity-meta-item">
                        <i class="fas fa-star"></i>
                        <span>{{ number_format($activity->reviews_avg_rating ?? 4.5, 1) }}/5
                            ({{ $activity->reviews_count ?? 0 }} reviews)</span>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="photo-gallery">
                <div class="photo-item main-photo">
                    <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'api/placeholder/800/400') }}"
                        alt="{{ $activity->name }}">
                </div>
                <div class="photo-item sub-photo">
                    <img src="/api/placeholder/400/200" alt="Close-up detail of {{ $activity->name }}">
                </div>
                <div class="photo-item sub-photo">
                    <img src="/api/placeholder/400/200" alt="{{ $activity->name }} additional view">
                </div>
            </div>

            <!-- Book Now Button -->
            <button class="book-btn" id="openBookingModal">
                <i class="fas fa-calendar-check"></i>
                Book Your {{ $activity->name }} Experience Now
            </button>

            <!-- Weather & Preparation -->
            <div class="content-card">
                <h2 class="section-title">Weather & Preparation</h2>
                <div class="weather-grid">
                    <div class="current-conditions">
                        <h4>Current Conditions</h4>
                        <div class="current-weather">
                            <div class="temp-display">{{ $weather->current_temp }}°C</div>
                            <div class="weather-desc">{{ $weather->condition }}</div>
                            <div class="weather-details">
                                <div>Humidity: {{ $weather->humidity }}%</div>
                                <div>UV Index: {{ $weather->uv_index }}</div>
                            </div>
                        </div>
                        <div class="forecast">
                            <h5 class="mt-4 mb-2">Today's Forecast</h5>
                            <div class="forecast-row">
                                @foreach ($weather->forecast as $forecast)
                                    <div class="forecast-item">
                                        <div class="forecast-time">{{ $forecast['time'] }}</div>
                                        <i
                                            class="fas {{ $forecast['icon'] }} {{ $forecast['icon'] == 'fa-sun' ? 'text-warning' : 'text-primary' }}"></i>
                                        <div class="forecast-temp">{{ $forecast['temp'] }}°C</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="what-to-bring">
                        <h4>What to Bring</h4>
                        <ul class="items-to-bring">
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Comfortable walking shoes</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Sun protection (hat, sunscreen, sunglasses)</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Water bottle (2L minimum)</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Light, breathable clothing</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Camera</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Small backpack</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <h4 class="mt-4">Best Time to Visit</h4>
                <div class="season-grid">
                    <div class="season-card">
                        <div class="season-title">Spring (Mar-May)</div>
                        <div class="season-desc">Perfect temperatures, wildflowers blooming. Morning/late afternoon with
                            moderate crowds.</div>
                    </div>
                    <div class="season-card">
                        <div class="season-title">Summer (Jun-Aug)</div>
                        <div class="season-desc">Very hot during day. Early morning visits recommended. Lower crowds.</div>
                    </div>
                    <div class="season-card">
                        <div class="season-title">Fall (Sep-Nov)</div>
                        <div class="season-desc">Mild temperatures, clear skies. Excellent photography conditions.</div>
                    </div>
                    <div class="season-card">
                        <div class="season-title">Winter (Dec-Feb)</div>
                        <div class="season-desc">Cool temperatures, possible rain, stunning views. Dramatic skies, fewer
                            tourists.</div>
                    </div>
                </div>
            </div>

            <!-- Visitor Experiences -->
            <div class="content-card">
                <h2 class="section-title">Visitor Experiences</h2>
                @if ($activity->reviews && $activity->reviews->count() > 0)
                    @foreach ($activity->reviews->take(2) as $review)
                        <div class="review-card">
                            <div class="review-avatar">
                                <img src="/api/placeholder/60/60" alt="{{ $review->user->first_name ?? 'Reviewer' }}">
                            </div>
                            <div class="review-content">
                                <h5 class="reviewer-name">{{ $review->user->first_name ?? 'Anonymous' }}
                                    {{ $review->user->last_name ?? '' }}</h5>
                                <div class="review-date">Visited
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('F Y') }}</div>
                                <div class="review-text">
                                    "{{ $review->comment }}"
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="review-card">
                        <div class="review-avatar">
                            <img src="/api/placeholder/60/60" alt="Michael Chen">
                        </div>
                        <div class="review-content">
                            <h5 class="reviewer-name">Michael Chen</h5>
                            <div class="review-date">Visited April 2024</div>
                            <div class="review-text">
                                "The experience was breathtaking. Our guide's knowledge brought this place to life. Worth
                                every minute of the journey."
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Complete Your Journey -->
            <div class="content-card">
                <h2 class="section-title">Complete Your Journey</h2>
                <div class="related-activities">
                    @if ($similarActivities && $similarActivities->count() > 0)
                        @foreach ($similarActivities as $relatedActivity)
                            <div class="activity-card">
                                <div class="activity-img">
                                    <img src="{{ asset($relatedActivity->image ? 'storage/' . $relatedActivity->image : 'api/placeholder/400/250') }}"
                                        alt="{{ $relatedActivity->name }}">
                                </div>
                                <div class="activity-info">
                                    <h4 class="activity-name">{{ $relatedActivity->name }}</h4>
                                    <div class="tour-attributes">
                                        <div class="attribute-item">
                                            <i class="far fa-clock"></i>
                                            <span>{{ $relatedActivity->formatted_duration ?? 'Full day' }}</span>
                                        </div>
                                    </div>
                                    <p class="mb-2">{{ Str::limit($relatedActivity->description, 60) }}</p>
                                    <div class="activity-price">From ${{ number_format($relatedActivity->price, 0) }} USD
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="activity-card">
                            <div class="activity-img">
                                <img src="/api/placeholder/400/250" alt="Wadi Rum Desert Camp">
                            </div>
                            <div class="activity-info">
                                <h4 class="activity-name">Wadi Rum Desert Camp</h4>
                                <div class="tour-attributes">
                                    <div class="attribute-item">
                                        <i class="far fa-clock"></i>
                                        <span>Overnight</span>
                                    </div>
                                </div>
                                <p class="mb-2">Experience Bedouin hospitality under the stars in Wadi Rum.</p>
                                <div class="activity-price">From $85 USD</div>
                            </div>
                        </div>
                        <div class="activity-card">
                            <div class="activity-img">
                                <img src="/api/placeholder/400/250" alt="Dead Sea Wellness Day">
                            </div>
                            <div class="activity-info">
                                <h4 class="activity-name">Dead Sea Wellness Day</h4>
                                <div class="tour-attributes">
                                    <div class="attribute-item">
                                        <i class="far fa-clock"></i>
                                        <span>Full day</span>
                                    </div>
                                </div>
                                <p class="mb-2">Relax and float in mineral-rich, therapeutic mud.</p>
                                <div class="activity-price">From $95 USD</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Book {{ $activity->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Booking Steps -->
                    <div class="booking-steps mb-4">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Select Quantity</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Participants</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Payment</div>
                        </div>
                    </div>

                    <!-- Step 1: Quantity Selection -->
                    <div class="booking-step" id="step1">
                        <h4>How many people will be joining?</h4>
                        <div class="quantity-selector mb-4">
                            <button class="quantity-btn" id="decreaseQuantity">-</button>
                            <input type="number" id="ticketQuantity" value="1" min="1" max="10"
                                readonly>
                            <button class="quantity-btn" id="increaseQuantity">+</button>
                        </div>

                        <div class="price-summary">
                            <div class="price-row">
                                <span>{{ $activity->name }}</span>
                                <span>${{ number_format($activity->price, 2) }} × <span
                                        id="quantityDisplay">1</span></span>
                            </div>
                            <div class="price-row total">
                                <span>Total</span>
                                <span id="totalPrice">${{ number_format($activity->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-primary" id="goToStep2">Continue</button>
                        </div>
                    </div>

                    <!-- Step 2: Participants Information -->
                    <div class="booking-step" id="step2" style="display: none;">
                        <!-- Participant form will be inserted here dynamically -->
                        <div id="participantFormsContainer">
                            <!-- Each participant form will be added here -->
                        </div>

                        <div class="step-navigation d-flex justify-content-between mt-4">
                            <button class="btn btn-secondary" id="backToStep1">Back</button>
                            <button class="btn btn-primary" id="goToStep3">Continue to Payment</button>
                        </div>
                    </div>

                    <!-- Step 3: Payment -->
                    <div class="booking-step" id="step3" style="display: none;">
                        <div class="booking-summary">
                            <div class="tour-info">
                                <div>
                                    <div class="tour-name">{{ $activity->name }}</div>
                                    <div class="tour-date" id="bookingSummaryDate">{{ date('F d, Y') }} • <span
                                            id="participantCount">1</span> Participants</div>
                                </div>
                                <div class="regular-price">
                                    <div class="pricing-label">Regular Price</div>
                                    <div class="pricing-value">${{ number_format($activity->price, 2) }} × <span
                                            id="quantityDisplayPayment">1</span></div>
                                </div>
                            </div>

                            <table class="pricing-table">
                                <tr>
                                    <td class="pricing-label">Subtotal</td>
                                    <td class="td-right pricing-value" id="paymentSubtotal">
                                        ${{ number_format($activity->price, 2) }}</td>
                                </tr>
                                <tr id="discountRow" style="display: none;">
                                    <td class="pricing-label">Multi-ticket Discount (5%)</td>
                                    <td class="td-right pricing-value discount" id="discountAmount">-$0.00</td>
                                </tr>
                                <tr>
                                    <td class="pricing-label">Tourism Tax</td>
                                    <td class="td-right pricing-value" id="taxAmount">
                                        ${{ number_format($activity->price * 0.05, 2) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <td>Total</td>
                                    <td class="td-right" id="paymentTotal">
                                        ${{ number_format($activity->price * 1.05, 2) }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="payment-section">
                            <h3>
                                <i class="fas fa-lock secure-icon"></i>
                                Secure Payment
                            </h3>

                            <div class="payment-methods">
                                <div class="payment-method selected">
                                    <div class="payment-method-radio"></div>
                                    <i class="far fa-credit-card"></i>
                                    <span class="payment-method-label">Credit Card</span>
                                </div>
                                <div class="payment-method">
                                    <div class="payment-method-radio"></div>
                                    <i class="fab fa-paypal"></i>
                                    <span class="payment-method-label">PayPal</span>
                                </div>
                                <div class="payment-method">
                                    <div class="payment-method-radio"></div>
                                    <i class="far fa-credit-card"></i>
                                    <span class="payment-method-label">Debit Card</span>
                                </div>
                            </div>

                            <div class="card-info-section">
                                <div class="form-group">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber"
                                        placeholder="1234 5678 9012 3456" value="1234 1234 1234 1234">
                                    <div class="card-icons">
                                        <div class="card-icon visa">VISA</div>
                                        <div class="card-icon mastercard">MC</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label class="form-label">Expiration Date</label>
                                            <input type="text" class="form-control" id="expiryDate"
                                                placeholder="MM/YY" value="12/28">
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" placeholder="123"
                                                value="123">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Cardholder Name</label>
                                    <input type="text" class="form-control" id="cardholderName"
                                        placeholder="Name as it appears on card" value="Test User">
                                </div>
                            </div>

                            @if (Auth::check() && Auth::user()->loyalty_points > 0)
                                <div class="loyalty-section">
                                    <div class="loyalty-header">
                                        <div class="loyalty-title">Loyalty Points</div>
                                        <div class="available-points">Available:
                                            {{ number_format(Auth::user()->loyalty_points) }} points</div>
                                    </div>

                                    <div class="loyalty-option">
                                        <input type="checkbox" id="use-points">
                                        <label for="use-points">Use my points (100 points = $1)</label>
                                    </div>

                                    <div class="loyalty-earn">
                                        <i class="fas fa-check-circle"></i>
                                        <span id="pointsToEarn">You'll earn 100 points from this purchase</span>
                                    </div>
                                </div>
                            @endif

                            <div class="terms-checkbox">
                                <input type="checkbox" id="terms-agree" required>
                                <label for="terms-agree" class="terms-text">
                                    I agree to the <a href="#">Terms of Service</a> and <a
                                        href="#">Cancellation Policy</a>
                                </label>
                                <div class="invalid-feedback">
                                    You must agree to the terms to continue
                                </div>
                            </div>

                            <div class="step-navigation d-flex justify-content-between mt-4">
                                <button class="btn btn-secondary" id="backToStep2">Back</button>
                                <button class="btn btn-success" id="completeBooking">Complete Payment • <span
                                        id="paymentButtonAmount">${{ number_format($activity->price * 1.05, 2) }}</span></button>
                            </div>

                            <div class="confirmation-message">
                                <i class="far fa-clock"></i>
                                You'll receive email confirmation within 2 minutes
                            </div>

                            <div class="payment-icons">
                                <div class="icon"><i class="fas fa-shield-alt"></i></div>
                                <div class="icon">VISA</div>
                                <div class="icon">MC</div>
                                <div class="icon"><i class="fab fa-paypal"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            const activityPrice = {{ $activity->price }};
            let currentParticipant = 1;
            let totalParticipants = 1;

            // Elements
            const bookingModal = document.getElementById('bookingModal');
            const openBookingModalBtn = document.getElementById('openBookingModal');
            const decreaseQuantityBtn = document.getElementById('decreaseQuantity');
            const increaseQuantityBtn = document.getElementById('increaseQuantity');
            const ticketQuantityInput = document.getElementById('ticketQuantity');
            const quantityDisplay = document.getElementById('quantityDisplay');
            const totalPriceElement = document.getElementById('totalPrice');
            const participantFormsContainer = document.getElementById('participantFormsContainer');

            // Step navigation buttons
            const goToStep2Btn = document.getElementById('goToStep2');
            const backToStep1Btn = document.getElementById('backToStep1');
            const goToStep3Btn = document.getElementById('goToStep3');
            const backToStep2Btn = document.getElementById('backToStep2');
            const completeBookingBtn = document.getElementById('completeBooking');

            // Step elements
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            const bookingSteps = document.querySelectorAll('.booking-steps .step');

            // Initialize modal
            const modal = new bootstrap.Modal(bookingModal);

            // Open modal when book button is clicked
            openBookingModalBtn.addEventListener('click', function() {
                modal.show();
            });

            // Quantity selector
            decreaseQuantityBtn.addEventListener('click', function() {
                if (parseInt(ticketQuantityInput.value) > 1) {
                    ticketQuantityInput.value = parseInt(ticketQuantityInput.value) - 1;
                    updatePriceSummary();
                }
            });

            increaseQuantityBtn.addEventListener('click', function() {
                if (parseInt(ticketQuantityInput.value) < 10) {
                    ticketQuantityInput.value = parseInt(ticketQuantityInput.value) + 1;
                    updatePriceSummary();
                }
            });

            // Update price summary
            function updatePriceSummary() {
                const quantity = parseInt(ticketQuantityInput.value);
                const total = quantity * activityPrice;

                quantityDisplay.textContent = quantity;
                totalPriceElement.textContent = '$' + total.toFixed(2);
                totalParticipants = quantity;

                // Also update payment summary
                updatePaymentSummary();
            }

            // Update price summary for payment page
            function updatePaymentSummary() {
                const quantity = parseInt(ticketQuantityInput.value);
                const subtotal = quantity * activityPrice;
                let discount = 0;
                let discountPercent = 0;

                // Apply discount for multiple tickets (if more than 3)
                if (quantity >= 3) {
                    discountPercent = 5;
                    discount = subtotal * (discountPercent / 100);
                    document.getElementById('discountRow').style.display = 'table-row';
                    document.getElementById('discountAmount').textContent = '-$' + discount.toFixed(2);
                } else {
                    document.getElementById('discountRow').style.display = 'none';
                }

                const taxRate = 0.05;
                const tax = (subtotal - discount) * taxRate;
                const total = subtotal - discount + tax;

                // Update payment page elements
                document.getElementById('quantityDisplayPayment').textContent = quantity;
                document.getElementById('participantCount').textContent = quantity;
                document.getElementById('paymentSubtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('taxAmount').textContent = '$' + tax.toFixed(2);
                document.getElementById('paymentTotal').textContent = '$' + total.toFixed(2);
                document.getElementById('paymentButtonAmount').textContent = '$' + total.toFixed(2);

                // Update loyalty points to earn (roughly 10% of total)
                if (document.getElementById('pointsToEarn')) {
                    const pointsToEarn = Math.round(total);
                    document.getElementById('pointsToEarn').textContent =
                        `You'll earn ${pointsToEarn} points from this purchase`;
                }
            }

            // Navigate to Step 2
            goToStep2Btn.addEventListener('click', function() {
                // Generate participant forms based on quantity
                generateParticipantForms();

                // Show Step 2
                step1.style.display = 'none';
                step2.style.display = 'block';

                // Update steps indicator
                bookingSteps[0].classList.remove('active');
                bookingSteps[1].classList.add('active');
            });

            // Navigate back to Step 1
            backToStep1Btn.addEventListener('click', function() {
                step2.style.display = 'none';
                step1.style.display = 'block';

                bookingSteps[1].classList.remove('active');
                bookingSteps[0].classList.add('active');
            });

            // Navigate to Step 3
            goToStep3Btn.addEventListener('click', function() {
                // Validate participant forms
                if (validateParticipantForms()) {
                    step2.style.display = 'none';
                    step3.style.display = 'block';

                    bookingSteps[1].classList.remove('active');
                    bookingSteps[2].classList.add('active');
                }
            });

            // Navigate back to Step 2
            backToStep2Btn.addEventListener('click', function() {
                step3.style.display = 'none';
                step2.style.display = 'block';

                bookingSteps[2].classList.remove('active');
                bookingSteps[1].classList.add('active');
            });

            // Allow clicking on steps to navigate backward
            document.querySelectorAll('.booking-steps .step').forEach(step => {
                step.addEventListener('click', function() {
                    const clickedStep = parseInt(this.getAttribute('data-step'));
                    let currentActiveStep = 0;

                    // Find which step is currently active
                    bookingSteps.forEach((step, index) => {
                        if (step.classList.contains('active')) {
                            currentActiveStep = index + 1;
                        }
                    });

                    // Only allow going backward, not forward
                    if (clickedStep < currentActiveStep) {
                        // Navigate to the clicked step
                        if (clickedStep === 1) {
                            // Go to step 1
                            step2.style.display = 'none';
                            step3.style.display = 'none';
                            step1.style.display = 'block';

                            // Update step indicators
                            bookingSteps[0].classList.add('active');
                            bookingSteps[1].classList.remove('active');
                            bookingSteps[2].classList.remove('active');
                        } else if (clickedStep === 2 && currentActiveStep === 3) {
                            // Go to step 2 from step 3
                            step1.style.display = 'none';
                            step3.style.display = 'none';
                            step2.style.display = 'block';

                            // Update step indicators
                            bookingSteps[0].classList.remove('active');
                            bookingSteps[1].classList.add('active');
                            bookingSteps[2].classList.remove('active');
                        }
                    }
                });
            });

            // Generate participant forms
            function generateParticipantForms() {
                participantFormsContainer.innerHTML = '';

                for (let i = 1; i <= totalParticipants; i++) {
                    const participantForm = `
                    <div class="participant-form" data-participant="${i}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Participant ${i} of ${totalParticipants}</h5>
                            <div class="participant-navigation">
                                <button type="button" class="btn btn-sm ${i === 1 ? 'btn-secondary disabled' : 'btn-outline-secondary'} prev-participant" ${i === 1 ? 'disabled' : ''}>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="mx-2">${i} / ${totalParticipants}</span>
                                <button type="button" class="btn btn-sm ${i === totalParticipants ? 'btn-secondary disabled' : 'btn-outline-secondary'} next-participant" ${i === totalParticipants ? 'disabled' : ''}>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                    <i class="fas fa-info-circle" title="Enter full name as it appears on ID"></i>
                                </label>
                                <input type="text" class="form-control participant-name" required>
                                <div class="invalid-feedback">
                                    Please enter the participant's full name
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                    <i class="fas fa-info-circle" title="Include country code (e.g., +1 for US)"></i>
                                </label>
                                <input type="tel" class="form-control participant-phone" required>
                                <div class="invalid-feedback">
                                    Please enter a phone number
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Email Address (optional)
                                </label>
                                <input type="email" class="form-control participant-email">
                            </div>
                        </div>
                    </div>
                `;

                    participantFormsContainer.insertAdjacentHTML('beforeend', participantForm);
                }

                // Add event listeners for navigation between participants
                document.querySelectorAll('.next-participant').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const currentForm = this.closest('.participant-form');
                        const currentIndex = parseInt(currentForm.dataset.participant);

                        if (currentIndex < totalParticipants) {
                            showParticipantForm(currentIndex + 1);
                        }
                    });
                });

                document.querySelectorAll('.prev-participant').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const currentForm = this.closest('.participant-form');
                        const currentIndex = parseInt(currentForm.dataset.participant);

                        if (currentIndex > 1) {
                            showParticipantForm(currentIndex - 1);
                        }
                    });
                });

                // Show only the first participant form initially
                showParticipantForm(1);
            }

            // Show a specific participant form
            function showParticipantForm(index) {
                document.querySelectorAll('.participant-form').forEach(form => {
                    form.style.display = 'none';
                });

                const formToShow = document.querySelector(`.participant-form[data-participant="${index}"]`);
                if (formToShow) {
                    formToShow.style.display = 'block';
                }
            }

            // Validate participant forms
            function validateParticipantForms() {
                let isValid = true;

                document.querySelectorAll('.participant-form').forEach(form => {
                    const nameInput = form.querySelector('.participant-name');
                    const phoneInput = form.querySelector('.participant-phone');

                    if (!nameInput.value.trim()) {
                        nameInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        nameInput.classList.remove('is-invalid');
                    }

                    if (!phoneInput.value.trim()) {
                        phoneInput.classList.add('is-invalid');
                        if (!phoneInput.nextElementSibling || !phoneInput.nextElementSibling.classList
                            .contains('invalid-feedback')) {
                            phoneInput.insertAdjacentHTML('afterend',
                                '<div class="invalid-feedback">Please enter a phone number</div>');
                        }
                        isValid = false;
                    } else {
                        phoneInput.classList.remove('is-invalid');
                    }
                });

                return isValid;
            }

            // Complete booking with direct redirect and soft modal
            completeBookingBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Validate terms agreement first
                const termsCheckbox = document.getElementById('terms-agree');
                if (!termsCheckbox.checked) {
                    termsCheckbox.nextElementSibling.classList.add('is-invalid');
                    document.querySelector('.terms-checkbox .invalid-feedback').style.display = 'block';
                    return;
                }

                // Store button text and state before changing
                completeBookingBtn.disabled = true;
                const originalText = completeBookingBtn.innerHTML;
                completeBookingBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                // Reset button automatically after 3 seconds as a failsafe
                const resetTimer = setTimeout(function() {
                    completeBookingBtn.disabled = false;
                    completeBookingBtn.innerHTML = originalText;
                }, 3000);

                // Gather booking data
                const quantity = parseInt(ticketQuantityInput.value);
                const activityId = {{ $activity->id }};
                const unitPrice = {{ $activity->price }};
                const total = quantity * unitPrice;

                // Get participant data
                const participants = [];
                document.querySelectorAll('.participant-form').forEach(form => {
                    participants.push({
                        name: form.querySelector('.participant-name').value,
                        phone: form.querySelector('.participant-phone').value,
                        email: form.querySelector('.participant-email').value
                    });
                });

                // Get payment info
                const paymentInfo = {
                    payment_method: 'credit_card',
                    card_number: document.getElementById('cardNumber').value,
                    cardholder_name: document.getElementById('cardholderName').value
                };

                // Create booking object
                const bookingData = {
                    activity_id: activityId,
                    quantity: quantity,
                    unit_price: unitPrice,
                    total: total,
                    activity_date: new Date().toISOString().split('T')[0], // Today's date
                    activity_time: '10:00:00', // Default time
                    participants: participants,
                    payment_info: paymentInfo
                };

                // Send booking data to server
                fetch('{{ route('api.activity-booking.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(bookingData),
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(data => {
                        clearTimeout(resetTimer);

                        if (data.success) {
                            // Hide modal
                            try {
                                modal.hide();
                            } catch (e) {
                                console.log("Modal hide error", e);
                            }

                            // Create a soft modal instead of alert
                            showBookingConfirmation();

                            // Set timer to redirect after showing modal
                            setTimeout(function() {
                                // Redirect to profile page
                                window.location.href =
                                    "{{ route('profile.index') }}#trips-section";
                            }, 2500);
                        } else {
                            // Show detailed error
                            console.error('Booking failed:', data);

                            // Show more detailed error message to user
                            let errorMessage = data.error || 'Unknown error';
                            if (data.details) {
                                errorMessage += ' - ' + data.details;
                            }

                            Swal.fire({
                                title: 'Booking Failed',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonColor: '#92400b'
                            });

                            completeBookingBtn.disabled = false;
                            completeBookingBtn.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing your booking. Please try again.');
                        completeBookingBtn.disabled = false;
                        completeBookingBtn.innerHTML = originalText;
                    });
            });

            // Function to show booking confirmation modal
            function showBookingConfirmation() {
                // Create a soft modal instead of alert
                const softModal = document.createElement('div');
                softModal.style.position = 'fixed';
                softModal.style.top = '0';
                softModal.style.left = '0';
                softModal.style.right = '0';
                softModal.style.bottom = '0';
                softModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                softModal.style.display = 'flex';
                softModal.style.alignItems = 'center';
                softModal.style.justifyContent = 'center';
                softModal.style.zIndex = '9999';

                // Create modal content
                const modalContent = document.createElement('div');
                modalContent.style.backgroundColor = 'white';
                modalContent.style.borderRadius = '12px';
                modalContent.style.padding = '30px';
                modalContent.style.textAlign = 'center';
                modalContent.style.maxWidth = '400px';
                modalContent.style.width = '90%';
                modalContent.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.2)';
                modalContent.style.animation = 'fadeIn 0.3s ease-out';

                // Add keyframes for animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(-20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                `;
                document.head.appendChild(style);

                // Add checkmark icon
                const checkIcon = document.createElement('div');
                checkIcon.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 12l2 2 6-6"></path></svg>';
                checkIcon.style.marginBottom = '20px';

                // Add title
                const title = document.createElement('h3');
                title.textContent = 'Booking Confirmed!';
                title.style.color = '#212529';
                title.style.fontSize = '1.5rem';
                title.style.fontWeight = '600';
                title.style.marginBottom = '10px';

                // Add message
                const message = document.createElement('p');
                message.textContent = 'Your booking has been completed successfully!';
                message.style.color = '#6c757d';
                message.style.marginBottom = '25px';

                // Add button
                const viewButton = document.createElement('button');
                viewButton.textContent = 'View My Bookings';
                viewButton.style.backgroundColor = '#92400b';
                viewButton.style.color = 'white';
                viewButton.style.border = 'none';
                viewButton.style.borderRadius = '8px';
                viewButton.style.padding = '12px 24px';
                viewButton.style.fontSize = '1rem';
                viewButton.style.fontWeight = '500';
                viewButton.style.cursor = 'pointer';
                viewButton.style.transition = 'all 0.2s ease';

                viewButton.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#793509';
                });

                viewButton.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '#92400b';
                });

                // Add all elements to modal content
                modalContent.appendChild(checkIcon);
                modalContent.appendChild(title);
                modalContent.appendChild(message);
                modalContent.appendChild(viewButton);
                softModal.appendChild(modalContent);

                // Add modal to body
                document.body.appendChild(softModal);

                // Also redirect when button is clicked
                viewButton.addEventListener('click', function() {
                    window.location.href = "{{ route('profile.index') }}#trips-section";
                });

                // In case redirection fails, add a fallback link
                const fallbackDiv = document.createElement('div');
                fallbackDiv.style.position = 'fixed';
                fallbackDiv.style.top = '20px';
                fallbackDiv.style.left = '0';
                fallbackDiv.style.right = '0';
                fallbackDiv.style.textAlign = 'center';
                fallbackDiv.style.zIndex = '9999';
                fallbackDiv.style.pointerEvents = 'none';
                fallbackDiv.innerHTML =
                    '<div style="display:inline-block; background:#28a745; color:white; padding:15px; border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.1); pointer-events:all;">' +
                    'If you\'re not redirected automatically, <a href="{{ route('profile.index') }}#trips-section" style="color:white; text-decoration:underline; font-weight:bold;">click here</a> to view your bookings</div>';
                fallbackDiv.style.opacity = '0';
                fallbackDiv.style.transition = 'opacity 0.5s ease';

                // Show fallback after 4 seconds if redirect doesn't happen
                setTimeout(function() {
                    fallbackDiv.style.opacity = '1';
                }, 4000);

                document.body.appendChild(fallbackDiv);
            }
        });
    </script>
@endpush
