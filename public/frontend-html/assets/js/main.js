// Register GSAP ScrollTrigger Plugin
gsap.registerPlugin(ScrollTrigger);

/** Additional Initializations **/

jQuery(function () {
  initLenis();
  initSplitText();
  setupSplits();
  initScrollTriggerParallaxScroll();
  initOthers();
  forms();
});


function initLenis() {
  const scroll = new Lenis({
    duration: 1,
    smooth: true,
    smoothTouch: false,
  });

  scroll.on('scroll', ScrollTrigger.update);

  gsap.ticker.add((time) => {
    scroll.raf(time * 1000);
  });

  ScrollTrigger.refresh();
  gsap.ticker.lagSmoothing(0);



  const modalElements = document.getElementsByClassName('genericmodal');

  Array.from(modalElements).forEach((modalElement) => {
    modalElement.addEventListener('shown.bs.modal', function () {
      scroll.stop(); // Stop Lenis
      document.documentElement.classList.add('modal-open-lenis');
    });

    modalElement.addEventListener('hidden.bs.modal', function () {
      scroll.start(); // Resume Lenis
      document.documentElement.classList.remove('modal-open-lenis');
    });
  });

  // Allow scroll inside modal content
  document.querySelectorAll('.modal_scroll').forEach((el) => {
    el.addEventListener('wheel', function (e) {
      e.stopPropagation();
    });
  });



  // Add "Go to Top" button functionality
  const goToTopButton = document.getElementById('backtotop');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      goToTopButton.classList.add('show');
    } else {
      goToTopButton.classList.remove('show');
    }
  });

  goToTopButton.addEventListener('click', () => {
    scroll.scrollTo(0, {
      duration: 1.5,
    });
  });

  // Safari-specific fallback
  if (navigator.userAgent.includes('Safari') && !navigator.userAgent.includes('Chrome')) {
    console.log('Safari detected: Adjusting settings...');
    scroll.smooth = false; // Disable smooth scrolling for Safari
  }
}

function initSplitText() {
  if (window.innerWidth >= 1100) { // Check if viewport width is 1100px or greater
    document.fonts.ready.then(() => {
      gsap.utils.toArray('.quote').forEach(title => {
        const childSplit = new SplitText(title, { type: "lines", linesClass: "split-child" });
        const parentSplit = new SplitText(title, { linesClass: "split-parent" });

        gsap.from(childSplit.lines, {
          scrollTrigger: {
            trigger: title,
            start: 'top 100%',
            toggleActions: "restart pause resume reverse",
          },
          duration: 1,
          yPercent: 100,
          ease: "power4",
          stagger: 0.1,
        });
      });
    });
  }
}

function setupSplits() {
  if (window.innerWidth >= 1025) {
    const quotes = document.querySelectorAll(".opac");
    quotes.forEach(quote => {
      var text = new SplitText(quote, {
        type: "words",
      });
      ScrollTrigger.update();
      quote.anim = gsap.from(text.words, {
        stagger: 1,
        opacity: 0.2,
        scrollTrigger: {
          trigger: quote,
          start: "top 50%",
          end: "bottom 50%",
          toggleActions: "restart pause resume reverse",
          scrub: 1,
        },
      });
    });
  }
}

function initScrollTriggerParallaxScroll() {
  // Check viewport width
  if (window.innerWidth >= 1025) {
    $('[data-parallax-strength-vertical]').each(function () {
      const triggerElement = $(this).attr('data-parallax-trigger') ? $($(this).attr('data-parallax-trigger')) : $(this);
      const target = $(this).find('[data-parallax-target]');
      const strength = $(this).data('parallax-strength-vertical') * 20;

      gsap.timeline({
        scrollTrigger: {
          trigger: triggerElement,
          start: "0% 100%",
          end: "100% 0%",
          scrub: true,
        }
      }).fromTo(target, { yPercent: -strength / 2 }, { yPercent: strength / 2, ease: "none" });
    });
  }
}

function initOthers() {
  /* For Dropdown */
  document.addEventListener("DOMContentLoaded", function () {
    /////// Prevent closing from click inside dropdown
    document.querySelectorAll('.dropdown-menu').forEach(function (element) {
      element.addEventListener('click', function (e) {
        e.stopPropagation();
      });
    })
  });
  // DOMContentLoaded  end


  /* Input Plus Minus */
  $('.minus').click(function () {
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.change();
    return false;
  });

  $('.plus').click(function () {
    var $input = $(this).parent().find('input');
    $input.val(parseInt($input.val()) + 1);
    $input.change();
    return false;
  });




  jQuery(document).ready(function () {

    const swiper__1 = new Swiper('.swiper__1', {
      slidesPerView: 1,
      loop: true,
      spaceBetween: 5,
      autoplay: {
        delay: 3000,
        pauseOnMouseEnter: true,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: '.swiper__1--next',
        prevEl: '.swiper__1--prev',
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        type: 'bullets', // Enables bullets for navigation
        bulletClass: 'swiper-pagination-bullet',
        bulletActiveClass: 'swiper-pagination-bullet-active'
      },
      on: {
        slideChangeTransitionStart: function () {
          resetProgressBar();
          animateProgressBar(swiper__1.params.autoplay.delay); // Start progress bar immediately
        },
        slideChangeTransitionEnd: function () {
          animateProgressBar(swiper__1.params.autoplay.delay);
        }
      }
    });

    var swiper2 = new Swiper(".swiper--product-thumbs", {
      spaceBetween: 10,
      slidesPerView: 5,
      slidesPerGroup: 4,
      freeMode: true,
      scrollbar: {
        el: ".swiper-scrollbar",
        hide: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
    var swiper = new Swiper(".swiper--product-detail", {
      slidesPerView: 1,
      watchSlidesProgress: true,
      thumbs: {
        swiper: swiper2,
      },
    });

    // Instantiate EasyZoom instances
    //var $easyzoom = $('.easyzoom').easyZoom();

    function animateProgressBar(duration) {
      const progressFill = document.querySelector('.swiper-pagination-progressbar-fill');
      if (!progressFill) return;

      let startTime;

      function updateProgressBar(timestamp) {
        if (!startTime) startTime = timestamp;
        const elapsed = timestamp - startTime;
        const progress = Math.min((elapsed / duration) * 100, 100);

        progressFill.style.width = `${progress}%`;

        if (progress < 100) {
          requestAnimationFrame(updateProgressBar);
        }
      }

      requestAnimationFrame(updateProgressBar);
    }

    function resetProgressBar() {
      const progressFill = document.querySelector('.swiper-pagination-progressbar-fill');
      if (progressFill) {
        progressFill.style.width = '0%';
      }
    }



    const swiper__2 = new Swiper('.swiper__2', {
      slidesPerView: 4,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 4000,
        pauseOnMouseEnter: true,
        disableOnInteraction: false, // Autoplay will continue after user interaction
      },
      navigation: {
        nextEl: '.swiper__2--next',
        prevEl: '.swiper__2--prev',
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          autoplay: false,
        },
        568: {
          slidesPerView: 2,
          spaceBetween: 15,
          autoplay: false,
        },
        769: {
          slidesPerView: 3,
        },
        1040: {
          slidesPerView: 4,
        },
        1921: {
          slidesPerView: 4,
        }
      },
    });

    const swiper__3 = new Swiper('.swiper__3', {
      slidesPerView: "auto",
      spaceBetween: 20,
      centeredSlides: false, // Start from the left
      initialSlide: 0, // Start with first slide on the left
      loop: false,
      pagination: {
        el: ".swiper__3-pagination",
        type: "fraction",
      },
      navigation: {
        nextEl: '.swiper__3--next',
        prevEl: '.swiper__3--prev',
      },
      on: {
        init: function () {
          setActiveSlide(this); // Pass the Swiper instance
        },
        slideChangeTransitionEnd: function () {
          setActiveSlide(this); // Pass the Swiper instance
        }
      }
    });

    const swiper__4 = new Swiper('.swiper__4', {
      slidesPerView: 5,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 4000,
        pauseOnMouseEnter: true,
        disableOnInteraction: false, // Autoplay will continue after user interaction
      },
      navigation: {
        nextEl: '.swiper__4--next',
        prevEl: '.swiper__4--prev',
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          autoplay: false,
        },
        568: {
          slidesPerView: 2,
          spaceBetween: 15,
          autoplay: false,
        },
        769: {
          slidesPerView: 3,
        },
        1040: {
          slidesPerView: 4,
        },
        1921: {
          slidesPerView: 4,
        }
      },
    });

    const swiper__5 = new Swiper('.swiper__5', {
      slidesPerView: "auto",
      spaceBetween: 20,
      navigation: {
        nextEl: '.swiper__5--next',
        prevEl: '.swiper__5--prev',
      },
    });

    const swiper__check_more = new Swiper('.swiper__check-more', {
      slidesPerView: 5,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 4000,
        pauseOnMouseEnter: true,
        disableOnInteraction: false, // Autoplay will continue after user interaction
      },
      navigation: {
        nextEl: '.swiper__chk--next',
        prevEl: '.swiper__chk--prev',
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          autoplay: false,
        },
        568: {
          slidesPerView: 2,
          spaceBetween: 15,
          autoplay: false,
        },
        769: {
          slidesPerView: 3,
        },
        1040: {
          slidesPerView: 5,
        },
        1921: {
          slidesPerView: 5,
        }
      },
    });

    const swiper__pl = new Swiper('.swiper__pl', {
      slidesPerView: 5,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 4000,
        pauseOnMouseEnter: true,
        disableOnInteraction: false, // Autoplay will continue after user interaction
      },
      navigation: {
        nextEl: '.swiper__pl--next',
        prevEl: '.swiper__pl--prev',
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
          autoplay: false,
        },
        568: {
          slidesPerView: 2,
          spaceBetween: 15,
          autoplay: false,
        },
        769: {
          slidesPerView: 3,
        },
        1040: {
          slidesPerView: 5,
        },
        1921: {
          slidesPerView: 5,
        }
      },
    });

    function setActiveSlide(swiperInstance) {
      document.querySelectorAll('.swiper-slide').forEach(slide => {
        slide.classList.remove('active');
      });

      let visibleSlides = document.querySelectorAll('.swiper-slide');
      let centerIndex = Math.floor(swiperInstance.activeIndex + (swiperInstance.params.slidesPerView / 2));

      if (visibleSlides[centerIndex]) {
        visibleSlides[centerIndex].classList.add('active');
      }
    }

  });
}

function forms() {
  let allFormField = document.querySelectorAll('.form-field');
  let allFormSelect = document.querySelectorAll('.form-select');

  // Handle .form-field (input, textarea, etc.)
  setTimeout(function () {
    for (let i = 0; i < allFormField.length; i++) {
      if (allFormField[i].value) {
        allFormField[i].parentNode.classList.add('has-value');
      }
    }
  }, 100);

  for (let i = 0; i < allFormField.length; i++) {
    allFormField[i].addEventListener('focus', function () {
      this.parentNode.classList.add('has-value');
    });
    allFormField[i].addEventListener('blur', function () {
      if (!this.value) {
        this.parentNode.classList.remove('has-value');
      }
    });
  }

  // Handle .form-select (select elements)
  setTimeout(function () {
    for (let i = 0; i < allFormSelect.length; i++) {
      if (allFormSelect[i].value) {
        allFormSelect[i].parentNode.classList.add('has-value');
      }
    }
  }, 100);

  for (let i = 0; i < allFormSelect.length; i++) {
    allFormSelect[i].addEventListener('change', function () {
      if (this.value) {
        this.parentNode.classList.add('has-value');
      } else {
        this.parentNode.classList.remove('has-value');
      }
    });
  }
}

/* Color Swatches */

document.addEventListener("DOMContentLoaded", () => {
  let productCards = document.querySelectorAll(".product-card");

  productCards.forEach(card => {
    let circles = card.querySelectorAll(".circle");
    let mainImages = card.querySelectorAll(".main-images img");

    circles.forEach(circle => {
      let colorHex = circle.getAttribute("data-hex");
      let colorName = circle.getAttribute("data-color");

      // Set background color dynamically
      if (colorHex) {
        circle.style.backgroundColor = colorHex;
      }

      // ✅ Apply default box-shadow to the initially active circle
      if (circle.classList.contains("active")) {
        circle.style.boxShadow = `0 0 0 2px #fff, 0 0 0 3px ${colorHex}`;
      }

      circle.addEventListener("click", () => {
        // Remove active class and reset box-shadow within this product card only
        let activeCircle = card.querySelector(".circle.active");
        if (activeCircle) {
          activeCircle.classList.remove("active");
          activeCircle.style.boxShadow = "none"; // Remove box-shadow from previous active
        }

        // Add active class to clicked circle
        circle.classList.add("active");

        // Ensure colorHex exists before applying styles
        if (colorHex) {
          // Apply dynamic box-shadow
          circle.style.boxShadow = `0 0 0 2px #fff, 0 0 0 3px ${colorHex}`;
        }

        // Update the main image based on selected color
        let activeImage = card.querySelector(".main-images .active");
        if (activeImage) {
          activeImage.classList.remove("active");
        }

        let newActiveImage = card.querySelector(`.main-images img[data-color="${colorName}"]`);
        if (newActiveImage) {
          newActiveImage.classList.add("active");
        }
      });
    });
  });
});
