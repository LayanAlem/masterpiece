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


    <style>
        body {
            padding-top: 60px;
        }

        /* Ensure dropdown arrows always display correctly */
        .dropdown-toggle::after {
            display: inline-block !important;
            width: 0 !important;
            height: 0 !important;
            margin-left: 0.255em !important;
            vertical-align: 0.255em !important;
            content: "" !important;
            border-top: 0.3em solid !important;
            border-right: 0.3em solid transparent !important;
            border-bottom: 0 !important;
            border-left: 0.3em solid transparent !important;
        }

        /* Fix any alignment issues */
        .nav-link.dropdown-toggle {
            display: flex !important;
            align-items: center !important;
        }
    </style>
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
                    <!-- Search Dropdown -->
                    <div class="dropdown me-3">
                        <a href="#" class="text-dark position-relative" id="searchDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="searchDropdown"
                            style="min-width: 300px;">
                            <form action="{{ route('search') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control"
                                        placeholder="Search activities, places..." required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="auth-buttons">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                        @else
                            <div class="dropdown">
                                <a href="#" class="nav-link d-flex align-items-center dropdown-toggle"
                                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <!-- Updated user profile image styling -->
                                    <div class="user-avatar me-2"
                                        style="width: 36px; height: 36px; overflow: hidden; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 2px solid #fff; display: flex; align-items: center; justify-content: center;">
                                        @if (Auth::user()->profile_image)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile"
                                                style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @else
                                            <div
                                                style="width: 100%; height: 100%; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; color: #6c757d;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span style="font-weight: 500; color: #333;">{{ Auth::user()->first_name }}
                                        {{ Auth::user()->last_name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown"
                                    style="border-radius: 8px; border: none; margin-top: 10px;">
                                    <li class="px-1">
                                        <a class="dropdown-item rounded" href="{{ route('profile.index') }}"
                                            style="padding: 8px 16px;">
                                            <i class="fas fa-user-circle me-2 text-primary"></i> My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li class="px-1">
                                        <a class="dropdown-item rounded" href="{{ route('logout') }}"
                                            style="padding: 8px 16px;"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout
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

    <!-- Footer Section -->
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
                        <a href="#" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link twitter"><i class="fab fa-twitter"></i></a>
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
    <script src="{{ asset('mainJS/main.js') }}"></script>
    <script src="{{ asset('mainJS/wishlist.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns with the standard Bootstrap way
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });

            // Remove custom event listeners that may be interfering with dropdowns
            const navDropdowns = document.querySelectorAll('.nav-item.dropdown');
            navDropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('.dropdown-toggle');
                // Remove any click handlers that may be interfering
                if (toggle) {
                    const newToggle = toggle.cloneNode(true);
                    toggle.parentNode.replaceChild(newToggle, toggle);
                }
            });
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
