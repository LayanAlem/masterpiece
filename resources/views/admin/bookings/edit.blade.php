@extends('admin.layouts.admin')

@section('title', 'Edit Booking')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Tourism / Bookings /</span> Edit Booking
        </h4>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Booking #{{ $booking->booking_number }}</h5>
                        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back to Details
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bookings.update', $booking->id) }}" id="editBookingForm">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Booking Number</label>
                                        <input type="text" class="form-control" value="{{ $booking->booking_number }}"
                                            readonly>
                                        <div class="form-text">Booking number cannot be modified</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Customer</label>
                                        <select id="user_id" name="user_id" class="form-select" required>
                                            <option value="">Select Customer</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Booking Status</label>
                                        <select id="status" name="status" class="form-select" required>
                                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="confirmed"
                                                {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="cancelled"
                                                {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="completed"
                                                {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status</label>
                                        <select id="payment_status" name="payment_status" class="form-select" required>
                                            <option value="pending"
                                                {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="paid"
                                                {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="refunded"
                                                {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded
                                            </option>
                                            <option value="failed"
                                                {{ $booking->payment_status == 'failed' ? 'selected' : '' }}>Failed
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="ticket_count" class="form-label">Total Tickets</label>
                                        <input type="number" id="ticket_count" name="ticket_count" class="form-control"
                                            value="{{ $booking->ticket_count }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="discount_amount" class="form-label">Discount Amount ($)</label>
                                        <input type="number" id="discount_amount" name="discount_amount"
                                            class="form-control" value="{{ $booking->discount_amount }}" min="0"
                                            step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="total_price" class="form-label">Total Price ($)</label>
                                        <input type="number" id="total_price" name="total_price" class="form-control"
                                            value="{{ $booking->total_price }}" min="0" step="0.01" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="loyalty_points_earned" class="form-label">Loyalty Points Earned</label>
                                        <input type="number" id="loyalty_points_earned" name="loyalty_points_earned"
                                            class="form-control" value="{{ $booking->loyalty_points_earned }}"
                                            min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="loyalty_points_used" class="form-label">Loyalty Points Used</label>
                                        <input type="number" id="loyalty_points_used" name="loyalty_points_used"
                                            class="form-control" value="{{ $booking->loyalty_points_used }}"
                                            min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="divider my-4">
                                <div class="divider-text">Booking Activities</div>
                            </div>

                            <div class="booking-items-container">
                                @foreach ($booking->activities as $index => $activity)
                                    <div class="booking-item card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h6 class="card-subtitle">Activity #{{ $index + 1 }}</h6>
                                                @if (count($booking->activities) > 1)
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-item-btn">
                                                        <i class="bx bx-trash me-1"></i> Remove
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="activities_{{ $index }}_activity_id"
                                                            class="form-label">Activity</label>
                                                        <select id="activities_{{ $index }}_activity_id"
                                                            name="activity_id[]" class="form-select" required>
                                                            <option value="">Select Activity</option>
                                                            @foreach ($activities as $activityOption)
                                                                <option value="{{ $activityOption->id }}"
                                                                    data-price="{{ $activityOption->price }}"
                                                                    {{ $activity->id == $activityOption->id ? 'selected' : '' }}>
                                                                    {{ $activityOption->name }} -
                                                                    ${{ number_format($activityOption->price, 2) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="activity_date_{{ $index }}"
                                                            class="form-label">Activity Date</label>
                                                        <input type="date" id="activity_date_{{ $index }}"
                                                            name="activity_date[]" class="form-control"
                                                            value="{{ $activity->pivot->activity_date ?? now()->format('Y-m-d') }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="quantity_{{ $index }}"
                                                            class="form-label">Quantity</label>
                                                        <input type="number" id="quantity_{{ $index }}"
                                                            name="quantity[]" class="form-control item-quantity"
                                                            value="{{ $activity->pivot->quantity ?? 1 }}" min="1"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="unit_price_{{ $index }}"
                                                            class="form-label">Unit Price ($)</label>
                                                        <input type="number" id="unit_price_{{ $index }}"
                                                            name="unit_price[]" class="form-control item-price"
                                                            value="{{ $activity->price ?? 0.0 }}" min="0"
                                                            step="0.01" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="subtotal_{{ $index }}"
                                                            class="form-label">Subtotal ($)</label>
                                                        <input type="number" id="subtotal_{{ $index }}"
                                                            name="subtotal[]" class="form-control item-subtotal"
                                                            value="{{ ($activity->pivot->quantity ?? 1) * ($activity->price ?? 0) }}"
                                                            min="0" step="0.01" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <button type="button" id="add-item-btn" class="btn btn-outline-primary">
                                    <i class="bx bx-plus me-1"></i> Add Another Activity
                                </button>
                            </div>

                            <div class="divider my-4">
                                <div class="divider-text">Summary</div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Activities Subtotal:</span>
                                                <span
                                                    id="items-subtotal">${{ number_format($booking->activities->sum('subtotal'), 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Discount:</span>
                                                <span
                                                    id="summary-discount">-${{ number_format($booking->discount_amount, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 fw-bold">
                                                <span>Total:</span>
                                                <span
                                                    id="summary-total">${{ number_format($booking->total_price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Booking</button>
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                    class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="booking-item-template">
        <div class="booking-item card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h6 class="card-subtitle">New Activity</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn">
                        <i class="bx bx-trash me-1"></i> Remove
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="activities_INDEX_activity_id" class="form-label">Activity</label>
                            <select id="activities_INDEX_activity_id" name="activity_id[]" class="form-select" required>
                                <option value="">Select Activity</option>
                                @foreach ($activities as $activity)
                                    <option value="{{ $activity->id }}" data-price="{{ $activity->price }}">
                                        {{ $activity->name }} - ${{ number_format($activity->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="activity_date_INDEX" class="form-label">Activity Date</label>
                            <input type="date" id="activity_date_INDEX" name="activity_date[]" class="form-control"
                                value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="quantity_INDEX" class="form-label">Quantity</label>
                            <input type="number" id="quantity_INDEX" name="quantity[]"
                                class="form-control item-quantity" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="unit_price_INDEX" class="form-label">Unit Price ($)</label>
                            <input type="number" id="unit_price_INDEX" name="unit_price[]"
                                class="form-control item-price" value="0.00" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="subtotal_INDEX" class="form-label">Subtotal ($)</label>
                            <input type="number" id="subtotal_INDEX" name="subtotal[]"
                                class="form-control item-subtotal" value="0.00" min="0" step="0.01" required
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingItemsContainer = document.querySelector('.booking-items-container');
            const addItemBtn = document.getElementById('add-item-btn');
            const bookingItemTemplate = document.getElementById('booking-item-template');
            const form = document.getElementById('editBookingForm');

            // Add new booking activity
            addItemBtn.addEventListener('click', function() {
                const itemsCount = document.querySelectorAll('.booking-item').length;
                const newIndex = itemsCount;

                // Clone template and replace INDEX placeholder
                const template = bookingItemTemplate.innerHTML;
                const newItem = template.replace(/INDEX/g, newIndex);

                // Create a wrapper and add the new item HTML
                const wrapper = document.createElement('div');
                wrapper.innerHTML = newItem;

                // Append the new item to container
                bookingItemsContainer.appendChild(wrapper.firstElementChild);

                // Initialize event listeners for the new item
                initItemEvents(bookingItemsContainer.lastElementChild);

                // Update summary calculation
                updateSummary();
            });

            // Initialize event listeners for existing items
            document.querySelectorAll('.booking-item').forEach(item => {
                initItemEvents(item);
            });

            // Initialize events for an item
            function initItemEvents(item) {
                // Remove item button
                const removeBtn = item.querySelector('.remove-item-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (document.querySelectorAll('.booking-item').length > 1) {
                            item.remove();
                            updateSummary();
                            renumberItems();
                        } else {
                            alert('At least one booking activity is required');
                        }
                    });
                }

                // Activity selection change
                const activitySelect = item.querySelector('[name="activity_id[]"]');
                if (activitySelect) {
                    activitySelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption && selectedOption.getAttribute('data-price')) {
                            const price = parseFloat(selectedOption.getAttribute('data-price'));
                            const priceInput = item.querySelector('.item-price');
                            if (priceInput) {
                                priceInput.value = price.toFixed(2);
                                updateItemSubtotal(item);
                            }
                        }
                    });
                }

                // Quantity and price change
                const quantityInput = item.querySelector('.item-quantity');
                const priceInput = item.querySelector('.item-price');

                if (quantityInput) {
                    quantityInput.addEventListener('change', function() {
                        updateItemSubtotal(item);
                    });
                }

                if (priceInput) {
                    priceInput.addEventListener('change', function() {
                        updateItemSubtotal(item);
                    });
                }
            }

            // Update item subtotal
            function updateItemSubtotal(item) {
                const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
                const price = parseFloat(item.querySelector('.item-price').value) || 0;
                const subtotal = quantity * price;

                const subtotalInput = item.querySelector('.item-subtotal');
                if (subtotalInput) {
                    subtotalInput.value = subtotal.toFixed(2);
                }

                updateSummary();
            }

            // Update booking summary
            function updateSummary() {
                let itemsSubtotal = 0;

                // Calculate items subtotal
                document.querySelectorAll('.item-subtotal').forEach(input => {
                    itemsSubtotal += parseFloat(input.value) || 0;
                });

                // Update discount and total
                const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
                const totalPrice = itemsSubtotal - discountAmount;

                // Update ticket count based on total quantity
                let totalQuantity = 0;
                document.querySelectorAll('.item-quantity').forEach(input => {
                    totalQuantity += parseInt(input.value) || 0;
                });
                document.getElementById('ticket_count').value = totalQuantity;

                // Update total price input
                document.getElementById('total_price').value = totalPrice.toFixed(2);

                // Update summary display
                document.getElementById('items-subtotal').textContent = '$' + itemsSubtotal.toFixed(2);
                document.getElementById('summary-discount').textContent = `-$${discountAmount.toFixed(2)}`;
                document.getElementById('summary-total').textContent = '$' + totalPrice.toFixed(2);
            }

            // Renumber item indices after removal
            function renumberItems() {
                const items = document.querySelectorAll('.booking-item');
                items.forEach((item, index) => {
                    const title = item.querySelector('.card-subtitle');
                    if (title) {
                        title.textContent = 'Activity #' + (index + 1);
                    }
                });
            }

            // Discount amount change
            document.getElementById('discount_amount').addEventListener('change', updateSummary);

            // Form submission
            form.addEventListener('submit', function(e) {
                const items = document.querySelectorAll('.booking-item');
                if (items.length === 0) {
                    e.preventDefault();
                    alert('At least one booking activity is required');
                    return false;
                }

                // Final update before submit
                updateSummary();
                return true;
            });

            // Initialize with current values
            updateSummary();
        });
    </script>
@endsection
