import { initFilters } from "./core/init_filters.js";
import { fetchPostsByFilters } from "./functions/fetchPostsByFilters.js";
import { initPopupTriggers } from "./functions/initPopupTriggers.js";
import { initPostSearch } from "./functions/postSearch.js";
import { initPostSort } from "./functions/postSort.js";
import { initMasonry } from "./functions/initMasonry.js";

document.addEventListener('DOMContentLoaded', () => {
    initFilters();
    fetchPostsByFilters();
    initPopupTriggers();
    initPostSearch();
    initPostSort();
    initMasonry();


});


