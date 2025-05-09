@extends('public.layouts.main')
@section('title', 'About Us')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/about.css') }}">
@endpush

@section('content')


    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-hero-content">
                        <div class="about-hero-content">
                            <h1 class="display-4">Discover Jordan With Us</h1>
                            <p class="lead fs-4">For over 15 years, we've been creating unforgettable journeys through the
                                treasures of Jordan.</p>
                            <p class="fs-5">From the ancient wonders of Petra to the surreal landscapes of Wadi Rum, we
                                guide you through it all.</p>
                        </div>

                        <div class="hero-stats animate">
                            <div class="stat-item">
                                <span class="stat-number">5+</span>
                                <span class="stat-text">Years Experience</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ number_format($userCount) }}+</span>
                                <span class="stat-text">Happy Travelers</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">50+</span>
                                <span class="stat-text">Exciting Activities</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-hero-image">
                        <img src="/assets/images/explore.jpg" alt="Exploring Jordan" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <span class="section-subtitle">Our Journey</span>
                    <h2 class="section-title">Our Story</h2>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="story-image">
                        <img src="/assets/images/explore2.jpg" alt="Our Beginning" class="img-fluid rounded"
                            style="width: 100%; height: 700px; object-fit: cover; object-position: center;">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="story-content">
                        <h3>From Humble Beginnings</h3>
                        <p>Founded in 2008 by a group of passionate Jordanian guides, our company began with a simple
                            mission: to share the authentic beauty and cultural richness of Jordan with the world.</p>
                        <br>
                        <br>
                        <h3>Our Growth</h3>
                        <p>As word spread about our unique approach to tourism in Jordan, we steadily expanded our offerings
                            and team. Ensuring that our tours not only provide unforgettable experiences but also
                            support the people who make Jordan such a special place.</p>
                        <br>
                        <br>
                        <h3>Where We Are Today</h3>
                        <p>Today, we proudly serve hundreds of travelers each year, from solo adventurers to families and
                            groups. While we've grown in size, we remain true to our founding principles: authentic
                            experiences, responsible tourism, and creating lasting memories.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="mission-values-section py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-4">
                    <span class="section-subtitle">What Drives Us</span>
                    <h2 class="section-title">Our Mission & Values</h2>

                    <div class="mission-box text-center mb-4">
                        <h3>Our Mission</h3>
                        <p>To create transformative travel experiences that showcase Jordan's authentic beauty and rich
                            history while supporting local communities.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Values in a single row with smaller cards -->
                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="value-card h-100 py-3">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Authentic Experiences</h3>
                        <p>We connect our guests with real places and traditions that make Jordan unique.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="value-card h-100 py-3">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Cultural Respect</h3>
                        <p>We promote appreciation of Jordan's diverse cultural heritage through respectful engagement.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="value-card h-100 py-3">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Hospitality</h3>
                        <p>We embody legendary Jordanian hospitality, treating guests as friends with comfort and safety.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-section py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-4">
                    <span class="section-subtitle">Our Advantages</span>
                    <h2 class="section-title">Why Choose Us</h2>
                </div>
            </div>

            <div class="row justify-content-center">
                <!-- Three cards per row on larger screens -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="feature-card h-100 shadow-sm">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3>Local Expertise</h3>
                        <p>Jordanian native guides with deep knowledge of the country's history and culture.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="feature-card h-100 shadow-sm">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Authentic Experiences</h3>
                        <p>Home-cooked meals with local families and stays in traditional Bedouin camps.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="feature-card h-100 shadow-sm">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Safety & Comfort</h3>
                        <p>Well-maintained vehicles, comprehensive insurance, and 24/7 support.</p>
                    </div>
                </div>

                <!-- Second row with three smaller cards -->
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="feature-card h-100 shadow-sm">
                        <div class="feature-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3>Small Groups</h3>
                        <p>Small group sizes for personalized attention and meaningful connections.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="feature-card h-100 shadow-sm">
                        <div class="feature-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>Community Impact</h3>
                        <p>Supporting local communities through partnerships with small businesses.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="feature-card h-100 shadow-sm">
                        <div class="feature-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3>Award-Winning</h3>
                        <p>TripAdvisor's Certificate of Excellence for 5 consecutive years.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <span class="section-subtitle">What Our Guests Say</span>
                    <h2 class="section-title">Testimonials</h2>
                </div>
            </div>

            <div class="row">
                <!-- Testimonial 1 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"Our family trip to Jordan was absolutely magical. The guides were knowledgeable and
                                friendly, and they showed us places we would never have discovered on our own. Truly an
                                unforgettable experience!"</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="/api/placeholder/100/100" alt="Sarah M.">
                            <div class="author-info">
                                <h4>Sarah M.</h4>
                                <p>London, UK</p>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"As a solo female traveler, I was concerned about safety, but from the moment I arrived, I
                                felt completely at ease. The team went above and beyond to ensure I had an amazing
                                experience in this beautiful country."</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="/api/placeholder/100/100" alt="Michelle L.">
                            <div class="author-info">
                                <h4>Michelle L.</h4>
                                <p>Toronto, Canada</p>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"The Desert Safari Experience was the highlight of our trip! Sleeping under the stars in Wadi
                                Rum and learning about Bedouin culture from our guide was something we'll never forget.
                                Highly recommend!"</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="/api/placeholder/100/100" alt="David & Lisa R.">
                            <div class="author-info">
                                <h4>David & Lisa R.</h4>
                                <p>Sydney, Australia</p>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Ready to Experience Jordan?</h2>
                        <p>Let us help you plan the perfect journey through this ancient and beautiful land.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('contact') }}" class="btn cta-btn">Contact Us Today</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Animation for stats on scroll
            $(window).scroll(function() {
                var heroStats = $('.hero-stats');
                var heroStatsPosition = heroStats.offset().top;
                var windowHeight = $(window).height();
                var scrollPosition = $(window).scrollTop();

                if (scrollPosition > (heroStatsPosition - windowHeight + 200)) {
                    heroStats.addClass('animate');
                }
            });
        });
    </script>
@endpush
