@extends('admin.layouts.admin')

@section('title', 'Create Booking')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold m-0">
                                    <i class="bx bx-calendar-plus me-1 text-primary"></i> Create Booking
                                </h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 mt-1">
                                        <li class="breadcrumb-item"><a href="#">Tourism</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Bookings</a></li>
                                        <li class="breadcrumb-item active">Create</li>
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-1"></i> Back to Bookings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-1"></i>
                <strong>Error!</strong> Please check the form for errors.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="card-title mb-0">Booking Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST" id="createBookingForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body">
                                    <h6 class="mb-3 text-primary">User Information</h6>

                                    <div class="mb-3">
                                        <label class="form-label" for="user_id">Select User</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                                            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                                <option value="">Select a user</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="create_new_user" name="create_new_user">
                                            <label class="form-check-label" for="create_new_user">
                                                Create a new user instead
                                            </label>
                                        </div>
                                    </div>

                                    <div id="new_user_fields" class="d-none">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="first_name">First Name</label>
                                                <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="last_name">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                                                @error('last_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email Address</label>
                                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Phone Number</label>
                                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body">
                                    <h6 class="mb-3 text-primary">Booking Details</h6>

                                    <div class="mb-3">
                                        <label class="form-label" for="status">Booking Status</label>
                                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="ticket_count">Number of Seats</label>
                                        <input type="number" id="ticket_count" name="ticket_count" min="1" class="form-control @error('ticket_count') is-invalid @enderror" value="{{ old('ticket_count', 1) }}">
                                        @error('ticket_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="booking_date">Booking Date</label>
                                        <input type="date" id="booking_date" name="booking_date" class="form-control @error('booking_date') is-invalid @enderror" value="{{ old('booking_date', date('Y-m-d')) }}">
                                        @error('booking_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="notes">Additional Notes</label>
                                        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="mb-0">Activity Selection</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0 mb-4">
                                <i class="bx bx-info-circle me-1"></i>
                                Select at least one activity for this booking.
                            </div>

                            <div class="row">
                                @forelse($activities as $activity)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-body p-3">
                                                <div class="form-check">
                                                    <input class="form-check-input activity-checkbox" type="checkbox"
                                                           name="activities[]" value="{{ $activity->id }}"
                                                           id="activity-{{ $activity->id }}"
                                                           {{ (is_array(old('activities')) && in_array($activity->id, old('activities'))) ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100" for="activity-{{ $activity->id }}">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="me-2" style="width: 40px; height: 40px;">
                                                                @if($activity->image)
                                                                    <img src="{{ asset('storage/' . $activity->image) }}" class="img-fluid rounded"
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                                                         style="width: 40px; height: 40px;">
                                                                        <i class="bx bx-map-pin text-primary" style="font-size: 20px;"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $activity->name }}</h6>
                                                                <small class="text-muted">{{ $activity->location }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="small mb-2">{{ Str::limit($activity->description, 120) }}</div>
                                                        <div class="d-flex justify-content-between">
                                                            <span class="badge bg-label-info">
                                                                <i class="bx bx-time me-1"></i> {{ $activity->duration }} hours
                                                            </span>
                                                            <span class="fw-semibold text-primary">${{ number_format($activity->price, 2) }}</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            <i class="bx bx-error-circle me-1"></i>
                                            No activities available. Please <a href="{{ route('activities.create') }}">create activities</a> first.
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            @error('activities')
                                <div class="text-danger mt-2">
                                    <i class="bx bx-error-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i> Create Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle user fields based on checkbox
            const createNewUserCheckbox = document.getElementById('create_new_user');
            const userIdSelect = document.getElementById('user_id');
            const newUserFields = document.getElementById('new_user_fields');

            createNewUserCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    newUserFields.classList.remove('d-none');
                    userIdSelect.disabled = true;
                    userIdSelect.required = false;
                } else {
                    newUserFields.classList.add('d-none');
                    userIdSelect.disabled = false;
                    userIdSelect.required = true;
                }
            });

            // Form validation
            const form = document.getElementById('createBookingForm');
            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Check if at least one activity is selected
                const activityCheckboxes = document.querySelectorAll('.activity-checkbox:checked');
                if (activityCheckboxes.length === 0) {
                    isValid = false;
                    event.preventDefault();

                    // Create alert if it doesn't exist
                    let activityAlert = document.querySelector('.activity-alert');
                    if (!activityAlert) {
                        activityAlert = document.createElement('div');
                        activityAlert.className = 'alert alert-danger mt-3 activity-alert';
                        activityAlert.innerHTML = '<i class="bx bx-error-circle me-1"></i> Please select at least one activity';

                        const activitiesCard = document.querySelector('.card-body:has(.activity-checkbox)');
                        activitiesCard.appendChild(activityAlert);
                    }
                }

                // Validate user selection or creation
                if (createNewUserCheckbox.checked) {
                    const firstName = document.getElementById('first_name').value;
                    const lastName = document.getElementById('last_name').value;
                    const email = document.getElementById('email').value;

                    if (!firstName || !lastName || !email) {
                        isValid = false;
                        event.preventDefault();
                    }
                } else {
                    if (!userIdSelect.value) {
                        isValid = false;
                        event.preventDefault();
                    }
                }

                // Scroll to first error if form is invalid
                if (!isValid) {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
@endsection
