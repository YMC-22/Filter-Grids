import { YMC_ADVANCED, YMC_PATH } from "../core/constants.js";
export function initPreloader() {
    const selectIcon      = YMC_ADVANCED.querySelector('.js-preloader-icon');
    const selectFilter    = YMC_ADVANCED.querySelector('.js-preloader-filters');
    const customFilterBox = YMC_ADVANCED.querySelector('.js-preloader-custom-filters');
    const previewWrapper  = YMC_ADVANCED.querySelector('.js-preview-preloader');
    const basePath         = YMC_PATH + 'assets/images/preloaders/';

    const applyFilter = (filterKey) => {
        const img = previewWrapper.querySelector('img');
        if (!img) return;

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

        if (filterKey === 'custom_filter') {
            const input = customFilterBox?.querySelector('input');
            let value = input?.value?.trim() || 'none';
            if (value.startsWith('filter:')) {
                value = value.replace(/^filter:\s*/i, '');
            }
            img.style.cssText = `filter: ${value};`;
        } else {
            img.style.filter = filters[filterKey] || 'none';
        }
    };

    const toggleCustomFilterBox = (filterKey) => {
        if (!customFilterBox) return;

        const input = customFilterBox.querySelector('input');

        if (filterKey === 'custom_filter') {
            customFilterBox.style.display = 'block';
            if (input && !input.dataset.listenerAttached) {
                input.addEventListener('input', () => {
                    applyFilter('custom_filter');
                });
                input.dataset.listenerAttached = 'true';
            }
        } else {
            customFilterBox.style.display = 'none';
        }
    };

    const renderPreloader = (iconKey) => {
        if (iconKey === 'none') {
            previewWrapper.innerHTML = '';
        } else {
            const img = document.createElement('img');
            img.src = basePath + iconKey + '.svg';
            img.alt = iconKey + ' preloader';
            img.width = 70;
            img.height = 70;
            previewWrapper.innerHTML = '';
            previewWrapper.appendChild(img);

            const selectedFilter = selectFilter?.value || 'none';

            if (selectedFilter === 'custom_filter') {
                setTimeout(() => {
                    applyFilter('custom_filter');
                }, 0);
            } else {
                applyFilter(selectedFilter);
            }
        }
    };

    selectIcon?.addEventListener('change', function () {
        renderPreloader(this.value);
    });

    selectFilter?.addEventListener('change', function () {
        const selectedFilter = this.value;
        toggleCustomFilterBox(selectedFilter);
        applyFilter(selectedFilter);
    });

    if (selectIcon?.value && selectIcon.value !== 'none') {
        renderPreloader(selectIcon.value);
    }

    if (selectFilter?.value) {
        toggleCustomFilterBox(selectFilter.value);
    }
}




