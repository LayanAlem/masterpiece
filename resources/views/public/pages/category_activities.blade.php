@extends('public.layouts.main')
@section('title', $category->name . ' Activities')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/activities.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
@endpush

@section('content')
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Category Header Section -->
    <section class="category-header">
        <div class="container">
            <h1 class="category-title">{{ $category->name }}</h1>
            <p class="category-description">{{ $category->description }}</p>

            <!-- Search Bar -->
            <div class="search-container mt-4">
                <form action="{{ route('category.activities', ['categoryId' => $category->id]) }}" method="GET"
                    id="search-form">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for activities..." name="search"
                            value="{{ request('search') }}">
                        <button class="btn search-btn" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Category Types Filter Section -->
    <section class="category-types-section">
        <div class="container">
            <div class="text-center">
                <a href="{{ route('category.activities', ['categoryId' => $category->id]) }}"
                    class="category-type-tag {{ !request()->has('type') ? 'active' : '' }}">All Types</a>
                @foreach ($category->categoryTypes as $type)
                    <a href="{{ route('category.activities', ['categoryId' => $category->id, 'type' => $type->id]) }}"
                        class="category-type-tag {{ request('type') == $type->id ? 'active' : '' }}">{{ $type->name }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex gap-2 flex-wrap">
                            <!-- Date filter dropdown -->
                            <div class="dropdown">
                                <button class="filter-btn dropdown-toggle" type="button" id="dateFilterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Date <i class="fas fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dateFilterDropdown">
                                    <li><a class="dropdown-item {{ request('date_filter') == 'upcoming' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['date_filter', 'page']), ['categoryId' => $category->id, 'date_filter' => 'upcoming'])) }}">All
                                            Upcoming</a></li>
                                    <li><a class="dropdown-item {{ request('date_filter') == 'this_week' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['date_filter', 'page']), ['categoryId' => $category->id, 'date_filter' => 'this_week'])) }}">This
                                            Week</a></li>
                                    <li><a class="dropdown-item {{ request('date_filter') == 'this_month' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['date_filter', 'page']), ['categoryId' => $category->id, 'date_filter' => 'this_month'])) }}">This
                                            Month</a></li>
                                    <li><a class="dropdown-item {{ request('date_filter') == 'three_months' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['date_filter', 'page']), ['categoryId' => $category->id, 'date_filter' => 'three_months'])) }}">Next
                                            3 Months</a></li>
                                </ul>
                            </div>

                            <!-- Price filter dropdown -->
                            <div class="dropdown">
                                <button class="filter-btn dropdown-toggle" type="button" id="priceFilterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Price <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu price-dropdown p-3" aria-labelledby="priceFilterDropdown">
                                    <h6 class="dropdown-header">Price Range</h6>
                                    <div class="price-slider-container px-2">
                                        <div id="price-slider"></div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span id="price-min">${{ $priceRange->min_price ?? 0 }}</span>
                                            <span id="price-max">${{ $priceRange->max_price ?? 1000 }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <button id="apply-price-filter" class="btn btn-sm btn-primary">Apply</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort by dropdown -->
                            <div class="dropdown">
                                <button class="filter-btn dropdown-toggle" type="button" id="sortDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Sort By <i class="fas fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item {{ request('sort_by') == 'start_date' && request('sort_dir') == 'asc' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['categoryId' => $category->id, 'sort_by' => 'start_date', 'sort_dir' => 'asc'])) }}">Date
                                            (Earliest First)</a></li>
                                    <li><a class="dropdown-item {{ request('sort_by') == 'price' && request('sort_dir') == 'asc' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['categoryId' => $category->id, 'sort_by' => 'price', 'sort_dir' => 'asc'])) }}">Price
                                            (Low to High)</a></li>
                                    <li><a class="dropdown-item {{ request('sort_by') == 'price' && request('sort_dir') == 'desc' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['categoryId' => $category->id, 'sort_by' => 'price', 'sort_dir' => 'desc'])) }}">Price
                                            (High to Low)</a></li>
                                    <li><a class="dropdown-item {{ request('sort_by') == 'name' && request('sort_dir') == 'asc' ? 'active' : '' }}"
                                            href="{{ route('category.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['categoryId' => $category->id, 'sort_by' => 'name', 'sort_dir' => 'asc'])) }}">Name
                                            (A-Z)</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- View type buttons -->
                        <div class="view-buttons">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Active filters -->
                    @if (request()->except(['categoryId', 'page']))
                        <div class="active-filters mt-3">
                            <span class="active-filters-label">Active Filters:</span>
                            @if (request('search'))
                                <span class="active-filter-tag">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ route('category.activities', array_merge(request()->except(['search', 'page']), ['categoryId' => $category->id])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif
                            @if (request('type'))
                                @php
                                    $typeName =
                                        $category->categoryTypes->where('id', request('type'))->first()->name ?? 'Type';
                                @endphp
                                <span class="active-filter-tag">
                                    Type: {{ $typeName }}
                                    <a href="{{ route('category.activities', array_merge(request()->except(['type', 'page']), ['categoryId' => $category->id])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif
                            @if (request('date_filter'))
                                <span class="active-filter-tag">
                                    Date: {{ ucfirst(str_replace('_', ' ', request('date_filter'))) }}
                                    <a href="{{ route('category.activities', array_merge(request()->except(['date_filter', 'page']), ['categoryId' => $category->id])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif
                            @if (request('min_price') || request('max_price'))
                                <span class="active-filter-tag">
                                    Price: ${{ request('min_price', 0) }} - ${{ request('max_price', 1000) }}
                                    <a href="{{ route('category.activities', array_merge(request()->except(['min_price', 'max_price', 'page']), ['categoryId' => $category->id])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif

                            <a href="{{ route('category.activities', ['categoryId' => $category->id]) }}"
                                class="clear-all-filters">Clear All</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Tours Section -->
    <section class="tours-section">
        <div class="container">
            @if ($activities->count() > 0)
                <div class="row" id="activities-container">
                    @foreach ($activities as $activity)
                        <div class="col-lg-4 col-md-6 mb-4 activity-item">
                            <div class="tour-card">
                                <div class="tour-image">
                                    <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'api/placeholder/800/500') }}"
                                        alt="{{ $activity->name }}">
                                    <button class="favorite-btn" data-activity-id="{{ $activity->id }}">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <div class="location-tag">
                                        <i class="fas fa-map-marker-alt"></i> {{ $activity->location }}
                                    </div>
                                </div>
                                <div class="tour-content">
                                    <h3 class="tour-title">{{ $activity->name }}</h3>
                                    <p class="tour-description">{{ Str::limit($activity->description, 100) }}</p>
                                    <div class="tour-meta">
                                        <div class="rating">
                                            <i class="fas fa-star"></i>
                                            <span
                                                class="rating-score">{{ number_format($activity->average_rating, 1) }}</span>
                                            <span class="reviews-count">({{ $activity->reviews_count }})</span>
                                        </div>
                                        <div class="duration">
                                            <i class="far fa-clock"></i>
                                            <span>{{ $activity->formatted_duration }}</span>
                                        </div>
                                    </div>
                                    <div class="tour-footer">
                                        <div class="price">
                                            ${{ number_format($activity->price, 0) }}<span
                                                class="per-person">/person</span>
                                        </div>
                                        <a href="{{ route('activity.detail', $activity->id) }}" class="btn book-btn">Book
                                            Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row">
                    <div class="col-12">
                        {{ $activities->links() }}
                    </div>
                </div>
            @else
                <div class="no-results">
                    <i class="far fa-sad-tear"></i>
                    <h3>No Activities Found</h3>
                    <p>We couldn't find any activities matching your criteria. Please try adjusting your filters or check
                        back later.</p>
                    <a href="{{ route('category.activities', ['categoryId' => $category->id]) }}"
                        class="btn book-btn">Clear Filters</a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize price slider
            const priceSlider = document.getElementById('price-slider');
            if (priceSlider) {
                const minPrice = {{ $priceRange->min_price ?? 0 }};
                const maxPrice = {{ $priceRange->max_price ?? 1000 }};
                const currentMinPrice = {{ request('min_price', $priceRange->min_price ?? 0) }};
                const currentMaxPrice = {{ request('max_price', $priceRange->max_price ?? 1000) }};

                noUiSlider.create(priceSlider, {
                    start: [currentMinPrice, currentMaxPrice],
                    connect: true,
                    range: {
                        'min': minPrice,
                        'max': maxPrice
                    },
                    step: 10,
                    format: {
                        to: function(value) {
                            return Math.round(value);
                        },
                        from: function(value) {
                            return Number(value);
                        }
                    }
                });

                // Update price display on slider change
                priceSlider.noUiSlider.on('update', function(values) {
                    $('#price-min').text('$' + values[0]);
                    $('#price-max').text('$' + values[1]);
                });

                // Apply price filter
                $('#apply-price-filter').click(function() {
                    const values = priceSlider.noUiSlider.get();
                    const currentUrl = new URL(window.location.href);
                    const params = new URLSearchParams(currentUrl.search);

                    params.set('min_price', values[0]);
                    params.set('max_price', values[1]);
                    params.delete('page'); // Reset to page 1 when filtering

                    currentUrl.search = params.toString();
                    window.location.href = currentUrl.toString();
                });
            }

            // View toggle (grid/list)
            $('.view-btn').click(function() {
                const viewType = $(this).data('view');
                $('.view-btn').removeClass('active');
                $(this).addClass('active');

                if (viewType === 'list') {
                    $('#activities-container').removeClass('row').addClass('list-view');
                    $('.activity-item').removeClass('col-lg-4 col-md-6').addClass('col-12');
                    $('.tour-card').addClass('list-card');
                } else {
                    $('#activities-container').addClass('row').removeClass('list-view');
                    $('.activity-item').addClass('col-lg-4 col-md-6').removeClass('col-12');
                    $('.tour-card').removeClass('list-card');
                }
            });

            // Add animation for elements as they come into view
            $(window).scroll(function() {
                $('.tour-card').each(function() {
                    const elementTop = $(this).offset().top;
                    const elementVisible = 150;
                    const windowHeight = $(window).height();
                    const scrollPos = $(window).scrollTop();

                    if (elementTop < (scrollPos + windowHeight - elementVisible)) {
                        $(this).addClass('animated');
                    }
                });
            });
        });
    </script>
@endpush
