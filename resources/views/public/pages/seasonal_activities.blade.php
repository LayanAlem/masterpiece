@extends('public.layouts.main')
@section('title', $season['title'] . ' Activities')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/activities.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
    <style>
        /* Special seasonal styling */
        .seasonal-header {
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 100px 0;
            margin-bottom: 40px;
            color: white;
        }

        .seasonal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom,
                    rgba(0, 0, 0, 0.4) 0%,
                    rgba(0, 0, 0, 0.6) 100%);
            z-index: 1;
        }

        .seasonal-header .container {
            position: relative;
            z-index: 2;
        }

        .seasonal-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .seasonal-description {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 25px;
            line-height: 1.7;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .seasonal-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        .seasonal-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .seasonal-meta-item i {
            font-size: 1.3rem;
        }

        .season-tag {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 15px;
        }

        .activity-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            /* Add position relative */
        }

        .activity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(146, 64, 11, 0.2);
        }

        .activity-image {
            height: 220px;
            position: relative;
            overflow: hidden;
        }

        .activity-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .activity-card:hover .activity-image img {
            transform: scale(1.1);
        }

        .seasonal-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .activity-content {
            padding: 20px;
            background: white;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .activity-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.3;
        }

        .activity-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .activity-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .activity-location {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #555;
            font-size: 0.9rem;
        }

        .activity-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .activity-rating i {
            color: #ffc107;
        }

        .activity-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .activity-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .activity-price .small {
            font-size: 0.8rem;
            color: #666;
            font-weight: normal;
        }

        .book-now-btn {
            padding: 8px 20px;
            border-radius: 30px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .book-now-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(146, 64, 11, 0.3);
            color: white;
        }

        .season-filter-section {
            background-color: #f8f9fa;
            padding: 20px 0;
            margin-bottom: 40px;
            border-radius: 10px;
        }

        .filter-btn {
            border: 1px solid #ddd;
            background: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 500;
            color: #555;
            transition: all 0.3s ease;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .view-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 5px;
            transition: all 0.3s ease;
        }

        .view-btn:hover,
        .view-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .list-view .activity-item {
            width: 100%;
        }

        .list-view .activity-card {
            flex-direction: row;
            height: 220px;
        }

        .list-view .activity-image {
            flex: 0 0 40%;
        }

        .list-view .activity-content {
            flex: 1;
        }

        .list-view .activity-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .active-filters-label {
            font-weight: 600;
            color: #555;
        }

        .active-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #f0f0f0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .remove-filter {
            background: rgba(0, 0, 0, 0.1);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            line-height: 1;
            color: #555;
            text-decoration: none;
        }

        .clear-all-filters {
            font-size: 0.85rem;
            color: var(--primary);
            font-weight: 500;
            text-decoration: underline;
        }

        /* Season-specific themes can be added here */
        .winter-theme {
            --seasonal-color: #1976D2;
            --seasonal-gradient: linear-gradient(135deg, #1976D2 0%, #64B5F6 100%);
        }

        .spring-theme {
            --seasonal-color: #7CB342;
            --seasonal-gradient: linear-gradient(135deg, #7CB342 0%, #AED581 100%);
        }

        .summer-theme {
            --seasonal-color: #FF9800;
            --seasonal-gradient: linear-gradient(135deg, #FF9800 0%, #FFD54F 100%);
        }

        .autumn-theme {
            --seasonal-color: #E64A19;
            --seasonal-gradient: linear-gradient(135deg, #E64A19 0%, #FF8A65 100%);
        }

        /* Apply seasonal theming */
        .seasonal-theme .seasonal-badge,
        .seasonal-theme .book-now-btn,
        .seasonal-theme .filter-btn:hover,
        .seasonal-theme .filter-btn.active,
        .seasonal-theme .view-btn:hover,
        .seasonal-theme .view-btn.active {
            background: var(--seasonal-gradient);
        }

        .seasonal-theme .activity-price,
        .seasonal-theme .clear-all-filters {
            color: var(--seasonal-color);
        }

        @media (max-width: 767px) {
            .seasonal-title {
                font-size: 2.5rem;
            }

            .seasonal-meta {
                flex-direction: column;
                gap: 15px;
            }

            .list-view .activity-card {
                flex-direction: column;
                height: auto;
            }

            .list-view .activity-image {
                flex: 0 0 200px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb Section -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $season['title'] }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Seasonal Header Section -->
    <section class="seasonal-header {{ $season['theme'] }}-theme"
        style="background-image: url('{{ asset($season['banner'] ?? $season['image']) }}')">
        <div class="container text-center">
            <span class="season-tag">{{ $season['title'] }} Season</span>
            <h1 class="seasonal-title">{{ $season['title'] }} Activities</h1>
            <p class="seasonal-description">{{ $season['description'] }}</p>
            <div class="seasonal-meta">
                <div class="seasonal-meta-item">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ $season['months'] }}</span>
                </div>
                <div class="seasonal-meta-item">
                    <i class="fas fa-thermometer-half"></i>
                    <span>{{ $season['temperature'] ?? 'Varies by location' }}</span>
                </div>
                <div class="seasonal-meta-item">
                    <i class="fas fa-umbrella"></i>
                    <span>{{ $season['weather'] ?? 'Check local forecast' }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="season-filter-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex gap-2 flex-wrap">
                            <!-- Activity Type filter -->
                            <div class="dropdown">
                                <button class="filter-btn dropdown-toggle" type="button" id="activityTypeDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Activity Type <i class="fas fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="activityTypeDropdown">
                                    <li><a class="dropdown-item {{ request('type') == '' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['type', 'page']), ['season' => $season['slug']])) }}">All
                                            Types</a></li>
                                    @foreach ($activityTypes as $type)
                                        <li><a class="dropdown-item {{ request('type') == $type->id ? 'active' : '' }}"
                                                href="{{ route('seasonal.activities', array_merge(request()->except(['type', 'page']), ['season' => $season['slug'], 'type' => $type->id])) }}">{{ $type->name }}</a>
                                        </li>
                                    @endforeach
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

                            <!-- Duration filter -->
                            <div class="dropdown">
                                <button class="filter-btn dropdown-toggle" type="button" id="durationDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Duration <i class="fas fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="durationDropdown">
                                    <li><a class="dropdown-item {{ request('duration') == '' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['duration', 'page']), ['season' => $season['slug']])) }}">Any
                                            Duration</a></li>
                                    <li><a class="dropdown-item {{ request('duration') == 'half_day' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['duration', 'page']), ['season' => $season['slug'], 'duration' => 'half_day'])) }}">Half
                                            Day (up to 4h)</a></li>
                                    <li><a class="dropdown-item {{ request('duration') == 'full_day' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['duration', 'page']), ['season' => $season['slug'], 'duration' => 'full_day'])) }}">Full
                                            Day (4-8h)</a></li>
                                    <li><a class="dropdown-item {{ request('duration') == 'multi_day' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['duration', 'page']), ['season' => $season['slug'], 'duration' => 'multi_day'])) }}">Multi-Day
                                            (1+ days)</a></li>
                                </ul>
                            </div>

                            <!-- Sort by dropdown -->
                            <div class="dropdown">
                                <button class="filter-btn dropdown-toggle" type="button" id="sortDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Sort By <i class="fas fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item {{ request('sort_by') == 'popular' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['season' => $season['slug'], 'sort_by' => 'popular'])) }}">Most
                                            Popular</a></li>
                                    <li><a class="dropdown-item {{ request('sort_by') == 'price' && request('sort_dir') == 'asc' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['season' => $season['slug'], 'sort_by' => 'price', 'sort_dir' => 'asc'])) }}">Price
                                            (Low to High)</a></li>
                                    <li><a class="dropdown-item {{ request('sort_by') == 'price' && request('sort_dir') == 'desc' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['season' => $season['slug'], 'sort_by' => 'price', 'sort_dir' => 'desc'])) }}">Price
                                            (High to Low)</a></li>
                                    <li><a class="dropdown-item {{ request('sort_by') == 'rating' ? 'active' : '' }}"
                                            href="{{ route('seasonal.activities', array_merge(request()->except(['sort_by', 'sort_dir', 'page']), ['season' => $season['slug'], 'sort_by' => 'rating'])) }}">Highest
                                            Rated</a></li>
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
                    @if (request()->except(['season', 'page']))
                        <div class="active-filters">
                            <span class="active-filters-label">Active Filters:</span>
                            @if (request('type'))
                                @php
                                    $typeName = $activityTypes->where('id', request('type'))->first()->name ?? 'Type';
                                @endphp
                                <span class="active-filter-tag">
                                    Type: {{ $typeName }}
                                    <a href="{{ route('seasonal.activities', array_merge(request()->except(['type', 'page']), ['season' => $season['slug']])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif
                            @if (request('duration'))
                                <span class="active-filter-tag">
                                    Duration: {{ ucfirst(str_replace('_', ' ', request('duration'))) }}
                                    <a href="{{ route('seasonal.activities', array_merge(request()->except(['duration', 'page']), ['season' => $season['slug']])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif
                            @if (request('min_price') || request('max_price'))
                                <span class="active-filter-tag">
                                    Price: ${{ request('min_price', 0) }} - ${{ request('max_price', 1000) }}
                                    <a href="{{ route('seasonal.activities', array_merge(request()->except(['min_price', 'max_price', 'page']), ['season' => $season['slug']])) }}"
                                        class="remove-filter">×</a>
                                </span>
                            @endif

                            <a href="{{ route('seasonal.activities', ['season' => $season['slug']]) }}"
                                class="clear-all-filters">Clear All</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Seasonal Activities Section -->
    <section class="seasonal-activities-section seasonal-theme {{ $season['theme'] }}-theme">
        <div class="container">
            @if ($activities->count() > 0)
                <div class="row" id="activities-container">
                    @foreach ($activities as $activity)
                        <div class="col-lg-4 col-md-6 mb-4 activity-item">
                            <div class="activity-card">
                                <a href="{{ route('activity.detail', $activity->id) }}" class="card-link"></a>
                                <div class="activity-image">
                                    <img src="{{ asset($activity->image ? 'storage/' . $activity->image : 'api/placeholder/800/500') }}"
                                        alt="{{ $activity->name }}">
                                    <div class="seasonal-badge">{{ $season['title'] }} Special</div>
                                    <button class="favorite-btn" data-activity-id="{{ $activity->id }}">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="activity-content">
                                    <h3 class="activity-title">{{ $activity->name }}</h3>
                                    <p class="activity-description">{{ Str::limit($activity->description, 100) }}</p>
                                    <div class="activity-meta">
                                        <div class="activity-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $activity->location }}</span>
                                        </div>
                                        <div class="activity-rating">
                                            <i class="fas fa-star"></i>
                                            <span>{{ number_format($activity->average_rating, 1) }}</span>
                                            <span class="text-muted">({{ $activity->reviews_count }})</span>
                                        </div>
                                    </div>
                                    <div class="activity-footer">
                                        <div class="activity-price">
                                            ${{ number_format($activity->price, 0) }} <span class="small">/person</span>
                                        </div>
                                        <a href="{{ route('activity.detail', $activity->id) }}"
                                            class="btn book-now-btn">Book
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
                    <p>We couldn't find any {{ $season['title'] }} activities matching your criteria. Please try adjusting
                        your filters or check back later.</p>
                    <a href="{{ route('seasonal.activities', ['season' => $season['slug']]) }}"
                        class="btn book-now-btn">Clear Filters</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Season Tips Section -->
    <section class="season-tips-section py-5 my-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="season-tips-card">
                        <h3><i class="fas fa-lightbulb"></i> {{ $season['title'] }} Travel Tips</h3>
                        <ul class="season-tips-list">
                            @foreach ($season['tips'] ?? [] as $tip)
                                <li><i class="fas fa-check-circle"></i> {{ $tip }}</li>
                            @endforeach
                            @if (!isset($season['tips']) || count($season['tips'] ?? []) === 0)
                                <li><i class="fas fa-check-circle"></i> Pack appropriate clothing for
                                    {{ $season['title'] }} weather conditions.</li>
                                <li><i class="fas fa-check-circle"></i> Consider the local climate during this season when
                                    planning activities.</li>
                                <li><i class="fas fa-check-circle"></i> Book popular activities in advance as they tend to
                                    fill up quickly.</li>
                                <li><i class="fas fa-check-circle"></i> Check local forecasts regularly as
                                    {{ strtolower($season['title']) }} weather can be unpredictable.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.js"></script>
    <script src="{{ asset('mainJS/wishlist.js') }}"></script>
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
                    $('.activity-card').addClass('list-card');
                } else {
                    $('#activities-container').addClass('row').removeClass('list-view');
                    $('.activity-item').addClass('col-lg-4 col-md-6').removeClass('col-12');
                    $('.activity-card').removeClass('list-card');
                }
            });

            // Add animation for elements as they come into view
            $(window).scroll(function() {
                $('.activity-card').each(function() {
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
