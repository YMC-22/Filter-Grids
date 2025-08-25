import {YMC_TOOLS, YMC_AJAX_URL, YMC_POST_ID, YMC_BODY} from "../core/constants.js";
import { setPreloader, removePreloader } from "../utils/preloaderUtils.js";
import { notification } from "../utils/notification.js";

export function exportSettings() {
    YMC_TOOLS.querySelector('.export-wrapper .js-button-export-settings')
        ?.addEventListener('click', async (e) => {

        const fullYear = new Date().getFullYear();
        const day      = new Date().getDate();
        const month    = new Date().getMonth();
        const hour     = new Date().getHours();
        const minutes  = new Date().getMinutes();
        const seconds  = new Date().getSeconds();

        const formData = new FormData();
        formData.append('action', 'action_export_settings');
        formData.append('nonce_code', _ymc_fg_object.exportSettingsAjax_nonce);
        formData.append('post_id', YMC_POST_ID);

        try {
            setPreloader('.ymc-main #tools');
            const response = await fetch(YMC_AJAX_URL, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                notification(`HTTP error! Status: ${response.status}`, '#tools .export-wrapper .notify-wrapper', 'error', 2000);
                return;
            }

            const blob = await response.blob();
            const link = document.createElement('a');
            const filename = `ymc-fg-export-${day}-${month + 1}-${fullYear}-${hour}:${minutes}:${seconds}.json`;

            const url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
            notification('Settings exported', '#tools .export-wrapper .notify-wrapper', 'success', 2000);

        } catch (e) {
            console.error('Error occurred:', e);
            notification('Error occurred', '#tools .export-wrapper .notify-wrapper', 'error', 2000);
        } finally {
            removePreloader();
        }
    });
}

export function importSettings(file) {
    YMC_TOOLS.querySelector('.import-wrapper .js-file-import-settings')
        ?.addEventListener('change', uploadFile);
}

function uploadFile() {

    const input = this;
    const file = input.files[0];

    if (!file) return;

    if(file.type === "application/json" && file.name.indexOf('ymc-fg-export-') === 0) {
        const reader = new FileReader();
        reader.readAsText(file);
        reader.onload = async function() {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'action_import_settings');
            formData.append('post_id', YMC_POST_ID);
            formData.append('params', reader.result);
            formData.append('nonce_code', _ymc_fg_object.importSettingsAjax_nonce);

            try {
                setPreloader('.ymc-main #tools');
                const response = await fetch(YMC_AJAX_URL, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                });

                if (!response.ok) {
                    notification(`HTTP error! Status: ${response.status}`, '#tools .import-wrapper .notify-wrapper', 'error', 2000);
                    return;
                }

                const jsonData = await response.json();

                notification(jsonData.data.message, '#tools .import-wrapper .notify-wrapper', 'success', 2000);
                input.value = '';
                location.reload();

            } catch (e) {
                console.error('Error occurred:', e);
                notification('Error occurred', '#tools .import-wrapper .notify-wrapper', 'error', 2000);
            } finally {
                removePreloader();
            }
        }
    } else {
        notification('Invalid file format', '#tools .import-wrapper .notify-wrapper', 'error', 2000);
    }
}


