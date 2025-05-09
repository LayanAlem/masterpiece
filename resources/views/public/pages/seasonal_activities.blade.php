@extends('public.layouts.main')
@section('title', $season['title'] . ' Activities')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/activities.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.7.0/nouislider.min.css">
    <style>
        /* Set theme variables based on current season */
        :root {
            @if ($season['theme'] == 'winter')
                --seasonal-color: {{ $season['colors']['main'] ?? '#1976D2' }};
                --seasonal-color-light: {{ $season['colors']['light'] ?? '#64B5F6' }};
                --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#0D47A1' }};
                --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '25, 118, 210' }};
                --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#1976D2' }} 0%, {{ $season['colors']['light'] ?? '#64B5F6' }} 100%);
            @elseif($season['theme'] == 'spring')
                --seasonal-color: {{ $season['colors']['main'] ?? '#7CB342' }};
                --seasonal-color-light: {{ $season['colors']['light'] ?? '#AED581' }};
                --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#558B2F' }};
                --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '124, 179, 66' }};
                --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#7CB342' }} 0%, {{ $season['colors']['light'] ?? '#AED581' }} 100%);
            @elseif($season['theme'] == 'summer')
                --seasonal-color: {{ $season['colors']['main'] ?? '#FF9800' }};
                --seasonal-color-light: {{ $season['colors']['light'] ?? '#FFD54F' }};
                --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#EF6C00' }};
                --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '255, 152, 0' }};
                --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#FF9800' }} 0%, {{ $season['colors']['light'] ?? '#FFD54F' }} 100%);
            @elseif($season['theme'] == 'autumn')
                --seasonal-color: {{ $season['colors']['main'] ?? '#E64A19' }};
                --seasonal-color-light: {{ $season['colors']['light'] ?? '#FF8A65' }};
                --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#BF360C' }};
                --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '230, 74, 25' }};
                --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#E64A19' }} 0%, {{ $season['colors']['light'] ?? '#FF8A65' }} 100%);
            @else
                --seasonal-color: #4CAF50;
                --seasonal-color-light: #8BC34A;
                --seasonal-color-dark: #2E7D32;
                --seasonal-color-rgb: 76, 175, 80;
                --seasonal-gradient: linear-gradient(135deg, #4CAF50 0%, #8BC34A 100%);
            @endif
        }

        body {
            background: #ffffff;
        }

        /* Enhanced seasonal header styling */
        .seasonal-header {
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 60px 0;
            margin-bottom: 40px;
            color: white;
            overflow: hidden;
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
            z-index: 5;
            text-align: center;
        }

        .seasonal-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            position: relative;
            margin-top: 20px;
            /* display: inline-block; */
            color: white;
        }

        .seasonal-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--seasonal-color);
            border-radius: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .seasonal-description {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 30px auto 35px;
            line-height: 1.7;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .seasonal-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .seasonal-meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .seasonal-meta-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.2);
        }

        .seasonal-meta-item i {
            font-size: 1.5rem;
            color: var(--seasonal-color-light);
        }

        .season-tag {
            /* display: inline-block; */
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Search form styling */
        .search-container {
            max-width: 650px;
            margin: 0 auto;
        }

        .search-container .input-group {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: stretch;
        }

        .search-container .form-control {
            border: none;
            padding: 15px 25px;
            font-size: 1rem;
            height: auto;
            flex: 1;
        }

        .search-container .search-btn {
            padding: 0 30px;
            background: var(--seasonal-color);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-top-right-radius: 50px;
            border-bottom-right-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .search-container .search-btn:hover {
            background: var(--seasonal-color-dark);
            /* transform: translateY(-2px); */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Season-specific themes - using dynamic variables from controller */
        .winter-theme {
            --seasonal-color: {{ $season['colors']['main'] ?? '#1976D2' }};
            --seasonal-color-light: {{ $season['colors']['light'] ?? '#64B5F6' }};
            --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#0D47A1' }};
            --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '25, 118, 210' }};
            --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#1976D2' }} 0%, {{ $season['colors']['light'] ?? '#64B5F6' }} 100%);
        }

        .spring-theme {
            --seasonal-color: {{ $season['colors']['main'] ?? '#7CB342' }};
            --seasonal-color-light: {{ $season['colors']['light'] ?? '#AED581' }};
            --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#558B2F' }};
            --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '124, 179, 66' }};
            --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#7CB342' }} 0%, {{ $season['colors']['light'] ?? '#AED581' }} 100%);
        }

        .summer-theme {
            --seasonal-color: {{ $season['colors']['main'] ?? '#FF9800' }};
            --seasonal-color-light: {{ $season['colors']['light'] ?? '#FFD54F' }};
            --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#EF6C00' }};
            --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '255, 152, 0' }};
            --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#FF9800' }} 0%, {{ $season['colors']['light'] ?? '#FFD54F' }} 100%);
        }

        .autumn-theme {
            --seasonal-color: {{ $season['colors']['main'] ?? '#E64A19' }};
            --seasonal-color-light: {{ $season['colors']['light'] ?? '#FF8A65' }};
            --seasonal-color-dark: {{ $season['colors']['dark'] ?? '#BF360C' }};
            --seasonal-color-rgb: {{ $season['colors']['rgb'] ?? '230, 74, 25' }};
            --seasonal-gradient: linear-gradient(135deg, {{ $season['colors']['main'] ?? '#E64A19' }} 0%, {{ $season['colors']['light'] ?? '#FF8A65' }} 100%);
        }

        /* Activity card styles with improved image handling */
        .activity-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .activity-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .activity-card:hover .activity-image img {
            transform: scale(1.1);
        }

        .activity-image {
            height: 200px;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .activity-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }

        .activity-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .activity-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .activity-description {
            color: #555;
            margin-bottom: 15px;
            flex: 1;
        }

        .activity-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .activity-location,
        .activity-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: #555;
        }

        .activity-location i {
            color: var(--seasonal-color);
        }

        .activity-rating i {
            color: #FFC107;
        }

        .activity-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .activity-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--seasonal-color);
        }

        .activity-price .small {
            font-size: 0.8rem;
            font-weight: 400;
            color: #777;
        }

        .book-now-btn {
            padding: 8px 18px;
            border-radius: 30px;
            background: var(--seasonal-color) !important;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
        }

        .book-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
            background: var(--seasonal-color-dark);
        }

        .seasonal-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--seasonal-gradient);
            color: white;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .favorite-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .favorite-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        .favorite-btn i {
            color: #FF5252;
            font-size: 1rem;
        }

        /* List view styles */
        .list-view .activity-item {
            margin-bottom: 20px;
        }

        .list-view .activity-card {
            flex-direction: row;
            height: 220px;
        }

        .list-view .activity-image {
            flex: 0 0 35%;
            height: 100%;
        }

        .list-view .activity-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .list-view .activity-meta {
            margin-top: auto;
        }

        @media (max-width: 991px) {
            .list-view .activity-card {
                height: auto;
                flex-direction: column;
            }

            .list-view .activity-image {
                flex: 0 0 220px;
            }
        }

        @media (max-width: 767px) {
            .seasonal-title {
                font-size: 2.5rem;
            }

            .seasonal-meta {
                flex-direction: column;
                gap: 15px;
            }

            .seasonal-header {
                padding: 40px 0;
            }

            .search-container .form-control {
                height: 50px;
                padding: 10px 20px;
            }

            .seasonal-description {
                font-size: 1.1rem;
            }
        }

        /* Season tips section styling */
        .season-tips-section {
            background-color: #f9f9f9;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            margin: 60px 0;
            padding: 60px 0;
            border: 1px solid #eee;
        }

        .season-tips-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 35px;
            position: relative;
            z-index: 2;
            border-left: 5px solid var(--seasonal-color);
            transition: all 0.3s ease;
        }

        .season-tips-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .season-tips-card h3 {
            color: var(--dark);
            font-weight: 700;
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            font-size: 1.6rem;
            position: relative;
            padding-bottom: 15px;
        }

        .season-tips-card h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 70px;
            background: var(--seasonal-gradient);
            border-radius: 10px;
        }

        .season-tips-card h3 i {
            color: var(--seasonal-color);
            margin-right: 12px;
            font-size: 1.8rem;
            background: rgba(var(--seasonal-color-rgb), 0.1);
            padding: 12px;
            border-radius: 50%;
            height: 50px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .season-tips-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .season-tips-list li {
            padding: 12px 0;
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
            line-height: 1.6;
            position: relative;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.08);
        }

        .season-tips-list li:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .season-tips-list li i {
            color: var(--seasonal-color);
            margin-right: 12px;
            font-size: 1.1rem;
            position: relative;
            top: 3px;
            flex-shrink: 0;
        }

        /* Filter section styling */
        .season-filter-section {
            margin-bottom: 30px;
            padding: 15px 0;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-btn {
            padding: 10px 20px;
            border-radius: 50px;
            background: white;
            border: 1px solid #e0e0e0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            border-color: var(--seasonal-color);
            color: var(--seasonal-color);
        }

        .filter-btn i {
            font-size: 0.8rem;
        }

        .view-buttons {
            display: flex;
            gap: 10px;
        }

        .view-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: white;
            border: 1px solid #e0e0e0;
            color: #777;
            transition: all 0.2s;
        }

        .view-btn.active,
        .view-btn:hover {
            background: var(--seasonal-color);
            color: white;
            border-color: var(--seasonal-color);
        }

        .active-filters {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .active-filters-label {
            font-size: 0.9rem;
            color: #666;
        }

        .active-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f5f5f5;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.85rem;
        }

        .remove-filter {
            font-size: 1.2rem;
            font-weight: bold;
            color: #999;
            text-decoration: none;
        }

        .remove-filter:hover {
            color: #d32f2f;
        }

        .clear-all-filters {
            font-size: 0.85rem;
            color: #d32f2f;
            text-decoration: none;
            margin-left: auto;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .no-results i {
            font-size: 3.5rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .no-results h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .no-results p {
            color: #666;
            max-width: 500px;
            margin: 0 auto 25px;
        }

        /* Price slider customization */
        .noUi-connect {
            background: var(--seasonal-color);
        }

        #apply-price-filter {
            background-color: var(--seasonal-color);
            border-color: var(--seasonal-color);
        }

        #apply-price-filter:hover {
            background-color: var(--seasonal-color-dark);
            border-color: var(--seasonal-color-dark);
        }

        .dropdown-item.active,
        .dropdown-item:active {
            background-color: var(--seasonal-color);
        }
    </style>
@endpush

@section('content')

    <!-- Enhanced Seasonal Header Section -->
    <section class="seasonal-header {{ $season['theme'] }}-theme"
        style="background-image: url('{{ $season['banner'] }}');">
        <div class="container text-center">
            <span class="season-tag">{{ $season['title'] }} Season</span>
            <h1 class="seasonal-title">{{ $season['title'] }} Activities</h1>
            <p class="seasonal-description">{{ $season['description'] }}</p>

            <!-- Search Bar -->
            <div class="search-container mb-4">
                <form action="{{ route('seasonal.activities', ['season' => $season['slug']]) }}" method="GET"
                    id="search-form">
                    <div class="input-group">
                        <input type="text" class="form-control"
                            placeholder="Search for {{ strtolower($season['title']) }} activities..." name="search"
                            value="{{ request('search') }}">
                        <button class="btn search-btn" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

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
    <section class="seasonal-activities-section">
        <div class="container">
            @if ($activities->count() > 0)
                <div class="row" id="activities-container">
                    @foreach ($activities as $activity)
                        <div class="col-lg-4 col-md-6 mb-4 activity-item">
                            <div class="activity-card">
                                <a href="{{ route('activity.detail', $activity->id) }}" class="card-link">
                                    <div class="activity-image" style="height: 200px; overflow: hidden; border-radius: 15px 15px 0 0;">
                                    @if ($activity->has_images)
                                        @php
                                            $primaryImage = null;
                                            if (
                                                $activity->images instanceof \Illuminate\Database\Eloquent\Collection &&
                                                $activity->images->count() > 0
                                            ) {
                                                $primaryImage = $activity->images->where('is_primary', true)->first();
                                                if (!$primaryImage) {
                                                    $primaryImage = $activity->images->first();
                                                }
                                            }
                                        @endphp
                                        <img src="{{ asset('storage/' . ($primaryImage ? $primaryImage->path : 'placeholder.jpg')) }}"
                                            alt="{{ $activity->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            onerror="this.src='{{ asset('api/placeholder/800/500') }}'">
                                    @else
                                        <img src="{{ asset('assets/images/jordan-country.jpg') }}" 
                                            alt="{{ $activity->name }}" 
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                    <div class="seasonal-badge">{{ $season['title'] }} Special</div>
                                    <button class="favorite-btn" data-activity-id="{{ $activity->id }}">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    </div>
                                    <div class="activity-content">
                                </a>
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
