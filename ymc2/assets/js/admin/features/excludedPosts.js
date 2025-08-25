import { YMC_CONTAINER } from "../core/constants.js";
export function setupExcludedPosts() {
    YMC_CONTAINER.querySelector('#general .excluded-posts-checkbox .js-excluded-checkbox').addEventListener('click', function (e) {
        let selectedPostsWrp = YMC_CONTAINER.querySelector('#general .selected-posts .js-post-sortable');
        if(this.checked) {
            selectedPostsWrp.classList.add('is-excluded');
        } else {
            selectedPostsWrp.classList.remove('is-excluded');
        }
    });
}