import { YMC_BODY, YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";
import { setPreloader, removePreloader } from "../utils/preloaderUtils.js";
import { notification } from "../utils/notification.js";

/**
 * ThickBox Taxonomy
 */
export function thickBoxTaxonomy() {
    YMC_CONTAINER.querySelectorAll('#general .js-tax-insert .js-tax-settings').forEach( tax => {
        tax.addEventListener('click', openThickBoxTaxonomy);
    });
}

function openThickBoxTaxonomy() {
    YMC_CONTAINER.querySelectorAll('#general .js-tax-insert .taxonomies-list__item').forEach(item =>
        item.classList.remove('is-open'));
    
    let taxSelected = this.closest('.taxonomies-list__item');
    taxSelected.classList.add('is-open');

    let {
        taxName : taxSlug,
        taxOriginalName,
        taxLabel,
        taxBg,
        taxColor,
        taxStatus
    } = taxSelected.dataset;

    taxLabel = taxLabel || taxOriginalName;
    taxBg = taxBg || '#ffffff';
    taxColor = taxColor || '#ffffff';

    tb_show('&#9998; Taxonomy: &#91; '+ taxLabel +' &#93;', '/?TB_inline&inlineId=thickbox-tax-modal&width=800&height=500');

    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-taxonomy');

    jQuery(form.querySelector('.js-tax-bg')).wpColorPicker('color', taxBg);
    jQuery(form.querySelector('.js-tax-color')).wpColorPicker('color', taxColor);
    form.querySelector('.js-tax-name').value = taxLabel;

    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-btn-tax-save')
        ?.addEventListener('click', saveTaxAttributes);
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-btn-tax-reset')
        ?.addEventListener('click', resetTaxAttributes);
}

function saveTaxAttributes() {
    let taxSelected = YMC_CONTAINER.querySelector('#general .js-tax-insert .is-open');
    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-taxonomy');
    let taxBg = form.querySelector('.js-tax-bg').value;
    let taxColor = form.querySelector('.js-tax-color').value;
    let taxName = form.querySelector('.js-tax-name').value;
    let originalTaxName = taxSelected.dataset.taxOriginalName;
    let taxAttrs = [];

    taxSelected.classList.add('changed');

    taxSelected.dataset.taxBg = taxBg;
    taxSelected.dataset.taxColor = taxColor;
    taxSelected.dataset.taxLabel = taxName;
    taxSelected.dataset.taxStatus = 'changed';
    taxSelected.querySelector('.field-label').innerHTML = taxName || originalTaxName;

    YMC_CONTAINER.querySelectorAll('#general .js-tax-insert .taxonomies-list__item').forEach((el) => {
        taxAttrs.push({
            'name' : el.dataset.taxName,
            'label' : el.dataset.taxLabel,
            'background' : el.dataset.taxBg,
            'color' : el.dataset.taxColor,
            'status' : el.dataset.taxStatus
        });
    });

    requestTaxAttributeUpdate(taxAttrs);
}

function resetTaxAttributes() {
    let taxSelected = YMC_CONTAINER.querySelector('#general .js-tax-insert .is-open');
    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-taxonomy');
    let taxAttrs = [];

    jQuery(form.querySelector('.js-tax-bg')).wpColorPicker('color', '#ffffff');
    jQuery(form.querySelector('.js-tax-color')).wpColorPicker('color', '#ffffff');
    form.querySelector('.js-tax-name').value = taxSelected.dataset.taxOriginalName;

    taxSelected.classList.remove('changed');
    taxSelected.dataset.taxBg = '';
    taxSelected.dataset.taxColor = '';
    taxSelected.dataset.taxLabel = '';
    taxSelected.dataset.taxStatus = '';
    taxSelected.querySelector('.field-label').innerHTML = taxSelected.dataset.taxOriginalName;

    YMC_CONTAINER.querySelectorAll('#general .js-tax-insert .taxonomies-list__item').forEach((el) => {
        taxAttrs.push({
            'name'       : el.dataset.taxName,
            'label'      : el.dataset.taxLabel,
            'background' : el.dataset.taxBg,
            'color'      : el.dataset.taxColor,
            'status'     : el.dataset.taxStatus
        });
    });

    requestTaxAttributeUpdate(taxAttrs);
}

async function requestTaxAttributeUpdate(attrs) {
    const selector = '#TB_window #TB_ajaxContent';
    const url = YMC_AJAX_URL;
    const params = new URLSearchParams({
        action: 'action_save_taxonomy_attrs',
        nonce_code : _ymc_fg_object.saveTaxAttrAjax_nonce,
        payload: JSON.stringify({
            "post_id": YMC_POST_ID,
            "tax_attrs": attrs
        })
    });

    try {
        setPreloader(selector);
        const response = await fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params.toString()
        });
        if (response.ok) {
            const data = await response.json();
            notification(data.message, '#TB_window #TB_ajaxContent', 'success', 2000);
        } else {
            console.error("Error HTTP: " + response.status);
        }
    } catch(e) {
        console.error('Error occurred:', e)
    } finally {
        removePreloader(selector);
    }
}


