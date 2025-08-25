import { getDataParams, updateDataParams } from "../utils/updateDataParams.js";
import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";

export function setupLoadMorePagination(container) {
    const loadMoreButton = container.querySelector('.js-btn-load-more');
    loadMoreButton.addEventListener('click', addButtonListeners(container));
}

function addButtonListeners(container) {
    return function () {
        this.classList.add('button--loading');
        const dataParams = getDataParams(container);
        let paged = ++dataParams.paged;
        updateDataParams(container, { paged: paged });
        fetchFilteredPosts(container, 'append');
    }
}










