(function ($) {

  "use strict";

    // PRE LOADER
    $(window).load(function(){
      $('.preloader').fadeOut(1000); // set duration in brackets    
    });


    //Navigation Section
    $('.navbar-collapse a').on('click',function(){
      $(".navbar-collapse").collapse('hide');
    });


    // Owl Carousel
    $('.owl-carousel:not(.testimonial-slider)').owlCarousel({
      animateOut: 'fadeOut',
      items:1,
      loop:true,
      autoplay:true,
    })

    // Testimonial Slider
    $('.testimonial-slider').owlCarousel({
      items: 2,
      loop: true,
      autoplay: true,
      margin: 30,
      responsive: {
        0: { items: 1 },
        768: { items: 2 }
      }
    })


    // PARALLAX EFFECT
    $.stellar();  


    // SMOOTHSCROLL
    $(function() {
      $('.navbar-default a, .smoothScroll, footer a').on('click', function(event) {
        var href = $(this).attr('href');
        
        // Check if it's an internal hash link
        if (href.indexOf('#') !== -1) {
            var targetId = href.split('#')[1];
            var $target = $('#' + targetId);
            
            if ($target.length) {
                // If on the same page OR if the link is just a hash
                if (window.location.pathname.endsWith('index.php') || window.location.pathname.endsWith('/') || href.startsWith('#')) {
                    $('html, body').stop().animate({
                        scrollTop: $target.offset().top - 70
                    }, 1000);
                    event.preventDefault();
                }
            }
        }
      });
    });  


    // WOW ANIMATION
    new WOW({ mobile: false }).init();

    // DYNAMIC STICKY NAVBAR (sticks only after header scrolls past)
    var headerHeight = $('header').outerHeight();
    $(window).scroll(function() {
      if ($(this).scrollTop() > headerHeight) {
        $('.navbar').addClass('navbar-sticky');
      } else {
        $('.navbar').removeClass('navbar-sticky');
      }
    });

    // DROPDOWN CLICK-ONLY LOGIC (NO HOVER)
    $('.dropdown-toggle').on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      var $parent = $(this).parent();
      
      // Close other open dropdowns
      $('.dropdown').not($parent).removeClass('open');
      
      // Toggle current
      $parent.toggleClass('open');
    });

    // Close dropdown when clicking a link inside it
    $('.dropdown-menu a').on('click', function() {
      $(this).closest('.dropdown').removeClass('open');
    });

    // Close dropdown when clicking anywhere else on the document
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.dropdown').length) {
        $('.dropdown').removeClass('open');
      }
    });

})(jQuery);
