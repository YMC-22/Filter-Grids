import {updateDataParams} from "../utils/updateDataParams.js";
import {fetchFilteredPosts} from "../utils/fetchFilteredPosts.js";

export function setupNumericPagination(container) {
    container.querySelectorAll('.js-pagination-numeric li a').forEach(page => {
        page.addEventListener('click', addButtonListeners(container));
    });
}



function addButtonListeners(container) {
    return function (e) {
        e.preventDefault();
        let paged = 1;

        if (this.getAttribute('href')?.startsWith('#page')) {
            paged = parseInt(this.getAttribute('href').replace('#page=', ''));
        } else {
            paged = parseInt(this.innerText);
        }

        updateDataParams(container, { paged: paged });
        fetchFilteredPosts(container);
    }

}
