const swiper = new Swiper('.swiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    zoom: {
      maxRatio: 5,
    },
    mousewheel: {
      invert: false,
    },
    slidesPerView: 'auto',
    grabCursor: true,
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  
    // And if we need scrollbar
    scrollbar: {
      el: '.swiper-scrollbar',
    },
});

// Initialize the thumbnails swiper
const thumbSwiper = new Swiper('.thumbs-slider', {
  slidesPerView: 4,          // Number of visible thumbnails
  spaceBetween: 10,          // Space between thumbnails
  watchSlidesProgress: true, // Syncs main slider with thumbs
});

// Initialize the main swiper and link it to the thumbnails swiper
const mainSwiper = new Swiper('.main-slider', {
  spaceBetween: 10,
  pagination: {
      el: '.swiper-pagination',
      clickable: true,
  },
  thumbs: {
      swiper: thumbSwiper,   // Connect the thumbnail swiper
  },
});
