import { YMC_AJAX_URL } from "../core/constants.js";
import { ymcHooks } from "../utils/hooks.js";
import { removePreloader, setPreloader } from "../utils/preloaderUtils.js";

export function initPopupTriggers() {
    ymcHooks.addAction('ymc/grid/after_update', function(data, container) {
        document.querySelectorAll('.ymc-filter-grids .js-ymc-popup-trigger').forEach(button => {
            button.addEventListener('click', openPopup);
        });
    });
}

export async function openPopup(e) {
    e.preventDefault();
    const body = document.querySelector('body');
    const container = this.closest('.js-ymc-container');
    const params = JSON.parse(container.dataset.params);
    const postCard = this.closest('.post-card');
    const gridId = this.getAttribute('data-grid-id');
    const postId = this.getAttribute('data-post-id');
    const counter = this.getAttribute('data-counter');
    const popup = document.querySelector(`#ymc-popup-${gridId}`);
    const popupWrapper = popup.querySelector('.js-ymc-popup-wrapper');
    const btnClose = popup.querySelector(`.js-ymc-btn-popup-close`);
    const popupBody = popup.querySelector('.js-ymc-popup-body');

    // Preloader
    let preloaderIcon = params.preloader_settings.icon+'.svg';
    const filterPreloader     = params.preloader_settings.filter_preloader;
    const preloaderFilterCSS  = params.preloader_settings.custom_filters_css;
    const filterId            = params.filter_id

    if (!popup) return;

    body.style.overflow = 'hidden';

    btnClose.addEventListener('click', function (e) {
        popup.classList.remove('is-open');
        body.style.overflow = 'auto';
    });

    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            popup.classList.remove('is-open');
            body.style.overflow = 'auto';
        }
    });

    // Fetch data for popup
    const formData = new FormData();
    formData.append('action', 'get_post_to_popup');
    formData.append('nonce_code', _ymc_fg_object.getPostToPopup_nonce);
    formData.append('post_id', postId);
    formData.append('grid_id', gridId);
    formData.append('counter', counter);
    popupBody.innerHTML = '';

    preloaderIcon = ymcHooks.applyFilters('ymc/grid/preloader', preloaderIcon);
    preloaderIcon = ymcHooks.applyFilters('ymc/grid/preloader_'+params.filter_id, preloaderIcon);

    setPreloader(postCard,'replace','js-grid-item', preloaderIcon, preloaderFilterCSS, filterPreloader, filterId);

    ymcHooks.doAction('ymc/popup/before_open', popup);
    ymcHooks.doAction('ymc/popup/before_open_'+params.filter_id, popup);

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

        const { body } = jsonData.data;
        popupBody.innerHTML = body;
        popup.classList.add('is-open');

        ymcHooks.doAction('ymc/popup/after_open', popup, body);
        ymcHooks.doAction('ymc/popup/after_open_'+params.filter_id, popup, body);

    } catch (e) {
        console.error('Error occurred:', e);
    }  finally {
        removePreloader(postCard);
    }

}


