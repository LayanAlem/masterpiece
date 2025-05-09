@extends('public.layouts.main')
@section('title', 'Contact Us')

@push('styles')
    <link rel="stylesheet" href="{{ asset('mainStyle/contact.css') }}">
@endpush

@section('content')


    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1>Get in Touch</h1>
                    <p class="lead fs-4">We're here to answer any questions you might have or to help you plan your perfect
                        journey in Jordan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-info-section">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Location -->
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Our Location</h3>
                        <p>Swefieh<br>Amman, Jordan 11185</p>
                    </div>
                </div>
                <!-- Email -->
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Us</h3>
                        <p>info@jordantours.com</p>
                    </div>
                </div>
                <!-- Phone -->
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3>Call Us</h3>
                        <p>+962 79 729 8 446</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form and Map -->
    <section class="contact-form-section">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="contact-form-wrapper">
                        <h2>Send us a Message</h2>
                        <form id="contactForm">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" placeholder="Your Name">
                                <label for="name">Your Name</label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" placeholder="Your Email">
                                        <label for="email">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="phone" placeholder="Your Phone">
                                        <label for="phone">Your Phone</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" id="subject">
                                    <option selected disabled value="">Choose a subject</option>
                                    <option value="booking">Tour Booking</option>
                                    <option value="inquiry">General Inquiry</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="partnership">Partnership</option>
                                </select>
                                <label for="subject">Subject</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="message" placeholder="Your Message" style="height: 150px"></textarea>
                                <label for="message">Your Message</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn send-btn">Send Message <i
                                        class="fas fa-paper-plane ms-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Map -->
                <div class="col-lg-6">
                    <div class="map-wrapper">
                        <h2>Find Us</h2>
                        <div class="map-container">
                            <!-- Google Maps embed of Swefieh, Amman, Jordan -->
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13534.997245828794!2d35.86581007320676!3d31.957675252407443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151ca100964958e9%3A0x55a7647540d4e5ac!2sSwefieh%2C%20Amman%2C%20Jordan!5e0!3m2!1sen!2sus!4v1714319254235!5m2!1sen!2sus"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="office-hours mt-4">
                            <h3>Office Hours</h3>
                            <ul class="hours-list">
                                <li><span>Sunday - Thursday:</span> 9:00 AM - 8:00 PM</li>
                                <li><span>Saturday:</span> 10:00 AM - 4:00 PM</li>
                                <li><span>Friday:</span> Closed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2>Frequently Asked Questions</h2>
                    <p class="lead">Find quick answers to common questions</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="accordion" id="contactFAQ">
                        <!-- FAQ Item 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How far in advance should I book a tour?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    We recommend booking at least 2-3 weeks in advance for most tours, especially during
                                    peak tourist seasons (spring and fall). For custom tours or larger groups, we suggest
                                    booking 1-2 months ahead to ensure availability and allow time for personalization.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What payment methods do you accept?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    We accept all major credit cards (Visa, MasterCard, American Express), PayPal, bank
                                    transfers, and cash payments at our office. For tour bookings, a 20% deposit is required
                                    to secure your reservation, with the remaining balance due 14 days before the tour start
                                    date.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What is your cancellation policy?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    Cancellations made 30+ days before the tour date receive a full refund minus a 5%
                                    processing fee. Cancellations 15-29 days before receive a 75% refund. Cancellations 7-14
                                    days before receive a 50% refund. Cancellations less than 7 days before are
                                    non-refundable. We strongly recommend travel insurance to cover unexpected
                                    cancellations.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Can you arrange airport transfers?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#contactFAQ">
                                <div class="accordion-body">
                                    Yes, we offer airport transfer services to and from Queen Alia International Airport in
                                    Amman. You can add this service during booking or contact us directly to arrange
                                    transportation. We also offer transfers between major cities and tourist destinations
                                    throughout Jordan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Follow Us Section -->
    <section class="follow-us-section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>Follow Us</h2>
                    <p>Stay updated with our latest tours, events, and travel tips</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h3>Subscribe to Our Newsletter</h3>
                        <p>Get special offers, travel inspiration, and insider tips delivered to your inbox.</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your Email Address"
                                    aria-label="Email Address">
                                <button class="btn subscribe-btn" type="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form validation
            $('#contactForm').submit(function(e) {
                e.preventDefault();

                // Simple validation
                let valid = true;
                $('#contactForm input, #contactForm textarea, #contactForm select').each(function() {
                    if ($(this).val() === '' || $(this).val() === null) {
                        valid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (valid) {
                    // Show success message (in real implementation, you'd submit the form via AJAX)
                    alert('Thank you for your message! We will contact you shortly.');
                    $('#contactForm')[0].reset();
                } else {
                    alert('Please fill in all required fields');
                }
            });

            // Remove invalid class on input
            $('#contactForm input, #contactForm textarea, #contactForm select').on('focus', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endpush
