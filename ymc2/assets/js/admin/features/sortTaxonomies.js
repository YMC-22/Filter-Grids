import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";

export function setupSortTaxonomies() {
    const taxListElement = YMC_CONTAINER.querySelector('.js-tax-sortable');
    jQuery(taxListElement).sortable({
        //axis: 'X',
        cursor: "move",
        handle: '.js-tax-handle',
        opacity: 1,
        delay: 150,
        scroll: false,
        //containment: "parent",
        start: function( event, ui ) {
            ui.item[0].classList.add('is-item-dragging');
            taxListElement.classList.add('dragging');
        },
        stop: function( event, ui ) {
            ui.item[0].classList.remove('is-item-dragging');
            taxListElement.classList.remove('dragging');
            let listTax = [];
            taxListElement.querySelectorAll('.taxonomies-list__item').forEach(el => {
                listTax.push(el.dataset.taxName);
            });
            updateTaxonomySortOrder(listTax);
        }
    });
}

async function updateTaxonomySortOrder(tax) {
    const url = YMC_AJAX_URL;
    const params = new URLSearchParams({
        action: 'action_taxonomies_sort',
        nonce_code : _ymc_fg_object.sortTaxAjax_nonce,
        payload: JSON.stringify({
            "tax_sort": tax,
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