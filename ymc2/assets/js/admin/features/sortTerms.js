import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";

export function setupSortTerms() {
    jQuery('.js-term-insert .js-term-sortable').sortable({
        //axis: 'y',
        cursor: "move",
        opacity: 1,
        delay: 150,
        handle: '.js-term-handle',
        scroll: false,
        //containment: "parent",
        start: function( event, ui ) {
            ui.item[0].classList.add('is-item-dragging');
            ui.item[0].closest('.terms-inner').classList.add('dragging');
        },
        stop: function( event, ui ) {
            ui.item[0].classList.remove('is-item-dragging');
            ui.item[0].closest('.terms-inner').classList.remove('dragging');
            let listTerms = [];
            YMC_CONTAINER.querySelectorAll('.js-term-insert .js-term-sortable input[type="checkbox"]').forEach(el => {
                listTerms.push(el.value);
            });
            updateTermSortOrder(listTerms);
        }
    });
}

async function updateTermSortOrder(terms) {
    const url = YMC_AJAX_URL;
    const params = new URLSearchParams({
        action: 'action_terms_sort',
        nonce_code : _ymc_fg_object.sortTermAjax_nonce,
        payload: JSON.stringify({
            "terms_sort": terms,
            "post_id": YMC_POST_ID
        })
    });
    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: params.toString()
    }).
    then(function(response) {
        if(response.ok === true) {
            return response.json();
        } else {
            console.error('Error occurred:', response.status);
        }
    }).
    catch(error => console.error(error));

}
