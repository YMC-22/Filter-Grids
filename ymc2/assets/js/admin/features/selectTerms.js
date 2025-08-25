import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";
export function selectAllTerms() {
    YMC_CONTAINER.querySelectorAll('#general .js-term-insert').forEach((el) => {
        el.addEventListener('click', function (e) {
            if(e.target.closest('.tax-name')) {
                let checkboxes = e.target.closest('.cel').querySelectorAll('.term-item .checkbox-control');
                if(e.target.checked) {
                    checkboxes.forEach((checkbox)=> {
                        checkbox.checked = true;
                    });
                } else {
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = false;
                    });
                }
            }
        });
    });
}
