<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-wide customizer-hide" dir="ltr"
    data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Visit Jo') }} - Register</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assetsAdmin/img/favicon/favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #92400b;
            --primary-dark: #793509;
            --secondary: #b85c38;
            --accent: #e09132;
            --light: #f7f1e5;
            --dark: #2d2424;
            --white: #ffffff;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            margin: 0;
            min-height: 100vh;
        }

        .authentication-wrapper {
            display: flex;
            flex-basis: 100%;
            width: 100%;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
            overflow-y: auto;
            max-height: 100vh;
        }

        .authentication-inner {
            max-width: 850px;
            width: 100%;
        }

        .card {
            border: none;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            border-radius: 1.25rem;
            background-color: var(--white);
            overflow: hidden;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .card-body {
            padding: 2rem;
        }

        .app-brand {
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
        }

        .app-brand-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.5rem;
        }

        .app-brand-logo {
            width: 40px;
            height: 40px;
        }

        .app-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .auth-title {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 0.25rem;
            font-size: 1.25rem;
        }

        .auth-subtitle {
            color: #697a8d;
            margin-bottom: 1rem;
        }

        .form-control {
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            border: 1px solid #d9dee3;
            background-color: #f6f9ff;
            transition: all 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(146, 64, 11, 0.1);
            background-color: var(--white);
        }

        .form-label {
            color: var(--dark);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .form-password-toggle .input-group-text {
            cursor: pointer;
            background-color: #f6f9ff;
            border-left: none;
            border-color: #d9dee3;
        }

        .input-group-merge .form-control:not(:last-child) {
            border-right: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group-merge .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .auth-footer-links {
            display: flex;
            justify-content: center;
            margin-top: 0.75rem;
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .auth-link:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        .form-check .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .alert-danger {
            background-color: #fff2f2;
            border-color: #ffcacc;
            color: #dc3545;
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background-color: #f6f9ff;
            border-color: #d9dee3;
        }

        /* Fix border radius on all input groups */
        .input-group .input-group-text:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }

        .input-group .form-control:not(:first-child):not(:last-child) {
            border-radius: 0;
        }

        .input-group .form-control:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }

        .input-group .form-control:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        .input-group .input-group-text:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        /* Override for merged input groups */
        .input-group-merge .form-control:not(:last-child) {
            border-right: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .file-upload {
            position: relative;
            width: 100%;
            height: 120px;
            border: 2px dashed #d9dee3;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
            background-color: #f6f9ff;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--primary);
        }

        .file-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .file-upload-text {
            color: #697a8d;
            font-size: 0.75rem;
        }

        .preview-image {
            max-width: 100%;
            max-height: 100px;
            display: none;
        }

        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background-color: rgba(247, 241, 229, 0.4);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--primary);
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: var(--primary-dark);
        }

        /* Firefox scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary) rgba(247, 241, 229, 0.4);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.25rem;
            }

            .file-upload {
                height: 100px;
            }
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.5rem;
            margin-left: -0.5rem;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .mb-4 {
            margin-bottom: 1rem !important;
        }

        .mb-5 {
            margin-bottom: 1.25rem !important;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .authentication-inner {
                max-width: 700px;
            }
        }

        @media (max-width: 768px) {
            .authentication-inner {
                max-width: 100%;
                padding: 0 15px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .file-upload {
                height: 100px;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .auth-title {
                font-size: 1.2rem;
            }

            .app-brand-text {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.25rem;
            }

            .auth-subtitle {
                margin-bottom: 0.75rem;
            }

            .mb-5 {
                margin-bottom: 0.75rem !important;
            }

            .file-upload {
                height: 80px;
            }

            .file-upload-icon {
                font-size: 1.5rem;
            }

            .file-upload-text {
                font-size: 0.7rem;
            }
        }

        /* Fix border radius on password inputs */
        .input-group-merge .form-control:not(:last-child) {
            border-right: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group-merge .input-group-text:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }

        .input-group-merge .input-group-text:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
    </style>
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand">
                            <a href="/" class="app-brand-link">
                                <span class="app-brand-logo d-flex justify-content-center align-items-center ">
                                    <img src="{{ asset('assetsAdmin/img/favicon/favicon.png') }}" alt="Visit Jo Logo"
                                        class="img-fluid">
                                </span>
                                <span class="app-brand-text">Visit Jo</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="auth-title">Join our community</h4>
                        <p class="auth-subtitle mb-5">Create your account to get started</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="mb-3" method="POST" action="{{ route('register') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name" value="{{ old('first_name') }}"
                                            placeholder="Enter your first name" required autocomplete="first_name"
                                            autofocus />
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text"
                                            class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                                            name="last_name" value="{{ old('last_name') }}"
                                            placeholder="Enter your last name" required autocomplete="last_name" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Enter your email" required autocomplete="email" />
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}"
                                            placeholder="Enter your phone number" autocomplete="tel" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                        <input type="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            name="password" required autocomplete="new-password"
                                            placeholder="Create a password" />
                                        <span class="input-group-text cursor-pointer toggle-password"><i
                                                class="bx bx-hide"></i></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="password-confirm">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                        <input type="password" id="password-confirm" class="form-control"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm your password" />
                                        <span class="input-group-text cursor-pointer toggle-confirm"><i
                                                class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-gift"></i></span>
                                        <input type="text"
                                            class="form-control @error('referral_code') is-invalid @enderror"
                                            id="referral_code" name="referral_code"
                                            value="{{ old('referral_code') }}"
                                            placeholder="Enter referral code (if any)" />
                                    </div>
                                    <div class="form-text text-muted">
                                        If someone referred you, enter their code here
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <div class="file-upload">
                                        <input type="file" id="profile_image" name="profile_image"
                                            class="@error('profile_image') is-invalid @enderror" accept="image/*"
                                            onchange="previewImage(this)" />
                                        <i class="bx bx-upload file-upload-icon"></i>
                                        <p class="file-upload-text">Click to upload or drag and drop</p>
                                        <img id="preview" class="preview-image" src="#" alt="Preview" />
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary w-100" type="submit">Sign up</button>
                            </div>
                        </form>

                        <div class="auth-footer-links">
                            <div class="text-center">
                                <span class="me-1">Already have an account?</span>
                                <a class="auth-link" href="{{ route('login') }}">
                                    <span>Sign in</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Register Card -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password validation criteria
        const passwordCriteria = [{
                regex: /.{8,}/,
                label: 'At least 8 characters'
            },
            {
                regex: /[A-Z]/,
                label: 'At least one uppercase letter'
            },
            {
                regex: /[a-z]/,
                label: 'At least one lowercase letter'
            },
            {
                regex: /[0-9]/,
                label: 'At least one number'
            },
            {
                regex: /[^A-Za-z0-9]/,
                label: 'At least one special character'
            }
        ];

        // Create validation feedback element
        const createPasswordFeedback = () => {
            const passwordGroup = document.querySelector('#password').closest('.mb-3');

            // Check if feedback already exists
            if (document.querySelector('#password-validation-feedback')) {
                return;
            }

            // Create the container
            const feedbackContainer = document.createElement('div');
            feedbackContainer.id = 'password-validation-feedback';
            feedbackContainer.className = 'password-validation-feedback mt-2';

            // Add style for the feedback items
            const style = document.createElement('style');
            style.textContent = `
                .password-validation-feedback {
                    font-size: 0.8rem;
                }
                .password-criteria {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                    margin-top: 0.5rem;
                }
                .criteria-item {
                    display: flex;
                    align-items: center;
                    padding: 0.25rem 0.5rem;
                    border-radius: 0.25rem;
                    background-color: #f8f9fa;
                    transition: all 0.2s;
                }
                .criteria-item i {
                    margin-right: 0.25rem;
                }
                .criteria-invalid {
                    background-color: rgba(220, 53, 69, 0.1);
                    color: #dc3545;
                }
                .criteria-valid {
                    background-color: rgba(25, 135, 84, 0.1);
                    color: #198754;
                }
                .password-match-feedback {
                    margin-top: 0.5rem;
                    font-size: 0.8rem;
                }
                .invalid-feedback {
                    display: block;
                    width: 100%;
                    margin-top: 0.25rem;
                    font-size: 0.875rem;
                    color: #dc3545;
                }
                .is-invalid {
                    border-color: #dc3545 !important;
                }
                .is-valid {
                    border-color: #198754 !important;
                }
            `;
            document.head.appendChild(style);

            // Create criteria list
            const criteriaList = document.createElement('div');
            criteriaList.className = 'password-criteria';

            // Add each criteria item
            passwordCriteria.forEach((criteria, index) => {
                const item = document.createElement('div');
                item.className = 'criteria-item criteria-invalid';
                item.id = `criteria-${index}`;
                item.innerHTML = `<i class="bx bx-x"></i>${criteria.label}`;
                criteriaList.appendChild(item);
            });

            feedbackContainer.appendChild(criteriaList);
            passwordGroup.appendChild(feedbackContainer);

            // Create password match feedback
            const confirmPasswordGroup = document.querySelector('#password-confirm').closest('.mb-3');
            const matchFeedback = document.createElement('div');
            matchFeedback.id = 'password-match-feedback';
            matchFeedback.className = 'password-match-feedback invalid-feedback';
            matchFeedback.textContent = 'Passwords do not match';
            matchFeedback.style.display = 'none';
            confirmPasswordGroup.appendChild(matchFeedback);
        };

        // Validate password against criteria
        const validatePassword = (password) => {
            let isValid = true;

            passwordCriteria.forEach((criteria, index) => {
                const criteriaElement = document.getElementById(`criteria-${index}`);
                const isMatch = criteria.regex.test(password);

                criteriaElement.className = isMatch ?
                    'criteria-item criteria-valid' :
                    'criteria-item criteria-invalid';

                criteriaElement.innerHTML = isMatch ?
                    `<i class="bx bx-check"></i>${criteria.label}` :
                    `<i class="bx bx-x"></i>${criteria.label}`;

                if (!isMatch) isValid = false;
            });

            return isValid;
        };

        // Check if passwords match
        const validatePasswordMatch = () => {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password-confirm').value;
            const matchFeedback = document.getElementById('password-match-feedback');
            const confirmInput = document.getElementById('password-confirm');

            if (confirmPassword === '') {
                matchFeedback.style.display = 'none';
                confirmInput.classList.remove('is-valid', 'is-invalid');
                return false;
            }

            const doMatch = password === confirmPassword;

            matchFeedback.style.display = doMatch ? 'none' : 'block';

            if (doMatch) {
                confirmInput.classList.add('is-valid');
                confirmInput.classList.remove('is-invalid');
            } else {
                confirmInput.classList.add('is-invalid');
                confirmInput.classList.remove('is-valid');
            }

            return doMatch;
        };

        // Form validation
        const validateForm = () => {
            const passwordValid = validatePassword(document.getElementById('password').value);
            const passwordsMatch = validatePasswordMatch();

            // Enable/disable submit button based on validation
            const submitButton = document.querySelector('button[type="submit"]');
            if (passwordValid && passwordsMatch) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }

            return passwordValid && passwordsMatch;
        };

        // Execute when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Create validation feedback elements
            createPasswordFeedback();

            // Password input event listener
            document.getElementById('password').addEventListener('input', function() {
                const isValid = validatePassword(this.value);

                if (isValid) {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }

                // Also check password match if confirm field has value
                if (document.getElementById('password-confirm').value !== '') {
                    validatePasswordMatch();
                }

                validateForm();
            });

            // Confirm password input event listener
            document.getElementById('password-confirm').addEventListener('input', function() {
                validatePasswordMatch();
                validateForm();
            });

            // Form submission handling
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();

                    // Show general error message
                    let errorContainer = document.querySelector('.alert.alert-danger');
                    if (!errorContainer) {
                        errorContainer = document.createElement('div');
                        errorContainer.className = 'alert alert-danger';
                        errorContainer.innerHTML = '<ul class="mb-0"></ul>';
                        this.insertBefore(errorContainer, this.firstChild);
                    }

                    const errorList = errorContainer.querySelector('ul');
                    errorList.innerHTML = '';

                    if (!validatePassword(document.getElementById('password').value)) {
                        const li = document.createElement('li');
                        li.textContent = 'Password does not meet the requirements.';
                        errorList.appendChild(li);
                    }

                    if (!validatePasswordMatch()) {
                        const li = document.createElement('li');
                        li.textContent = 'Passwords do not match.';
                        errorList.appendChild(li);
                    }
                }
            });

            // Field-specific validation for other fields
            const validateField = (field) => {
                const input = document.getElementById(field);
                if (!input) return;

                input.addEventListener('input', function() {
                    if (this.value.trim() === '' && this.hasAttribute('required')) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');

                        // Add feedback if not exists
                        let feedback = this.parentNode.querySelector('.invalid-feedback');
                        if (!feedback) {
                            feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = `${this.getAttribute('placeholder')} is required.`;
                            this.parentNode.appendChild(feedback);
                        }
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');

                        // Remove feedback if exists
                        const feedback = this.parentNode.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.remove();
                        }
                    }
                });
            };

            // Apply validation to required fields
            ['first_name', 'last_name', 'email', 'phone'].forEach(validateField);

            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const isValid = emailRegex.test(this.value);

                    let feedback = this.parentNode.querySelector('.invalid-feedback');

                    if (this.value.trim() !== '' && !isValid) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');

                        // Add feedback if not exists
                        if (!feedback) {
                            feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = 'Please enter a valid email address.';
                            this.parentNode.appendChild(feedback);
                        } else {
                            feedback.textContent = 'Please enter a valid email address.';
                        }
                    }
                });
            }

            // Initial form validation
            validateForm();
        });

        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.querySelector('#password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        });

        // Toggle confirm password visibility
        document.querySelector('.toggle-confirm').addEventListener('click', function() {
            const passwordInput = document.querySelector('#password-confirm');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        });

        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const fileUploadIcon = document.querySelector('.file-upload-icon');
            const fileUploadText = document.querySelector('.file-upload-text');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    fileUploadIcon.style.display = 'none';
                    fileUploadText.style.display = 'none';
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                fileUploadIcon.style.display = 'block';
                fileUploadText.style.display = 'block';
            }
        }
    </script>
</body>

</html>
