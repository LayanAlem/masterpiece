<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-wide customizer-hide" dir="ltr"
    data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Visit Jo') }} - Reset Password</title>

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
            padding: 20px;
            margin: 0;
        }

        .authentication-wrapper {
            display: flex;
            flex-basis: 100%;
            min-height: 100vh;
            width: 100%;
            align-items: center;
            justify-content: center;
        }

        .authentication-inner {
            max-width: 450px;
            width: 100%;
        }

        .card {
            border: none;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            border-radius: 1.25rem;
            background-color: var(--white);
            overflow: hidden;
        }

        .card-body {
            padding: 2.5rem;
        }

        .app-brand {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }

        .app-brand-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.5rem;
        }

        .app-brand-logo  {
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
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .auth-subtitle {
            color: #697a8d;
            margin-bottom: 1.5rem;
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
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f6f9ff;
            border-color: #d9dee3;
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
            margin-top: 1.5rem;
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

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #fff2f2;
            border-color: #ffcacc;
            color: #dc3545;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .text-center {
            text-align: center;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper">
            <div class="authentication-inner">
                <!-- Reset Password Card -->
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
                        <h4 class="auth-title">Reset Password</h4>
                        <p class="auth-subtitle mb-5">Enter your email to receive a password reset link</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @error('email')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <form class="mb-3" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Enter your email" required
                                        autocomplete="email" autofocus />
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary  w-100" type="submit">
                                    <i class="bx bx-mail-send me-1"></i> Send Reset Link
                                </button>
                            </div>
                        </form>

                        <div class="auth-footer-links">
                            <a class="auth-link" href="{{ route('login') }}">
                                <i class="bx bx-chevron-left me-1"></i> Back to login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Reset Password Card -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
