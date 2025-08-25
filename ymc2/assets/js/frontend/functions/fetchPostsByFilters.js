import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";
import { initMasonry } from "./initMasonry.js";
import {ymcHooks} from "../utils/hooks.js";

// Fetch posts
export async function fetchPostsByFilters() {
    const filters = document.querySelectorAll('.js-ymc-container');
    filters.forEach(filter => {
        ymcHooks.doAction('ymc/grid/cancel_fetch', filter);
        if (filter.dataset.loadingEnabled === 'true') {
            fetchFilteredPosts(filter);
            // Init Masonry
            initMasonry(filter);
        }
    });
}



