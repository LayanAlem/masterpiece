@extends('public.layouts.main')
@section('title', 'Our Services')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/services.css') }}">
    <style>
        /* Additional custom styles to enhance design */
        .category-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(146, 64, 11, 0.2);
        }

        .category-image {
            flex: 0 0 40%;
            height: auto;
            min-height: 250px;
        }

        .explore-btn {
            font-weight: 600;
            border-radius: 30px;
            padding: 10px 25px;
            box-shadow: 0 4px 8px rgba(146, 64, 11, 0.2);
            margin-top: 15px;
            display: inline-block;
            background: linear-gradient(135deg, var(--primary) 0%, #b85c38 100%);
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .explore-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, #b85c38 0%, var(--primary) 100%);
            transition: width 0.4s ease;
            z-index: -1;
            border-radius: 30px;
        }

        .explore-btn:hover {
            box-shadow: 0 6px 12px rgba(146, 64, 11, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .explore-btn:hover::after {
            width: 100%;
        }

        .subcategory-tag {
            transition: all 0.3s ease;
            padding: 5px 12px;
            margin: 3px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            background-color: var(--light);
            color: var(--primary);
            border: 1px solid transparent;
        }

        .subcategory-tag:hover {
            background-color: var(--primary);
            color: var(--white);
            transform: scale(1.05);
        }

        .season-card {
            overflow: hidden;
            border-radius: 15px;
            height: 250px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .season-card img {
            transition: transform 0.5s ease;
        }

        .season-card:hover img {
            transform: scale(1.1);
        }

        .season-overlay {
            background: linear-gradient(to top,
                    rgba(0, 0, 0, 0.8) 10%,
                    rgba(0, 0, 0, 0.2));
            transition: all 0.3s ease;
        }

        .season-card:hover .season-overlay {
            background: linear-gradient(to top,
                    rgba(146, 64, 11, 0.8) 20%,
                    rgba(146, 64, 11, 0.3));
        }

        .services-header-section {
            background: linear-gradient(135deg, var(--light) 0%, #fff8e1 100%);
            padding: 70px 0 60px;
        }

        .category-overlay {
            background: linear-gradient(to right,
                    rgba(0, 0, 0, 0.5) 0%,
                    rgba(0, 0, 0, 0));
        }

        .category-content {
            flex: 1;
            padding: 25px 30px;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .category-title {
            font-size: 1.6rem;
            margin-bottom: 12px;
            font-weight: 700;
            color: var(--dark);
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
        }

        .category-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .category-description {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }

        .subcategories {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            gap: 5px;
        }

        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        @media (max-width: 767px) {
            .category-card {
                flex-direction: column;
            }

            .category-image {
                flex: 0 0 100%;
                min-height: 200px;
            }

            .category-overlay {
                background: linear-gradient(to top,
                        rgba(0, 0, 0, 0.5) 0%,
                        rgba(0, 0, 0, 0));
            }

            .category-content {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')

    <!-- Services Header Section -->
    <section class="services-header-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="section-title">Our Services</h1>
                    <p class="section-description">Explore our wide range of travel services designed to make your journey
                        unforgettable. From guided tours to custom experiences, we have everything you need for the perfect
                        adventure.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Service Categories Section -->
    <section class="service-categories-section">
        <div class="container">
            <div class="row">
                @forelse($mainCategories as $category)
                    <div class="col-12 mb-4">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset($category->image ? 'storage/' . $category->image : 'api/placeholder/800/500') }}"
                                    alt="{{ $category->name }}">
                                <div class="category-overlay"></div>
                            </div>
                            <div class="category-content">
                                <h3 class="category-title">{{ $category->name }}</h3>
                                <p class="category-description">{{ $category->description }}</p>
                                <div class="subcategories">
                                    @foreach ($category->categoryTypes as $type)
                                        <a href="{{ route('category.activities', ['categoryId' => $category->id, 'type' => $type->id]) }}"
                                            class="subcategory-tag text-decoration-none">{{ $type->name }}</a>
                                    @endforeach
                                </div>
                                <a href="{{ route('category.activities', ['categoryId' => $category->id]) }}"
                                    class="btn explore-btn">Explore
                                    {{ $category->name }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback categories if no data exists -->
                    <div class="col-12 mb-4">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="/api/placeholder/800/500" alt="Tours">
                                <div class="category-overlay"></div>
                            </div>
                            <div class="category-content">
                                <h3 class="category-title">Tours</h3>
                                <p class="category-description">Discover breathtaking locations with our expertly guided
                                    tours across Jordan and beyond.</p>
                                <div class="subcategories">
                                    <a href="#" class="subcategory-tag text-decoration-none">Adventure</a>
                                    <a href="#" class="subcategory-tag text-decoration-none">Cultural</a>
                                    <a href="#" class="subcategory-tag text-decoration-none">Family</a>
                                </div>
                                <a href="" class="btn explore-btn">Explore Tours</a>
                            </div>
                        </div>
                    </div>
                    <!-- Additional fallback categories -->
                    <div class="col-12 mb-4">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="/api/placeholder/800/500" alt="Accommodations">
                                <div class="category-overlay"></div>
                            </div>
                            <div class="category-content">
                                <h3 class="category-title">Accommodations</h3>
                                <p class="category-description">Find the perfect place to stay, from luxury hotels to
                                    authentic desert camps.</p>
                                <div class="subcategories">
                                    <a href="#" class="subcategory-tag text-decoration-none">Hotels</a>
                                    <a href="#" class="subcategory-tag text-decoration-none">Resorts</a>
                                    <a href="#" class="subcategory-tag text-decoration-none">Camping</a>
                                </div>
                                <a href="" class="btn explore-btn">Find Accommodations</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="category-card">
                            <div class="category-image">
                                <img src="/api/placeholder/800/500" alt="Transportation">
                                <div class="category-overlay"></div>
                            </div>
                            <div class="category-content">
                                <h3 class="category-title">Transportation</h3>
                                <p class="category-description">Convenient transportation options for seamless travel
                                    throughout your journey.</p>
                                <div class="subcategories">
                                    <a href="#" class="subcategory-tag text-decoration-none">Airport Transfers</a>
                                    <a href="#" class="subcategory-tag text-decoration-none">Car Rentals</a>
                                    <a href="#" class="subcategory-tag text-decoration-none">Private Drivers</a>
                                </div>
                                <a href="" class="btn explore-btn">View Options</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Seasonal Recommendations Section -->
    <section class="recommendations-section">
        <div class="container">
            <h2 class="section-title text-center">Seasonal Recommendations</h2>
            <div class="row">
                @foreach ($seasonalRecommendations as $season)
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <a href="{{ route('seasonal.activities', ['season' => $season['slug']]) }}"
                            class="text-decoration-none">
                            <div class="season-card">
                                <img src="{{ asset($season['image']) }}" alt="{{ $season['alt'] }}">
                                <div class="season-overlay">
                                    <h3 class="season-title">{{ $season['title'] }}</h3>
                                    <p class="season-months">{{ $season['months'] }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Add hover effects for category cards
            $(".category-card").hover(
                function() {
                    $(this).addClass("hover");
                },
                function() {
                    $(this).removeClass("hover");
                }
            );

            // Add animation for elements as they come into view
            $(window).scroll(function() {
                $('.category-card, .season-card').each(function() {
                    var elementTop = $(this).offset().top;
                    var elementVisible = 150;
                    var windowHeight = $(window).height();
                    var scrollPos = $(window).scrollTop();

                    if (elementTop < (scrollPos + windowHeight - elementVisible)) {
                        $(this).addClass('animated');
                    }
                });
            });
        });
    </script>
@endpush
