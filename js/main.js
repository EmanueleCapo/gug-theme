jQuery(document).ready(function ($) {

  if (typeof $.fn.owlCarousel !== "undefined") {
    $(".owl-carousel.home-carousel").owlCarousel({
      loop: true,
      nav: false,
      dots: false,
      items: 1,
      lazyLoad: true,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      autoplay: true,
      //autoplayTimeout: 5000,
      //margin: 15
      //autoWidth: true,
      autoplayHoverPause: true,
    });

    $(".owl-carousel.partner-carousel").owlCarousel({
      loop: true,
      nav: false,
      dots: false,
      //items: 1,
      lazyLoad: true,
      //animateIn: 'fadeIn',
      //animateOut: 'fadeOut',
      autoplay: true,
      //autoplayTimeout: 5000,
      margin: 15,
      //autoWidth: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 1
        },
        426: {
          items: 2
        },
        600: {
          items: 3
        },
        1000: {
          items: 4
        }
      }
    });
  }
});