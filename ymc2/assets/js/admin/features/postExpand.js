import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";

/**
 * Expand Collapse Posts
 */
export function setupExpandCollapse() {
    YMC_CONTAINER.querySelector('#general .button-expand-wrapper .js-button-expand').addEventListener('click', function () {
        this.classList.toggle('is-expanded');
        YMC_CONTAINER.querySelectorAll('#general .select-posts-display__inner .cel .list-posts').forEach((el) => {
            if(el.classList.contains('is-expanded')) {
                el.classList.remove('is-expanded');
            } else {
                el.classList.add('is-expanded');
            }
        })
    })
}


