import { YMC_AJAX_URL } from "../core/constants.js";
import { ymcHooks } from "./hooks.js";
import { setupNumericPagination } from "../functions/setupNumericPagination.js";
import { setupLoadMorePagination } from "../functions/setupLoadMorePagination.js";
import { removePreloader, setPreloader } from "./preloaderUtils.js";
import { initInfiniteScroll } from "./initInfiniteScroll.js";
import { initSwiper } from "../functions/initSwiper.js";


/**
 * Fetch filtered posts
 * @param container
 * @param mode - 'replace' | 'append'
 * @returns {Promise<void>}
 */
export async function fetchFilteredPosts(container, mode = 'replace') {
    const params = JSON.parse(container.dataset.params);

    const formData = new FormData();
    formData.append('action', 'get_filtered_posts');
    formData.append('nonce_code', _ymc_fg_object.getPosts_nonce);
    formData.append('params', JSON.stringify(params));
    formData.append('filter_id', params.filter_id);

    const content = container.querySelector('.js-ajax-content');

    ymcHooks.doAction('ymc/grid/before_update', container);
    ymcHooks.doAction('ymc/grid/before_update_'+params.filter_id, container);
    ymcHooks.doAction('ymc/grid/before_update_'+params.filter_id+'_'+params.counter, container);

    // Preloader
    let preloaderIcon = params.preloader_settings.icon+'.svg';
    const filterPreloader     = params.preloader_settings.filter_preloader;
    const preloaderFilterCSS  = params.preloader_settings.custom_filters_css;
    const filterId            = params.filter_id

    preloaderIcon = ymcHooks.applyFilters('ymc/grid/preloader', preloaderIcon);
    preloaderIcon = ymcHooks.applyFilters('ymc/grid/preloader_'+params.filter_id, preloaderIcon);

    (mode === 'append') ?
        setPreloader(container, 'append','js-ajax-content', preloaderIcon, preloaderFilterCSS, filterPreloader, filterId) :
        setPreloader(container,'replace','js-ajax-content', preloaderIcon, preloaderFilterCSS, filterPreloader, filterId);

    let response;

    try {
        response = await fetch(YMC_AJAX_URL, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            console.error(`HTTP error: ${response.status}`);
            return;
        }

        const jsonData = await response.json();
        if (!jsonData.success) {
            console.warn('No renderedHTML in response');
            return;
        }

        container.querySelector('.pagination')?.remove();

        const {
            rendered_posts,
            rendered_pagination,
            pagination_type,
            found_posts,
            paged,
            max_num_pages,
            posts_count,
            requires_swiper,
            scroll_filter_bar,
            results_text
        } = jsonData.data;

        if (found_posts > 0) {
            content.classList.remove('posts-no-found');

            switch (pagination_type) {
                case 'numeric':
                    content.innerHTML = rendered_posts;
                    if (rendered_pagination) {
                        content.insertAdjacentHTML('afterend', rendered_pagination);
                        setupNumericPagination(container);
                    }
                    // Scroll Filter Bar
                    if(scroll_filter_bar === 'yes') {
                        const offset = 30;
                        container.querySelector('.filter-layout').scrollIntoView({behavior: "smooth", block: "start", inline: "start"});
                        const top = container.getBoundingClientRect().top + window.scrollY - offset;
                        window.scrollTo({top, behavior: 'smooth'});
                    }
                    break;

                case 'loadmore':
                    if (mode === 'append') {
                        content.insertAdjacentHTML('beforeend', rendered_posts);
                    } else {
                        content.innerHTML = rendered_posts;
                    }

                    if (rendered_pagination && max_num_pages > paged) {
                        content.insertAdjacentHTML('afterend', rendered_pagination);
                        setupLoadMorePagination(container);
                    }
                    break;

                case 'infinite':
                    if (mode === 'append') {
                        content.insertAdjacentHTML('beforeend', rendered_posts);
                    } else {
                        content.innerHTML = rendered_posts;
                    }

                    if(max_num_pages > paged) {
                        const fnObserve = initInfiniteScroll(container);
                        fnObserve.observe(content.querySelector('.post-card:last-child'));
                    }
                    break;

                default:
                    content.innerHTML = rendered_posts;
                    break;
            }

            // Swiper init
            if (requires_swiper) {
                if (typeof Swiper === 'undefined') {
                    const cssLink = document.createElement('link');
                    cssLink.rel = 'stylesheet';
                    cssLink.href = _ymc_fg_object.path + 'assets/css/lib/swiper.min.css';
                    document.head.appendChild(cssLink);

                    const script = document.createElement('script');
                    script.src = _ymc_fg_object.path + 'assets/js/lib/swiper.min.js';
                    script.onload = () => {
                        initSwiper(params.filter_id, params.counter, params.carousel_settings);
                    };
                    document.body.appendChild(script);
                } else {
                    initSwiper(params.filter_id, params.counter, params.carousel_settings);
                }
            }

        } else {
            content.classList.add('posts-no-found');
            content.innerHTML = rendered_posts;
        }

        const resultsEl = container.querySelector('.js-results-found');
        if (resultsEl) {
            resultsEl.textContent = results_text ? `${found_posts} ${results_text}` : '';
        }

        ymcHooks.doAction('ymc/grid/after_update', jsonData.data, container, );
        ymcHooks.doAction('ymc/grid/after_update_'+params.filter_id, jsonData.data,container);
        ymcHooks.doAction('ymc/grid/after_update_'+params.filter_id+'_'+params.counter, jsonData.data, container);

    } catch (e) {
        console.error('Error occurred:', e);
    } finally {
        removePreloader(container);

        ymcHooks.doAction('ymc/grid/after_complete', response.status, container);
        ymcHooks.doAction('ymc/grid/after_complete_'+params.filter_id, response.status, container);
        ymcHooks.doAction('ymc/grid/after_complete_'+params.filter_id+'_'+params.counter, response.status, container);
    }
}

