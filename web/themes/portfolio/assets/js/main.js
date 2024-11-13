(function ($) {
  "use strict";

  var windowOn = $(window);

  /*======================================
  Preloader activation
  ========================================*/
  $(window).on('load', function (event) {
    $('#preloader').delay(500).fadeOut(500);
  });

  /*======================================
  Mobile Menu Js
  ========================================*/
  $("#mobile-menu").meanmenu({
    meanMenuContainer: ".mobile-menu",
    meanScreenWidth: "991",
    meanExpand: ['<i class="fa-regular fa-arrow-right"></i>'],
  });

  $("#mobile-menu-2").meanmenu({
    meanMenuContainer: ".mobile-menu-2",
    meanScreenWidth: "4000",
    meanExpand: ['<i class="fa-regular fa-arrow-right"></i>'],
  });

  /*======================================
  Sidebar Toggle
  ========================================*/
  $(".offcanvas__close,.offcanvas__overlay").on("click", function () {
    $(".offcanvas__area").removeClass("info-open");
    $(".offcanvas__overlay").removeClass("overlay-open");
  });
  $(".sidebar__toggle").on("click", function () {
    $(".offcanvas__area").addClass("info-open");
    $(".offcanvas__overlay").addClass("overlay-open");
  });

  /*======================================
  Body overlay Js
  ========================================*/
  $(".body-overlay").on("click", function () {
    $(".offcanvas__area").removeClass("opened");
    $(".body-overlay").removeClass("opened");
  });

  /*======================================
  Sticky Header Js
  ========================================*/

  $(window).scroll(function () {
    if ($(this).scrollTop() > 150) {
      $("#header-sticky").addClass("bd-sticky");
    } else {
      $("#header-sticky").removeClass("bd-sticky");
    }
  });


  // jarallax js
  jarallax(document.querySelectorAll('.jarallax'), {
    speed: 0.4,
  });


  /*======================================
  Data Css js
  ========================================*/
  $("[data-background").each(function () {
    $(this).css(
      "background-image",
      "url( " + $(this).attr("data-background") + "  )"
    );
  });

  $("[data-width]").each(function () {
    $(this).css("width", $(this).attr("data-width"));
  });

  $("[data-bg-color]").each(function () {
    $(this).css("background-color", $(this).attr("data-bg-color"));
  });

  /*======================================
  MagnificPopup image view
  ========================================*/
  $(".popup-image").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
    },
  });

  ////////////////////////////////////////////////////
  // 22. Parallax Js image move
  if ($('.scene').length > 0) {
    $('.scene').parallax({
      scalarX: 10.0,
      scalarY: 15.0,
    });
  };

  /*======================================
  MagnificPopup video view
  ========================================*/
  $(".popup-video").magnificPopup({
    type: "iframe",
  });

  /*======================================
  Counter Js
  ========================================*/

  $(".counter").counterUp({
    delay: 10,
    time: 1000,
  });


  /*======================================
  Wow Js
  ========================================*/
  if ($('.wow').length) {
    var wow = new WOW({
      boxClass: 'wow', // animated element css class (default is wow)
      animateClass: 'animated', // animation css class (default is animated)
      offset: 0, // distance to the element when triggering the animation (default is 0)
      mobile: false, // trigger animations on mobile devices (default is true)
      live: true // act on asynchronously loaded content (default is true)
    });
    wow.init();
  }

  /*======================================
  Nice Select Js
  ========================================*/
  $(".lang__select, .form__select").niceSelect();


  /*======================================
  Button scroll up js
  ========================================*/

  var progressPath = document.querySelector(".backtotop-wrap path");
  var pathLength = progressPath.getTotalLength();
  progressPath.style.transition = progressPath.style.WebkitTransition =
    "none";
  progressPath.style.strokeDasharray = pathLength + " " + pathLength;
  progressPath.style.strokeDashoffset = pathLength;
  progressPath.getBoundingClientRect();
  progressPath.style.transition = progressPath.style.WebkitTransition =
    "stroke-dashoffset 10ms linear";
  var updateProgress = function () {
    var scroll = $(window).scrollTop();
    var height = $(document).height() - $(window).height();
    var progress = pathLength - (scroll * pathLength) / height;
    progressPath.style.strokeDashoffset = progress;
  };
  updateProgress();
  $(window).scroll(updateProgress);
  var offset = 150;
  var duration = 550;
  jQuery(window).on("scroll", function () {
    if (jQuery(this).scrollTop() > offset) {
      jQuery(".backtotop-wrap").addClass("active-progress");
    } else {
      jQuery(".backtotop-wrap").removeClass("active-progress");
    }
  });
  jQuery(".backtotop-wrap").on("click", function (event) {
    event.preventDefault();
    jQuery("html, body").animate({
      scrollTop: 0
    }, duration);
    return false;
  });

  /*======================================
  One Page Scroll Js
  ========================================*/
  function scrollNav() {
    $(".onepage-menu li a").click(function () {
      $(".onepage-menu li a.active").removeClass("active");
      $(this).addClass("active");
      $(".offcanvas__area").removeClass("info-open");
      $(".offcanvas__overlay").removeClass("overlay-open");
      $("html, body")
        .stop()
        .animate(
          {
            scrollTop: $($(this).attr("href")).offset().top - 96,
          },
          300
        );
      return false;
    });
  }
  scrollNav();

  /*======================================
  Smoth animatio Js
  ========================================*/

  $(document).on('click', '.smoth-animation', function (event) {
    event.preventDefault();
    $('html, body').animate({
      scrollTop: $($.attr(this, 'href')).offset().top - 50
    }, 300);
  });


  //circle-progress
  $("#percentage").waypoint(
    function () {
      // circle-1
      $(".circle__counter").map(function (i, elem) {
        let count = Number($(this).data("count"))
          ? Number($(this).data("count"))
          : 0;

        $(elem)
          .circleProgress({
            value: count / 100,
            size: 170,
            thickness: 8,
            lineCap: "round",
            emptyFill: "#FFF",
            fill: "#4286F4",
          })
          .on("circle-animation-progress", function (event, progress) {
            $(this)
              .find(".counter__percentage")
              .html(Math.round(count * progress) + "<i>%</i>");
          });
      });
    },
    {
      offset: "bottom-in-view",
    }
  );


  /*======================================
   Map activation js
  ========================================*/
  $(".location-dot").on("mouseenter", function () {
    $(this)
      .addClass("active")
      .parent()
      .siblings()
      .find(".location-dot")
      .removeClass("active");
  });


  //  Banner Slider Active Js
  if ($('.banner_more_item').length > 1) {
    var banner = new Swiper(".banner__active", {
      slidesPerView: 1,
      // loop: true,
      speed: 1000,
      roundLengths: true,
      autoplay: {
        delay: 6000,
      },
      navigation: {
        nextEl: ".banner__button-next",
        prevEl: ".banner__button-prev",
      },
    });
  }


  /*======================================
  service slider js 
  ========================================*/
  var service = new Swiper(".service__active", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    roundLengths: true,
    autoplay: {
      delay: 3500,
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },
    breakpoints: {
      1400: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 1,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });

  /*======================================
  team slider js 
  ========================================*/
  var team = new Swiper(".team__active", {
    slidesPerView: 1,
    spaceBetween: 30,
    // loop: true,
    autoplay: {
      delay: 4500,
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },
    breakpoints: {
      1400: {
        slidesPerView: 4,
      },
      1200: {
        slidesPerView: 4,
      },
      992: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 3,
      },
      576: {
        slidesPerView: 2,
      },
      500: {
        slidesPerView: 2,
      },
    },
  });

  /*======================================
  team slider js style 02
  ========================================*/
  var team = new Swiper(".team__active-two", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 4500,
    },
    navigation: {
      nextEl: ".team__button-next",
      prevEl: ".team__button-prev",
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },
    breakpoints: {
      1400: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 1,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });

  /*======================================
  brand slider js 
  ========================================*/
  var brand = new Swiper(".brand__active", {
    slidesPerView: 6,
    spaceBetween: 30,
    loop: true,
    roundLengths: true,
    autoplay: {
      delay: 3000,
    },
    breakpoints: {
      1400: {
        slidesPerView: 6,
      },
      1200: {
        slidesPerView: 5,
      },
      992: {
        slidesPerView: 4,
      },
      768: {
        slidesPerView: 3,
      },
      576: {
        slidesPerView: 2,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });

  /*======================================
  brand slider js style 02
  ========================================*/
  var brand = new Swiper(".brand__active-two", {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true,
    roundLengths: true,
    autoplay: {
      delay: 3500,
    },
    breakpoints: {
      1400: {
        slidesPerView: 4,
      },
      1200: {
        slidesPerView: 4,
      },
      992: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 3,
      },
      576: {
        slidesPerView: 2,
      },
      500: {
        slidesPerView: 2,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });

  /*======================================
  portfolio slider js 
  ========================================*/
  var portfolio = new Swiper(".portfolio__active", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    speed: 700,
    // centeredSlides: true,
    autoplay: {
      delay: 45000000,
    },
    navigation: {
      nextEl: ".portfolio__button-next",
      prevEl: ".portfolio__button-prev",
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },
    breakpoints: {
      1200: {
        slidesPerView: 2.3,
        centeredSlides: false,
      },
      992: {
        slidesPerView: 2.3,
        centeredSlides: true,
      },
      768: {
        slidesPerView: 1.7,
        centeredSlides: true,
      },
      576: {
        slidesPerView: 1,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });


  /*=====================================
  portfolio slider js style 02
  ========================================*/
  var portfolio = new Swiper(".portfolio__active-two", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    centeredSlides: true,
    speed: 700,
    autoplay: {
      delay: 4500,
    },
    navigation: {
      nextEl: ".portfolio__button-next",
      prevEl: ".portfolio__button-prev",
    },
    breakpoints: {
      1200: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 3,
      },
      576: {
        slidesPerView: 3,
      },
      400: {
        slidesPerView: 1,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });


  /*======================================
  portfolio slider js 
  ========================================*/
  var portfolio = new Swiper(".portfolio-details__active", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    centeredSlides: true,
    speed: 900,
    autoplay: {
      delay: 4500,
    },
    navigation: {
      nextEl: ".portfolio__button-next",
      prevEl: ".portfolio__button-prev",
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },
    breakpoints: {
      1200: {
        slidesPerView: 1.3,
      },
      992: {
        slidesPerView: 1.1,
      },
      768: {
        slidesPerView: 1.3,
      },
      576: {
        slidesPerView: 1,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });


  /*======================================
  Range slider js
  ========================================*/
  $(".loan-range-bar").slider({
    range: "min",
    min: 0,
    max: 50000, // Adjust the maximum value as needed
    value: 800,
    slide: function (event, ui) {
      $(".loan-amount").val("$" + ui.value + " USD");
    },
  });

  /*======================================
  Parallax Swiper
  ========================================*/
  var parallaxSlider;
  var parallaxSliderOptions = {
    speed: 1500,
    autoplay: {
      delay: 5000,
    },
    parallax: true,
    loop: true,

    pagination: {
      el: ".bd-slider-dot",
      clickable: true,
    },

    navigation: {
      nextEl: ".slider__button-prev",
      prevEl: ".slider__button-next",
    },
    on: {
      init: function () {
        var swiper = this;
        for (var i = 0; i < swiper.slides.length; i++) {
          $(swiper.slides[i])
            .find(".slider__thumb-bg")
            .attr({
              "data-swiper-parallax": 0.75 * swiper.width,
            });
        }
      },
      resize: function () {
        this.update();
      },
    },
  };
  parallaxSlider = new Swiper(
    ".slider-prlx .parallax-slider",
    parallaxSliderOptions
  );

  /*======================================
  Slider Swiper
  ========================================*/
  if (jQuery(".slider__active").length > 0) {
    let sliderActive1 = ".slider__active";
    let sliderInit1 = new Swiper(sliderActive1, {
      // Optional parameters
      slidesPerView: 1,
      slidesPerColumn: 1,
      paginationClickable: true,
      fadeEffect: {
        crossFade: true,
      },
      loop: true,
      effect: "fade",
      autoplay: {
        delay: 5000,
      },
      navigation: {
        nextEl: ".slider__button-prev",
        prevEl: ".slider__button-next",
      },
      pagination: {
        el: ".bd-slider-dot",
        clickable: true,
      },
      a11y: false,
    });

    function animated_swiper(selector, init) {
      let animated = function animated() {
        $(selector + " [data-animation]").each(function () {
          let anim = $(this).data("animation");
          let delay = $(this).data("delay");
          let duration = $(this).data("duration");

          $(this)
            .removeClass("anim" + anim)
            .addClass(anim + " animated")
            .css({
              webkitAnimationDelay: delay,
              animationDelay: delay,
              webkitAnimationDuration: duration,
              animationDuration: duration,
            })
            .one(
              "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",
              function () {
                $(this).removeClass(anim + " animated");
              }
            );
        });
      };
      animated();
      // Make animated when slide change
      init.on("slideChange", function () {
        $(sliderActive1 + " [data-animation]").removeClass("animated");
      });
      init.on("slideChange", animated);
    }

    animated_swiper(sliderActive1, sliderInit1);
  }


  /*======================================
  Feedback activation js
  ========================================*/

  var feedback = new Swiper(".feedback__active", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    roundLengths: true,
    autoplay: {
      delay: 3000,
    },
    navigation: {
      nextEl: ".feedback__button-prev",
      prevEl: ".feedback__button-next",
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },
  });

  /*======================================
  testimonial activation js
  ========================================*/

  var testimonial = new Swiper(".testimonial__active", {
    loop: false,
    freemode: true,
    slidesPerView: 1,
    spaceBetween: 30,
    centeredSlides: false,
    speed: 1000,
    navigation: {
      nextEl: ".testimonial__button-next",
      prevEl: ".testimonial__button-prev",
    },
    pagination: {
      el: ".bd-swiper-dot",
      clickable: true,
    },

  });

  /*======================================
  postbox blog activation js
  ========================================*/

  var blog = new Swiper(".postbox__slider", {
    loop: true,
    freemode: true,
    slidesPerView: 1,
    spaceBetween: 0,
    centeredSlides: false,
    speed: 1000,
    navigation: {
      nextEl: ".postbox__button-next",
      prevEl: ".postbox__button-prev",
    },

  });

  /*======================================
  testimonial slider style two js  
  ========================================*/
  var testimonial = new Swiper(".testimonial__active-two", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    roundLengths: true,
    speed:700,
    autoplay: {
      delay: 4500,
    },
    navigation: {
      nextEl: ".testimonial__button-next",
      prevEl: ".testimonial__button-prev",
    },
    breakpoints: {
      1400: {
        slidesPerView: 2,
      },
      1200: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 1,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });

  const tilt = document.querySelectorAll(".tilt");

  VanillaTilt.init(tilt, {
    reverse: true,
    max: 3,
    speed: 400,
    // glare: true,
    reset: true,
    perspective: 500,
    transition: true,
    "max-glare": 0.75,
    "glare-prerender": false,
    gyroscope: true,
    gyroscopeMinAngleX: -45,
    gyroscopeMaxAngleX: 45,
    gyroscopeMinAngleY: -45,
    gyroscopeMaxAngleY: 45
  });

  // For language
  if ($("#header-lang-toggle").length > 0) {
    window.addEventListener('click', function (e) {

      if (document.getElementById('header-lang-toggle').contains(e.target)) {
        $(".header-lang ul").toggleClass("lang-list-open");
      }
      else {
        $(".header-lang ul").removeClass("lang-list-open");
      }
    });
  }

  // for header currency
  if ($("#tp-header-currency-toggle").length > 0) {
    window.addEventListener('click', function (e) {

      if (document.getElementById('tp-header-currency-toggle').contains(e.target)) {
        $(".tp-header-currency ul").toggleClass("tp-currency-list-open");
      }
      else {
        $(".tp-header-currency ul").removeClass("tp-currency-list-open");
      }
    });
  }

  // for header setting
  if ($("#tp-header-setting-toggle").length > 0) {
    window.addEventListener('click', function (e) {

      if (document.getElementById('tp-header-setting-toggle').contains(e.target)) {
        $(".tp-header-setting ul").toggleClass("tp-setting-list-open");
      }
      else {
        $(".tp-header-setting ul").removeClass("tp-setting-list-open");
      }
    });
  }


  // $('.accordion-border')
  $('.accordion-border .accordion-item:first-child').addClass('active');
  $('.accordion-item').on('click', function () {

    $('.accordion-item.active').removeClass('active');
    $(this).addClass('active');

  });


  /*======================================
  content hidden class js
  ========================================*/
  $('.contentHidden').remove();

  // Masonry Js
  $(".grid").imagesLoaded(function () {
    // init Isotope
    var $grid = $(".grid").isotope({
      itemSelector: ".grid-item",
      percentPosition: true,
      masonry: {
        // use outer width of grid-sizer for columnWidth
        columnWidth: ".grid-item",
      },
    });

    // filter items on button click
    $(".bf-filter-btn").on("click", "button", function () {
      var filterValue = $(this).attr("data-filter");
      $grid.isotope({ filter: filterValue });
    });

    //for menu active class
    $(".bf-filter-btn button").on("click", function (event) {
      $(this).siblings(".active").removeClass("active");
      $(this).addClass("active");
      event.preventDefault();
    });
  });




})(jQuery);
