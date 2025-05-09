@extends('public.layouts.main')
@section('title', 'Search Results')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/search.css') }}">
@endpush

@section('content')
    <!-- Search Hero Section -->
    <div class="search-hero">
        <div class="container">
            <div class="search-hero-content">
                <h1 class="search-hero-title">Search Results for "{{ $query }}"</h1>
                <p class="search-hero-subtitle">Discover activities, restaurants and experiences that match your interests
                </p>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Search Form Container -->
        <div class="search-form-container">
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <div class="row g-3">
                    <div class="col-lg-10">
                        <div class="form-group mb-0">
                            <input type="text" name="query" class="form-control" value="{{ $query }}"
                                placeholder="Search activities, restaurants, places...">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button class="search-btn" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div class="search-results-header">
            <div class="results-count">
                Found <strong>{{ $activities->count() + $restaurants->count() }}</strong> results for
                "<strong>{{ $query }}</strong>"
            </div>
            <div class="sort-options">
                <span class="sort-label">View as:</span>
                <div class="view-options">
                    <button class="view-option active" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-option" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>

        @if ($activities->count() == 0 && $restaurants->count() == 0)
            <div class="empty-results">
                <i class="fas fa-search"></i>
                <h3>No results found</h3>
                <p>We couldn't find anything matching "{{ $query }}". Please try a different search term or browse
                    our suggestions below.</p>
                <ul class="suggestion-list">
                    <li>Check the spelling of your search term</li>
                    <li>Try using more general keywords</li>
                    <li>Try searching for a specific category</li>
                </ul>
                <a href="{{ route('services') }}" class="modify-search-btn">
                    <i class="fas fa-compass me-2"></i>Explore All Activities
                </a>
            </div>
        @else
            <!-- Activities Section -->
            @if ($activities->count() > 0)
                <h3 class="filter-section-title mt-4">Activities ({{ $activities->count() }})</h3>
                <div class="search-results-grid">
                    @foreach ($activities as $activity)
                        <div class="search-card">
                            <div class="search-card-image">
                                @if ($activity->has_images)
                                    @php
                                        $primaryImage = $activity->images->where('is_primary', true)->first();
                                        $imagePath = $primaryImage
                                            ? 'storage/' . $primaryImage->path
                                            : 'api/placeholder/400/300';
                                    @endphp
                                    <img src="{{ asset($imagePath) }}" alt="{{ $activity->name }}">
                                @else
                                    <img src="{{ asset('api/placeholder/400/300') }}" alt="{{ $activity->name }}">
                                @endif
                                <span class="card-badge {{ rand(0, 1) ? 'featured' : '' }}">
                                    {{ $activity->categoryType->name ?? 'Activity' }}
                                </span>
                                <button class="favorite-btn wishlist-button" data-activity-id="{{ $activity->id }}">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="search-card-content">
                                <h3 class="search-card-title">
                                    <a href="{{ route('activity.detail', $activity->id) }}">{{ $activity->name }}</a>
                                </h3>
                                <div class="search-card-location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $activity->location }}
                                </div>
                                <div class="search-card-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-users"></i> {{ rand(1, 10) }} people
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-clock"></i> {{ rand(1, 8) }} hours
                                    </span>
                                </div>
                                <div class="search-card-description">
                                    {{ Str::limit($activity->description, 120) }}
                                </div>
                                <div class="search-card-footer">
                                    <div class="search-card-price">
                                        ${{ number_format($activity->price, 2) }}
                                        <small>/ person</small>
                                    </div>
                                    <div class="search-card-rating">
                                        <div class="stars">
                                            @php $rating = rand(3, 5); @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $rating }}.0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('activity.detail', $activity->id) }}" class="view-details-btn">View
                                    Details</a>
                                <a href="{{ route('activity.detail', $activity->id) }}" class="book-now-btn">Book Now</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Restaurants Section -->
            @if ($restaurants->count() > 0)
                <h3 class="filter-section-title mt-5">Restaurants ({{ $restaurants->count() }})</h3>
                <div class="search-results-grid">
                    @foreach ($restaurants as $restaurant)
                        <div class="search-card">
                            <div class="search-card-image">
                                @if ($restaurant->image)
                                    <img src="{{ asset('storage/' . $restaurant->image) }}" alt="{{ $restaurant->name }}">
                                @else
                                    <img src="{{ asset('api/placeholder/400/300') }}" alt="{{ $restaurant->name }}">
                                @endif
                                <span class="card-badge {{ rand(0, 1) ? 'popular' : 'new' }}">
                                    {{ $restaurant->cuisine_type }}
                                </span>
                                <button class="favorite-btn" data-restaurant-id="{{ $restaurant->id }}">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="search-card-content">
                                <h3 class="search-card-title">
                                    <a href="#">{{ $restaurant->name }}</a>
                                </h3>
                                <div class="search-card-location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $restaurant->location }}
                                </div>
                                <div class="search-card-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-utensils"></i> {{ $restaurant->cuisine_type }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-dollar-sign"></i> {{ $restaurant->price_range }}
                                    </span>
                                </div>
                                <div class="search-card-description">
                                    {{ Str::limit($restaurant->description, 120) }}
                                </div>
                                <div class="search-card-footer">
                                    <div class="search-card-price">
                                        {{ $restaurant->price_range }}
                                    </div>
                                    <div class="search-card-rating">
                                        <div class="stars">
                                            @php $rating = rand(3, 5); @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ $rating }}.0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="#" class="view-details-btn">View Details</a>
                                <a href="#" class="book-now-btn">Reserve Table</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            <div class="pagination-container">
                <nav aria-label="Search results pages">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next <i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle grid/list view toggle
            const viewOptions = document.querySelectorAll('.view-option');
            const resultsGrid = document.querySelector('.search-results-grid');

            viewOptions.forEach(option => {
                option.addEventListener('click', function() {
                    viewOptions.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    if (this.querySelector('.fa-list')) {
                        resultsGrid.classList.remove('search-results-grid');
                        resultsGrid.classList.add('search-results-list');
                    } else {
                        resultsGrid.classList.remove('search-results-list');
                        resultsGrid.classList.add('search-results-grid');
                    }
                });
            });

            // Handle wishlist buttons
            const wishlistButtons = document.querySelectorAll('.wishlist-button');
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const icon = this.querySelector('i');
                    icon.classList.toggle('far');
                    icon.classList.toggle('fas');
                    icon.classList.toggle('active');
                });
            });
        });
    </script>
@endpush
