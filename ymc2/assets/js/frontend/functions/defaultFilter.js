import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";
import { updateDataParams, removeDataParams } from "../utils/updateDataParams.js";
import { clearSearchForm } from "../utils/clearSearchForm.js";
import { globalSelectedTerms } from "./filterState.js";
import { getEffectiveTermIds } from '../utils/getEffectiveTermIds.js';
import { resolveMainContainer } from '../utils/resolveMainContainer.js';


export function initDefaultFilter() {
    const filters = document.querySelectorAll('.js-ymc-container [data-filter-type="default"]');
    filters.forEach(filter => {
        const buttons = filter.querySelectorAll('.filter-button:not(.js-filter-button-all)');
        const buttonsByAll = filter.querySelectorAll('.filter-button.js-filter-button-all');
        buttons.forEach(button => {
            button.addEventListener('click', setupDefaultFilterEvents);
        });
        buttonsByAll.forEach(button => {
            button.addEventListener('click', addResetAllButtonListeners);
        });
    });
}

// Default filter
async function setupDefaultFilterEvents() {
    const button = this;
    const container = resolveMainContainer(button);
    const filter = button.closest('.filter');
    const buttons = filter.querySelectorAll('.filter-button:not(.js-filter-button-all)');
    const isMultiple = filter.dataset.selectionMode === 'multiple';

    if (isMultiple) {
        button.classList.toggle('is-active');
    } else {
        filter.querySelectorAll('button.is-active').forEach(btn => btn.classList.remove('is-active'));
        button.classList.add('is-active');
    }

    buttons.forEach(btn => {
        const termId = Number(btn.dataset.termid);
        if (btn.classList.contains('is-active')) {
            globalSelectedTerms.add(termId);
        } else {
            globalSelectedTerms.delete(termId);
        }
    });

    const activeButtons = filter.querySelectorAll('.filter-button.is-active:not(.js-filter-button-all)');
    if (isMultiple && activeButtons.length === 0) {
        const allButton = filter.querySelector('.js-filter-button-all');
        if (allButton) {
            const defaultTermIds = JSON.parse(allButton.dataset.allTerms).map(Number);
            buttons.forEach(btn => {
                const termId = Number(btn.dataset.termid);
                if (defaultTermIds.includes(termId)) {
                    globalSelectedTerms.add(termId);
                    btn.classList.remove('is-active');
                }
            });
        }
    }

    const allButton = filter.querySelector('.js-filter-button-all');
    if (allButton) {
        const hasActiveTerms = filter.querySelectorAll('.filter-button.is-active:not(.js-filter-button-all)').length > 0;
        if (hasActiveTerms) {
            allButton.classList.remove('is-active');
        } else {
            allButton.classList.add('is-active');
        }
    }

    filter.classList.toggle('js-has-selection', filter.querySelectorAll('button.is-active:not(.js-filter-button-all)').length > 0);

    clearSearchForm(container);
    removeDataParams(container, 'search');

    updateDataParams(container, {
        terms: getEffectiveTermIds(container),
        paged: 1
    });

    await fetchFilteredPosts(container);
}


// Reset all filters
async function addResetAllButtonListeners() {
    const button = this;
    const container = resolveMainContainer(button);
    const buttons = button.closest('.filter-buttons');

    const allTerms = JSON.parse(button.dataset.allTerms).map(Number); // термы этой кнопки (фильтра)

    const currentButtons = buttons.querySelectorAll('.filter-button:not(.js-filter-button-all)');
    currentButtons.forEach(btn => {
        const termId = Number(btn.dataset.termid);
        globalSelectedTerms.delete(termId);
    });

    allTerms.forEach(id => globalSelectedTerms.add(id));

    buttons.querySelectorAll('button.is-active').forEach(btn => btn.classList.remove('is-active'));
    button.closest('.filter').classList.remove('js-has-selection');

    button.classList.add('is-active');


    clearSearchForm(container);
    removeDataParams(container, 'search');

    updateDataParams(container, {
        terms: getEffectiveTermIds(container),
        paged: 1
    });

    await fetchFilteredPosts(container);
}


