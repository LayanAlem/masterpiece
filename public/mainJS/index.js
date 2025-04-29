$(document).ready(function() {
    $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 24,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        navText: [
            "<i class='fas fa-chevron-left'></i>",
            "<i class='fas fa-chevron-right'></i>"
        ],
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                stagePadding: 20,
            },
            576: {
                items: 2,
            },
            992: {
                items: 3,
            },
            1200: {
                items: 4,
            },
        },
    });

    // Favorite button functionality
    $(".favorite-btn").click(function() {
        $(this).toggleClass("active");
        $(this).find("i").toggleClass("far fas");
    });
});

 // Back to Top Button Functionality
 $(document).ready(function() {
    var backToTopButton = $('#backToTop');

    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            backToTopButton.addClass('active');
        } else {
            backToTopButton.removeClass('active');
        }
    });

    backToTopButton.click(function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 800);
    });
});

