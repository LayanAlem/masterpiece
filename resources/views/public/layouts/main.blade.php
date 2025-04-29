<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visit Jordan</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Owl Carousel CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('mainStyle/index.css') }}">

    @stack('styles')

</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.index') }}">Visit Jordan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Services
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="{{ route('services') }}">All Services</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @foreach ($mainCategories as $category)
                                <li><a class="dropdown-item"
                                        href="{{ route('category.activities', ['categoryId' => $category->id]) }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hiddengem') }}">Hidden Gem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="#" class="text-dark me-3"><i class="fas fa-search"></i></a>

                    <!-- Booking Cart Dropdown -->
                    <div class="dropdown me-3">
                        <a class="text-dark position-relative" href="#" id="bookingCartDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calendar-check"></i>
                            @if (Auth::check() && isset($bookingCount) && $bookingCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-booking-count">
                                    {{ $bookingCount }}
                                    <span class="visually-hidden">pending bookings</span>
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end booking-dropdown p-3"
                            aria-labelledby="bookingCartDropdown" style="min-width: 300px;">
                            <h6 class="dropdown-header">Your Bookings</h6>

                            @if (Auth::check() && isset($pendingBookings) && count($pendingBookings) > 0)
                                <div class="booking-items">
                                    @foreach ($pendingBookings as $booking)
                                        <div class="booking-item d-flex align-items-center p-2 border-bottom">
                                            <div class="booking-image me-2">
                                                @if ($booking->activities->isNotEmpty() && $booking->activities->first()->image)
                                                    <img src="{{ asset('storage/' . $booking->activities->first()->image) }}"
                                                        alt="{{ $booking->activities->first()->name }}" width="40"
                                                        height="40" class="rounded">
                                                @else
                                                    <div class="placeholder-image bg-light rounded"
                                                        style="width:40px;height:40px;"></div>
                                                @endif
                                            </div>
                                            <div class="booking-details flex-grow-1">
                                                <div class="booking-title small fw-bold text-truncate">
                                                    {{ $booking->activities->isNotEmpty() ? $booking->activities->first()->name : 'Booking #' . $booking->booking_number }}
                                                </div>
                                                <div class="booking-info small text-muted">
                                                    {{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('M d, Y') : 'No date' }}
                                                </div>
                                            </div>
                                            <div class="booking-price fw-bold">
                                                ${{ number_format($booking->total_price, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="total-text">Total:</div>
                                    <div class="total-price fw-bold">
                                        ${{ number_format($pendingBookings->sum('total_price'), 2) }}</div>
                                </div>
                                <div class="d-grid gap-2 mt-3">
                                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-sm">Proceed to
                                        Checkout</a>
                                    <a href="{{ route('profile.index') }}#trips-section"
                                        class="btn btn-outline-secondary btn-sm">View All
                                        Bookings</a>
                                </div>
                            @else
                                <p class="text-center text-muted my-3">No pending bookings</p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('services') }}" class="btn btn-outline-primary btn-sm">Explore
                                        Activities</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="auth-buttons">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                        @else
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer-section">
        <div class="container">
            <!-- Main Footer Content -->
            <div class="row footer-main">
                <!-- Company Info Column -->
                <div class="col-lg-5 col-md-12 mb-4 mb-lg-0">
                    <div class="footer-brand mb-3">
                        <a href="#" class="navbar-brand">Visit Jordan</a>
                    </div>
                    <p class="footer-about">Discover the wonders of Jordan with our expertly curated tours
                        and experiences. From ancient ruins to desert adventures.</p>
                    <div class="footer-social mt-3">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-heading">Explore</h5>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Destinations</a></li>
                        <li><a href="#">Tours & Packages</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Newsletter Column -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Stay Connected</h5>
                    <p class="footer-newsletter-text">Subscribe for exclusive offers and updates</p>
                    <form class="footer-newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your Email">
                            <button class="btn newsletter-btn" type="submit">Subscribe</button>
                        </div>
                    </form>
                    <div class="support-contact mt-3">
                        <div class="support-phone">
                            <i class="fas fa-phone-alt"></i> +962 (0)6 123 4567
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer - Copyright -->
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p class="copyright-text">&copy; 2025 Visit Jordan. All rights reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <ul class="terms-links">
                            <li><a href="#">Terms</a></li>
                            <li><a href="#">Privacy</a></li>
                            <li><a href="#">FAQs</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset('mainJS/wishlist.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            })
        });
    </script>

    @stack('scripts')

    <!-- Wishlist Confirmation Modal -->
    <div class="modal fade modal-wishlist-confirm" id="appModal" tabindex="-1" aria-labelledby="appModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appModalLabel">Remove from Wishlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="fas fa-heart-broken"></i>
                    <p id="appModalBody">Are you sure you want to remove this activity from your wishlist?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="modalCancelBtn">Cancel</button>
                    <button type="button" class="btn btn-danger" id="modalConfirmBtn">Remove</button>
                </div>
            </div>
        </div>
    </div>

</body>


</html>
