import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";
import { setPreloader, removePreloader } from '../utils/preloaderUtils.js';
import { YMCLoadTemplate } from '../utils/templateUtils.js';


export function setupFilterBuilder() {
    toggleFilterBuilder();
    setupRemoveFilter();
    const filterIndex = YMC_CONTAINER.querySelectorAll('#layouts .filter-builder .js-filter-group .filter-item').length;
    YMC_CONTAINER.querySelector('#layouts .filter-builder .js-add-filter').addEventListener('click', callbackFilterBuilder(filterIndex));
    YMC_CONTAINER.querySelector('#layouts .js-toggle-filter-builder').addEventListener('change', toggleFilterBuilder);

}

function setupRemoveFilter() {
    YMC_CONTAINER.querySelectorAll('#layouts .filter-builder .js-remove-filter').forEach(button => {
        button.addEventListener('click', removeFilter);
    });
}

function callbackFilterBuilder(filterIndex) {
    return async function () {
        const container = YMC_CONTAINER.querySelector('.filter-builder .js-filter-group');
        const params = {
            action: 'action_get_selected_taxonomies',
            nonce_code : _ymc_fg_object.getSelectTaxAjax_nonce,
            payload: JSON.stringify({
                'post_id': YMC_POST_ID
            })
        }

        try {
            setPreloader();
            const response = await fetch(YMC_AJAX_URL,
                {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams(params).toString()
                });
            if (response.ok) {
                const data = await response.json();
                const isTaxSelected = (data.taxSelected.length > 0);
                const isDisabledTax = (data.taxSelected.length > 0) ? '' : 'is-disabled';
                const dataFilter = {
                    filterIndex:   filterIndex,
                    isTaxSelected: isTaxSelected,
                    isDisabledTax : isDisabledTax,
                    taxSelected:   data.taxSelected,
                    filtersTypes:  data.filtersTypes,
                    placements:    data.placements
                }
                const tmplPosts = new YMCLoadTemplate(container, 'tmpl-filter-builder');
                tmplPosts.createAndWait(function(template, element) {
                    element.insertAdjacentHTML("beforeend", template(dataFilter));
                    setupRemoveFilter();
                });

                filterIndex++;
            }
        } catch (e) {
            console.error('Error occurred:', e)
        } finally {
            removePreloader();
        }
    }
}

function removeFilter() {
    const filterItem = this.closest('.filter-item');
    if (filterItem) {
        filterItem.remove();
    }
}

function toggleFilterBuilder() {
    const select = YMC_CONTAINER.querySelector('.js-toggle-filter-builder');
    const filterBuilder = YMC_CONTAINER.querySelector('.filter-builder');
    if (select.value === 'composite') {
        filterBuilder.style.display = 'block';
    } else {
        filterBuilder.style.display = 'none';
    }
}







