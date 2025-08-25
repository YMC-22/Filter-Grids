import { YMC_PATH } from "../core/constants.js";

export function setPreloader(
    container,
    mode = 'replace',
    target = 'js-ajax-content',
    preloaderName = null,
    filterStyle = null,
    filterPreloader = null,
    filterId = null) {

    const position  = mode === 'append' ? "beforeend" : "afterbegin";
    const className = mode === 'append' ? "preloader--is-load-more" : "preloader--is-numeric";
    const filters = {
        'none': 'none',
        'brightness': 'brightness(1.4)',
        'contrast': 'contrast(1.5)',
        'grayscale': 'grayscale(100%)',
        'invert': 'invert(100%)',
        'opacity': 'opacity(40%)',
        'saturate': 'saturate(2)',
        'sepia': 'sepia(100%)',
    };

    const iconKey = preloaderName || 'dual_arc.svg';
    const baseKey = iconKey.replace(/\.\w+$/, '');

    let filteredIconUrl = null;

    if (typeof ymcHooks !== 'undefined') {
        const candidates = [];

        if (filterId) {
            candidates.push(`ymc/grid/preloader_${filterId}`);
        }
        candidates.push(`ymc/grid/preloader`);

        for (const hook of candidates) {
            filteredIconUrl = ymcHooks.applyFilters(hook, null);
            if (filteredIconUrl) break;
        }
    }

    const imgSrc = filteredIconUrl || `${YMC_PATH}/assets/images/preloaders/${iconKey}`;

    let styleAttr = '';

    if(filterPreloader === 'custom_filter') {
        if (filterStyle) {
            if (filterStyle.startsWith('filter:')) {
                filterStyle = filterStyle.replace(/^filter:\s*/i, '');
            }
            styleAttr = ` style="filter: ${filterStyle};"`;
        }
    } else {
        styleAttr = ` style="filter: ${filters[filterPreloader]};"`;
    }

    const html = `
        <div class="preloader ${className}">
            <img src="${imgSrc}" alt="preloader"${styleAttr}>
        </div>
        <div class="overlay"></div>`;

    container.querySelector(`.${target}`)?.insertAdjacentHTML(position, html);
}

export function removePreloader(container) {
    container.querySelector('.preloader')?.remove();
    container.querySelector('.overlay')?.remove();
}


