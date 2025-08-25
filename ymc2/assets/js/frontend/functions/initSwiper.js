import { convertKeysToCamelCase } from "../utils/convertKeysToCamelCase.js";

export function initSwiper(filterId, counter, carouselSettings) {
    const settings = convertKeysToCamelCase(carouselSettings);
    const { general, pagination, navigation, scrollbar, useCustomSettings } = settings;
    const { autoHeight, autoplay, autoplayDelay, loop, centeredSlides, slidesPerView, spaceBetween, mousewheel, speed, effect } = general;
    const swiperEl = document.querySelector(`.js-swiper-${filterId}-${counter}`);

    if (!swiperEl) return;

    if(navigation.visible) {
        swiperEl.querySelectorAll('.post-layout_carousel').forEach(slide => {
            slide.classList.add('has-navigation-arrows')
        });
    }

    swiperEl.closest('.js-ajax-content').classList.add('posts-carousel');

    if( useCustomSettings ) return;

    // Initialize Swiper
    new Swiper(swiperEl, {
        grabCursor: true,
        spaceBetween: spaceBetween,
        centeredSlides: centeredSlides,
        autoHeight: autoHeight,
        autoplay: autoplay ? { delay: autoplayDelay } : false,
        loop: loop,
        slidesPerView: slidesPerView,
        mousewheel: mousewheel ? { invert: true } : false,
        speed: speed,
        effect: effect,
        fadeEffect: effect === 'fade' ? { crossFade: true } : '',
        creativeEffect: effect === 'creative' ? {
            prev: { shadow: true, translate: [0, 0, -400] },
            next: { translate: ["100%", 0, 0] },
        } : '',
        // Pagination
        pagination: pagination.visible ?  {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets : pagination.dynamicBullets,
            type: pagination.type,
        } : false,
        // Navigation
        navigation: navigation.visible ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        } : { enabled: false },
        // Scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
            draggable: true,
            enabled: scrollbar.visible
        }
    });

}