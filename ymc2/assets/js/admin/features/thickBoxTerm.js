import { YMC_BODY, YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";
import { setPreloader, removePreloader } from "../utils/preloaderUtils.js";
import { notification } from "../utils/notification.js";

/**
 * ThickBox Term
 */
export function thickBoxTerm() {
    YMC_CONTAINER.querySelectorAll('#general .js-term-insert .js-term-settings').forEach( term => {
        term.addEventListener('click', openThickBoxTerm);
    });
}

function openThickBoxTerm() {
    YMC_CONTAINER.querySelectorAll('#general .js-term-insert .term-item').forEach(
        item => item.classList.remove('is-open'));

    const termSelected = this.closest('.term-item');
    termSelected.classList.add('is-open');

    let {
        termName,
        termNameOrig,
        termBackground,
        termColor,
        termClass,
        termDefault,
        termVisible,
        termIconAlignment,
        termIconClass,
        termIconColor,
        termIconUrl
    } = termSelected.dataset;

    termBackground = termBackground || '#095c81';
    termColor      = termColor || '#ffffff';
    termClass      = termClass || '';
    termDefault    = termDefault || 'false';
    termVisible    = termVisible || 'true';
    termName       = termName || termNameOrig;
    termIconAlignment = termIconAlignment || 'left';
    termIconClass  = termIconClass || '';
    termIconColor  = termIconColor || '#ffffff';
    termIconUrl    = termIconUrl || '';

    tb_show('&#9998; Term: &#91; '+ termName +' &#93;', '/?TB_inline&inlineId=thickbox-term-modal&width=800&height=500');

    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term');

    jQuery(form.querySelector('.js-term-bg')).wpColorPicker('color', termBackground);
    jQuery(form.querySelector('.js-term-color')).wpColorPicker('color', termColor);
    jQuery(form.querySelector('.js-icon-color')).wpColorPicker('color', termIconColor);

    form.querySelector('.js-term-class').value = termClass;
    form.querySelector('.js-term-name').value = termName;
    form.querySelector('.js-term-default').checked = termDefault === 'true';
    form.querySelector('.js-term-visible').checked = termVisible === 'true';
    form.querySelector('.js-icon-class').value = termIconClass;

    form.querySelectorAll('.js-icon-alignment').forEach(item => {
        item.checked = item.value === termIconAlignment;
    });

    const domain = form.querySelector('.js-domain');
    const iconPreview = form.querySelector('.js-icon-preview');
    iconPreview.innerHTML = termIconClass ? `<i class="${termIconClass}"></i>` : '';
    iconPreview.innerHTML = termIconUrl ? `<img src="${domain.getAttribute('value')}${termIconUrl}" width="20">` : '';

    const btnIconRemove = form.querySelector('.js-btn-icon-remove');
    btnIconRemove.classList.toggle('is-visible', !!termIconClass);
    btnIconRemove.classList.toggle('is-visible', !!termIconUrl);

    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-btn-term-save')
        ?.addEventListener('click', saveTermAttributes);
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-btn-term-reset')
        ?.addEventListener('click', resetTermAttributes);
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-enable-custom-icon')
        ?.addEventListener('change', enableCustomIcon);
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-upload-file')
        ?.addEventListener('change', uploadCustomIcon);

    btnIconRemove?.addEventListener('click', removeIcon);

    const isEnableCustomIcon = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .js-enable-custom-icon');
    isEnableCustomIcon.checked = !!termIconUrl;
    isEnableCustomIcon.dispatchEvent(new Event('change', { bubbles: true }));

    initTabs();
    setActiveTab();
    searchIcons();
    selectIcon();
}

async function saveTermAttributes() {
    let termSelected = YMC_CONTAINER.querySelector('#general .js-term-insert .is-open');
    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term');
    termSelected.classList.add('changed');

    // Get Attributes
    let termBg      = form.querySelector('.js-term-bg').value;
    let termColor   = form.querySelector('.js-term-color').value;
    let termClass   = form.querySelector('.js-term-class').value;
    let termName    = form.querySelector('.js-term-name').value;
    let termDefault = form.querySelector('.js-term-default').checked;
    let termVisible = form.querySelector('.js-term-visible').checked;
    let originalTermName = termSelected.dataset.termNameOrig;
    let iconAlignment = 'left';
    form.querySelectorAll('.js-icon-alignment').forEach(item => {
        if (item.checked) iconAlignment = item.value;
    });
    let iconClass   = form.querySelector('.js-icon-class').value;
    let iconUrl     = form.querySelector('.js-icon-url').value;
    let iconColor   = form.querySelector('.js-icon-color').value;
    let ternChecked = termSelected.querySelector('.checkbox-control').checked;
    let termAttrs = [];

    termSelected.querySelectorAll('.field-label').forEach(label => {
        label.childNodes.forEach(node => {
            if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                node.remove();
                label.insertAdjacentHTML("afterbegin", termName || originalTermName);
            }
        });
    });

    termSelected.dataset.termBackground = termBg;
    termSelected.dataset.termColor      = termColor;
    termSelected.dataset.termClass      = termClass;
    termSelected.dataset.termName       = termName;
    termSelected.dataset.termDefault    = termDefault;
    termSelected.dataset.termVisible    = termVisible;
    termSelected.dataset.termIconAlignment = iconAlignment;
    termSelected.dataset.termIconClass  = iconClass;
    termSelected.dataset.termIconUrl    = iconUrl;
    termSelected.dataset.termIconColor  = iconColor;
    termSelected.dataset.termChecked    = ternChecked;
    termSelected.dataset.termStatus     = 'changed';

    YMC_CONTAINER.querySelectorAll('#general .js-term-insert .term-item').forEach(term => {
        termAttrs.push({
            'term_id' : term.dataset.termId,
            'term_slug' : term.dataset.termSlug,
            'term_name_orig' : term.dataset.termNameOrig,
            'term_background' : term.dataset.termBackground,
            'term_color' : term.dataset.termColor,
            'term_class' : term.dataset.termClass,
            'term_default' : term.dataset.termDefault,
            'term_visible' : term.dataset.termVisible,
            'term_name' : term.dataset.termName,
            'term_checked' : term.dataset.termChecked,
            'term_status' : term.dataset.termStatus,
            'icon_class' : term.dataset.termIconClass,
            'icon_alignment' : term.dataset.termIconAlignment,
            'icon_color' : term.dataset.termIconColor,
            'icon_url' : term.dataset.termIconUrl
        });
    });

    await requestTermAttributeUpdate(termAttrs);
}

async function resetTermAttributes() {
    let termSelected = YMC_CONTAINER.querySelector('#general .js-term-insert .is-open');
    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term');
    let termAttrs = [];
    let { termNameOrig } = termSelected.dataset;

    jQuery(form.querySelector('.js-term-bg')).wpColorPicker('color', '#095c81');
    jQuery(form.querySelector('.js-term-color')).wpColorPicker('color', '#ffffff');
    jQuery(form.querySelector('.js-icon-color')).wpColorPicker('color', '#ffffff');
    form.querySelector('.js-term-class').value = '';
    form.querySelector('.js-term-name').value = termNameOrig;
    form.querySelector('.js-term-default').checked = false;
    form.querySelector('.js-term-visible').checked = true;
    form.querySelector('.js-icon-alignment[value="left"]').checked = true;
    form.querySelector('.js-icon-class').value = '';
    form.querySelector('.js-icon-url').value = '';
    form.querySelector('.js-icon-preview').innerHTML = '';
    form.querySelector('.js-btn-icon-remove').classList.remove('is-visible');
    form.querySelector('.upload-term-icon .label-file span').innerHTML = 'Choose a icon';

    const label = termSelected.querySelector('.field-label');
    if (label) {
        const textNode = Array.from(label.childNodes).find(node =>
            node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== ''
        );
        if (textNode) {
            textNode.textContent = `${termNameOrig} `;
        }
    }

    termSelected.classList.remove('changed');

    termSelected.dataset.termBackground = '';
    termSelected.dataset.termColor = '';
    termSelected.dataset.termClass = '';
    termSelected.dataset.termName = termNameOrig;
    termSelected.dataset.termDefault = 'false';
    termSelected.dataset.termVisible = 'true';
    termSelected.dataset.termIconAlignment = '';
    termSelected.dataset.termIconClass = '';
    termSelected.dataset.termIconUrl = '';
    termSelected.dataset.termIconColor = '';
    termSelected.dataset.termChecked = termSelected.querySelector('.checkbox-control').checked;
    termSelected.dataset.termStatus = '';

    YMC_CONTAINER.querySelectorAll('#general .js-term-insert .term-item').forEach(el => {
        termAttrs.push({
            'term_id'         : el.dataset.termId,
            'term_slug'       : el.dataset.termSlug,
            'term_name_orig'  : el.dataset.termNameOrig,
            'term_background' : el.dataset.termBackground,
            'term_color'      : el.dataset.termColor,
            'term_class'      : el.dataset.termClass,
            'term_default'    : el.dataset.termDefault,
            'term_visible'    : el.dataset.termVisible,
            'term_name'       : el.dataset.termName,
            'term_checked'    : el.dataset.termChecked,
            'term_status'     : el.dataset.termStatus,
            'icon_class'      : el.dataset.termIconClass,
            'icon_alignment'  : el.dataset.termIconAlignment,
            'icon_color'      : el.dataset.termIconColor,
            'icon_url'        : el.dataset.termIconUrl
        });
    });

    await requestTermAttributeUpdate(termAttrs);
}

async function requestTermAttributeUpdate(attrs) {
    const selector = '#TB_window #TB_ajaxContent';
    const url = YMC_AJAX_URL;
    const params = new URLSearchParams({
        action: 'action_save_term_attrs',
        nonce_code: _ymc_fg_object.saveTermAttrAjax_nonce,
        payload: JSON.stringify({
            post_id: YMC_POST_ID,
            term_attrs: attrs
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

function initTabs() {
    const buttons = YMC_BODY.querySelectorAll('#TB_window #TB_ajaxContent .tab-button');
    const contents = YMC_BODY.querySelectorAll('#TB_window #TB_ajaxContent .tab-content');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => btn.classList.remove('is-active'));
            contents.forEach(content => content.classList.remove('is-active'));
            button.classList.add('is-active');
            YMC_BODY.querySelector('#TB_window #TB_ajaxContent #'+button.dataset.tab).classList.add('is-active');
        });
    });
}

function setActiveTab() {
    const root = YMC_BODY.querySelector('#TB_window #TB_ajaxContent');
    const buttons = root.querySelectorAll('.tab-button');
    const contents = root.querySelectorAll('.tab-content');
    buttons.forEach(button => button.classList.remove('is-active'));
    contents.forEach(content => content.classList.remove('is-active'));
    root.querySelector('.tabs [data-tab="tabTerm"]')?.classList.add('is-active');
    root.querySelector('.tabs #tabTerm')?.classList.add('is-active');
}

function searchIcons() {
    const searchInput = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-field-search-icon');
    const icons = YMC_BODY.querySelectorAll('#TB_window #TB_ajaxContent .form-term .js-icon-item i');
    searchInput.addEventListener('input', () => {
        const keyword = searchInput.value.toLowerCase();
        icons.forEach(icon => {
            let iconName = icon.getAttribute('class').replace(/[\s.-]/g, ' ').toLowerCase();
            icon.style.display = iconName.includes(keyword) ? 'inline-block' : 'none';
        });
    });
}

function selectIcon() {
    const icons = YMC_BODY.querySelectorAll('#TB_window #TB_ajaxContent .form-term .js-icon-item i');
    const btnRemove = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-btn-icon-remove');
    icons.forEach(icon => {
        icon.addEventListener('click', () => {
            YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-btn-icon-remove').classList.add('is-visible');
            YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-icon-class').value = icon.getAttribute('class');
            YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-icon-preview').innerHTML = icon.outerHTML;
            YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-icon-url').value = '';
            YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-upload-file').value = '';
            btnRemove.addEventListener('click', removeIcon);
        });
    });
}

function removeIcon() {
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-btn-icon-remove').classList.remove('is-visible');
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-icon-class').value = '';
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-icon-url').value = '';
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-icon-preview').innerHTML = '';
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .js-upload-file').value = '';
    YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term .upload-term-icon .label-file span').innerHTML = 'Choose a icon';
}

function enableCustomIcon(e) {
    const popup = YMC_BODY.querySelector('#TB_window #TB_ajaxContent');
    const uploadBlock = popup.querySelector('.js-upload-icon-wrap');
    const iconSelector = popup.querySelector('.js-term-icon-selector');

    const showUpload = this.checked;
    uploadBlock.classList.toggle('is-hidden', !showUpload);
    iconSelector.classList.toggle('is-hidden', showUpload);
}

async function uploadCustomIcon(e) {
    const url = YMC_AJAX_URL;
    const file = e.target.files[0];
    const selector = '#TB_window #TB_ajaxContent';
    const form = YMC_BODY.querySelector('#TB_window #TB_ajaxContent .form-term');
    const preview = form.querySelector('.js-icon-preview');
    const btnRemove = form.querySelector('.js-btn-icon-remove');
    const iconClass = form.querySelector('.js-icon-class');
    const iconUrl = form.querySelector('.js-icon-url');
    const fileName = e.target.value.split( '\\' ).pop();
    const domain = form.querySelector('.js-domain');

    if (!file) return;

    const formData = new FormData();
    formData.append('icon', file);
    formData.append('action', 'action_upload_term_icon');
    formData.append('post_id', YMC_POST_ID);
    formData.append('nonce_code', _ymc_fg_object.uploadTermIconAjax_nonce);

    try {
        setPreloader(selector);
        const response = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        });
        if (response.ok) {
            const jsonData = await response.json();

            if (jsonData.success && jsonData.data.url) {
                preview.innerHTML = `<img src="${domain.getAttribute('value')}${jsonData.data.url}" width="20">`;
                iconClass.value = '';
                iconUrl.value = jsonData.data.url;
                btnRemove.classList.add('is-visible');
                btnRemove.addEventListener('click', removeIcon);
                this.nextElementSibling.querySelector('span').innerHTML = fileName;
                notification(jsonData.data.message, '#TB_window #TB_ajaxContent', 'success', 2000);
            } else {
                notification(jsonData.data.message, '#TB_window #TB_ajaxContent', 'error', 2000);
            }

        } else {
            console.error("Error HTTP: " + response.status);
        }
    } catch(e) {
        console.error('Error occurred:', e)
    } finally {
        removePreloader(selector);
    }

}



