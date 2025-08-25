import { YMC_APPEARANCE } from "../core/constants.js";
import { tooltip } from '../utils/tooltip.js';

export function setupAppearanceControls($) {
    YMC_APPEARANCE.querySelector('.js-term-sort-direction').addEventListener('change', disabledFieldSortByTerms);
    YMC_APPEARANCE.querySelector('.js-pagination_type').addEventListener('change', disabledFieldsPagination);
    YMC_APPEARANCE.querySelector('.js-post-order-by').addEventListener('change', toggleOrderByFields);
    YMC_APPEARANCE.querySelector('.js-add-order-multiple-field').addEventListener('click', addPostOrderMultipleField(0, $));
    YMC_APPEARANCE.querySelectorAll('.js-remove-order-field').forEach(btn => {
        btn.addEventListener('click', removePostOrderMultipleField);
    });
}


function disabledFieldSortByTerms() {
    const value = this.value;
    const termSortField = YMC_APPEARANCE.querySelector('.js-term-sort-field');
    const showSortOptions = (value === 'manual');
    termSortField.style.display = showSortOptions ? 'none' : 'block';
}

function disabledFieldsPagination() {
    const value = this.value;
    const navigationButtons = YMC_APPEARANCE.querySelector('.js-navigation-buttons');
    const loadMoreButton = YMC_APPEARANCE.querySelector('.js-load-more-button');
    const showNavigationButtons = (value === 'numeric');
    const showloadMoreButton = (value === 'loadmore');
    navigationButtons.style.display = showNavigationButtons ? 'block' : 'none';
    loadMoreButton.style.display = showloadMoreButton ? 'block' : 'none';
}

function toggleOrderByFields() {
    const value = this.value;
    const fieldsMetaKey = YMC_APPEARANCE.querySelector('.js-order-fields-meta-key');
    const fieldsMultipleFields = YMC_APPEARANCE.querySelector('.js-order-fields-multiple-fields');

    const showOrderByFieldsMetaKey = (value === 'meta_key');
    fieldsMetaKey.style.display = showOrderByFieldsMetaKey ? 'block' : 'none';

    const showOrderByFieldsMultipleFields = (value === 'multiple_fields');
    fieldsMultipleFields.style.display = showOrderByFieldsMultipleFields ? 'block' : 'none';
}

function addPostOrderMultipleField(fieldIndex, $) {
    return function() {
        const container = YMC_APPEARANCE.querySelector('.post-order-fields-inner');
        const counter = container.querySelectorAll('.field-group').length;
        if(counter > 0) {
            fieldIndex = counter;
        }
        const template = YMC_APPEARANCE.querySelector('.field-multiple-template').innerHTML;
        const html = template.replace(/index/g, fieldIndex);
        container.insertAdjacentHTML('beforeend', html);
        fieldIndex++;
        tooltip($);

        YMC_APPEARANCE.querySelectorAll('.js-remove-order-field').forEach(btn => {
            btn.addEventListener('click', removePostOrderMultipleField);
        });
    }
}

function removePostOrderMultipleField() {
    this.closest('.field-group').remove();
}


