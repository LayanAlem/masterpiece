<!DOCTYPE html>

<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assetsAdmin-path="../assetsAdmin/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title', 'Admin Dashboard')</title>

    {{-- <meta name="description" content="" /> --}}

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assetsAdmin/img/favicon/favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assetsAdmin/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsAdmin/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assetsAdmin/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assetsAdmin/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assetsAdmin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assetsAdmin/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assetsAdmin/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo d-flex justify-content-center align-items-center  demo">
                <img style="width: 40px" src="{{ asset('assetsAdmin/img/favicon/favicon.png') }}" alt="logo" class="img-fluid" />
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2">Visit Jo</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
              <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ Route::is('admins.index') ? 'active' : '' }}">
              <a href="{{ route('admins.index') }}" class="menu-link">
                <i class='bx bx-support me-1'></i>
                <div data-i18n="Analytics">Admins</div>
              </a>
            </li>
            <li class="menu-item {{ Route::is('users.index') ? 'active' : '' }}">
              <a href="{{ route('users.index') }}" class="menu-link">
                <i class="bx bx-user me-1"></i>
                <div data-i18n="Analytics">Users</div>
              </a>
            </li>

            <!-- Layouts -->
            <li class="menu-item {{ Route::is('main-categories.index') ? 'active' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="Layouts">Categories</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route('main-categories.index') }}" class="menu-link">
                    <div data-i18n="Without menu">Main Categories</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('main-categories.trashed') }}" class="menu-link">
                    <div data-i18n="Without navbar">Deleted Categories</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('category-types.index') }}" class="menu-link">
                    <div data-i18n="Without navbar">Categories Types (Sub)</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="#" class="menu-link">
                    <div data-i18n="Without navbar">Deleted Types</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item {{ Route::is('activities.index') ? 'active' : '' }}">
                <a href="{{ route('activities.index') }}" class="menu-link">
                    <i class='bx bx-run me-1'></i>
                  <div data-i18n="Analytics">Activities</div>
                </a>
            </li>

              <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='bx bx-diamond me-1'></i>
                  <div data-i18n="Layouts">Hidden Gem</div>
                </a>

                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="layouts-without-menu.html" class="menu-link">
                      <div data-i18n="Without menu">Posts</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="layouts-without-navbar.html" class="menu-link">
                      <div data-i18n="Without navbar">Comments</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="layouts-without-navbar.html" class="menu-link">
                      <div data-i18n="Without navbar">Reported Comments</div>
                    </a>
                  </li>
                </ul>
              </li>

              <li class="menu-item {{ Route::is('restaurants.*') ? 'active' : '' }}">
                <a href="{{ route('restaurants.index') }}" class="menu-link">
                    <i class='bx bx-restaurant me-1'></i>
                    <div data-i18n="Analytics">Restaurants</div>
                </a>
              </li>

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Bookings and Reviews</span>
            </li>


            <li class="menu-item {{ Route::is('bookings.index') ? 'active' : '' }}">
                <a href="{{ route('bookings.index') }}" class="menu-link">
                    <i class='bx bx-calendar-check me-1' ></i>
                  <div data-i18n="Analytics">Bookings</div>
                </a>
            </li>
            <li class="menu-item {{ Route::is('reviews.*') ? 'active' : '' }}">
                <a href="{{ route('reviews.index') }}" class="menu-link">
                    <i class='bx bx-comment-edit me-1'></i>
                  <div data-i18n="Analytics">Reviews</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="index.html" class="menu-link">
                    <i class='bx bx-credit-card me-1'></i>
                  <div data-i18n="Analytics">Payments</div>
                </a>
            </li>

            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span></li>
            <!-- Cards -->
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='bx bx-star me-1'></i>
                  <div data-i18n="Layouts">Royalty Program</div>
                </a>

                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="layouts-without-menu.html" class="menu-link">
                      <div data-i18n="Without menu">Points Management</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="layouts-without-menu.html" class="menu-link">
                      <div data-i18n="Without menu">Total Points</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="layouts-without-navbar.html" class="menu-link">
                      <div data-i18n="Without navbar">Used Points</div>
                    </a>
                  </li>
                </ul>
              </li>
            <!-- User interface -->
            <li class="menu-item">
                <a href="index.html" class="menu-link">
                    <i class='bx bx-user-voice me-1'></i>
                  <div data-i18n="Analytics">Referals</div>
                </a>
            </li>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <!-- Navbar -->
<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar"
>
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Page Title with breadcrumb -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb rounded-3 p-2 mb-0 bg-light">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt text-primary"></i></a></li>
            @php
                $routeName = Route::currentRouteName();
                $routeSegments = explode('.', $routeName);
                $lastSegment = end($routeSegments);
                $secondSegment = count($routeSegments) > 1 ? $routeSegments[count($routeSegments) - 2] : null;
            @endphp

            @if ($secondSegment && $secondSegment !== 'admin')
<li class="breadcrumb-item">
                <a href="{{ route($secondSegment . '.index') }}">{{ ucfirst($secondSegment) }}</a>
              </li>
@endif

            <li class="breadcrumb-item active">{{ ucfirst($lastSegment) }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Quick Links Dropdown -->
      <li class="nav-item dropdown me-3">
        <a class="nav-link dropdown-toggle btn btn-sm btn-primary d-flex align-items-center text-white" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="bx bx-plus me-1"></i> <span class="text-white">Quick Links</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end py-0">
          <li>
            <a class="dropdown-item" href="{{ route('activities.create') }}">
              <i class="bx bx-run me-1 text-primary"></i> Add Activity
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('restaurants.create') }}">
              <i class="bx bx-restaurant me-1 text-primary"></i> Add Restaurant
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('main-categories.create') }}">
              <i class="bx bx-category me-1 text-primary"></i> Add Main Category
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('category-types.create') }}">
              <i class="bx bx-category-alt me-1 text-primary"></i> Add Sub Category
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="{{ url('/') }}" target="_blank">
              <i class="bx bx-globe me-1 text-primary"></i> View Website
            </a>
          </li>
        </ul>
      </li>

      <!-- User Dropdown - Simplified -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online me-2">
            <img src="{{ asset('assetsAdmin/img/avatars/1.jpg') }}" alt class="w-px-40 h-auto rounded-circle border" />
          </div>
          <div class="d-none d-md-block">
            <span class="fw-semibold">{{ Auth::guard('admin')->user()->name ?? 'Admin User' }}</span>
            <small class="text-muted d-block">{{ Auth::guard('admin')->user()->role ?? 'Administrator' }}</small>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off me-2 text-danger"></i>
              <span class="align-middle">Log Out</span>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
  </div>
</nav>
          <!-- / Navbar -->
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                      document.write(new Date().getFullYear());
                  </script>
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">Visit Jo</a>
                </div>
                <div>
                    <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">Documentation</a>
                  <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">Main Site</a>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assetsAdmin/vendor/js/core.js -->
    <script src="{{ asset('assetsAdmin/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assetsAdmin/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assetsAdmin/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assetsAdmin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assetsAdmin/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assetsAdmin/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assetsAdmin/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assetsAdmin/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Remove this duplicate Bootstrap JS that might cause conflicts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <!-- Add this to ensure dropdowns work -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });

            // Initialize all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
  </body>
</html>
