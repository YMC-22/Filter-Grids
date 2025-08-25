import { YMC_AJAX_URL } from "../core/constants.js";
import { getDataParams, updateDataParams, removeDataParams} from "../utils/updateDataParams.js";
import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";


export function initPostSearch() {
    const searchInput = document.querySelectorAll('.js-ymc-container .js-search-field');
    const searchButton = document.querySelectorAll('.js-ymc-container .js-search-button');
    const clearButton = document.querySelectorAll('.js-ymc-container .js-clear-button');

    if (searchInput) {
        searchInput.forEach(filed => {
            filed.addEventListener('keydown', onSearchInputKeyDown);
            filed.addEventListener('input', debounce(onSearchInputChange, 400));
        });
    }
    if (searchButton) {
        searchButton.forEach(button => {
            button.addEventListener('click', onSearchButtonClick);
        });
    }
    if(clearButton) {
        clearButton.forEach(clearButton => {
            clearButton.addEventListener('click', onClearButtonClick);
        });
    }

    document.addEventListener('click', autocompleteContainerHidden);
}

function debounce(func, delay) {
    let timeout;
    return function (...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), delay);
    };
}

async function onSearchInputChange(e) {
    const rawValue = this.value;
    let container = this.closest('.js-ymc-container');
    const containerExtra = this.closest('.ymc-extra-search');
    const resultsContainer = container.querySelector('.js-autocomplete-results');
    const clearButton = container.querySelector('.js-clear-button');
    const gridId = this.dataset.gridId;
    const stateAutocomplete = this.dataset.stateAutocomplete;
    const searchMode = this.dataset.searchMode;
    let termsId = [];

    clearButton.classList.add('is-visible');

    if (stateAutocomplete === 'no') {
        return;
    }

    let keyword = rawValue.trim().replace(/[^\p{L}\p{N}\s\-]/gu, '');

    if (keyword.length < 3) {
        resultsContainer.classList.remove('is-visible');
        resultsContainer.innerHTML = '';
        clearButton.classList.remove('is-visible');
        return;
    }

    if(searchMode === 'filtered') {
        if (containerExtra) {
            const extraFilterId = containerExtra.dataset.extraFilterId;
            let dataParams = getDataParams(document.querySelector(`.ymc-filter-${extraFilterId}`));
            termsId = dataParams.terms;
        } else {
            let dataParams = getDataParams(container);
            termsId = dataParams.terms;
        }
    }

    const formData = new FormData();
    formData.append('action', 'get_autocomplete_suggestions');
    formData.append('nonce_code', _ymc_fg_object.getAutocompletePosts_nonce);
    formData.append('keyword', keyword);
    formData.append('grid_id', gridId);
    formData.append('terms', JSON.stringify(termsId));

    try {
        const response = await fetch(YMC_AJAX_URL, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            console.error(`HTTP error: ${response.status}`);
            return;
        }

        const jsonData = await response.json();
        if (!jsonData.success) {
            console.warn('No renderedHTML in response');
            return;
        }

        const { results } = jsonData.data;

        if(results.length === 0) {
            resultsContainer.classList.remove('is-visible');
            resultsContainer.innerHTML = '';
            return;
        }

        resultsContainer.innerHTML = results.map(item =>
            `<div class="clue-item js-clue-item" data-post-title="${item.title}" data-post-id="${item.id}">
                ${capitalizeFirstVisibleLetter(item.context)}
             </div>`
        ).join('');

        onClueItemClick(container);

        resultsContainer.classList.add('is-visible');

    } catch (e) {
        console.error('Error occurred:', e);
    }  finally {}

}

function onClueItemClick(container) {
    container.querySelectorAll('.js-autocomplete-results .js-clue-item').forEach(elem => {
        elem.addEventListener('click', async function (event) {
            const clueItem = event.target.closest('.js-clue-item');
            const containerExtra = clueItem.closest('.ymc-extra-search');
            const postTitle = clueItem.dataset.postTitle;
            if (!clueItem) return;

            let container = clueItem.closest('.js-ymc-container');
            const input = container.querySelector('.js-search-field');
            const resultsContainer = container.querySelector('.js-autocomplete-results');
            const clearButton = container.querySelector('.js-clear-button');

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = clueItem.innerHTML;
            const clueText = tempDiv.textContent.trim();

            input.value = clueText;
            input.dataset.postTitle = postTitle;

            resultsContainer.innerHTML = '';
            resultsContainer.classList.remove('is-visible');
            clearButton.classList.add('is-visible');

            if (containerExtra) {
                const extraFilterId = containerExtra.dataset.extraFilterId;
                container = document.querySelector(`.ymc-filter-${extraFilterId}`);
            }

            updateDataParams(container, {
                search: postTitle,
                paged: 1
            });

            await fetchFilteredPosts(container);

        });
    })

}

async function onSearchButtonClick() {
    let container = this.closest('.js-ymc-container');
    const containerExtra = this.closest('.ymc-extra-search');
    const searchInput = this.closest('.search-form').querySelector('.js-search-field');
    const resultsContainer = container.querySelector('.js-autocomplete-results');

    let rawValue = searchInput.dataset.postTitle;
    if (!rawValue) {
        rawValue = searchInput.value;
    }

    let keyword = rawValue.trim().replace(/[^\p{L}\p{N}\s\-]/gu, '');

    if (keyword.length < 3) {
        console.log('Minimum keyword length: 2 characters');
        return;
    }

    resultsContainer.classList.remove('is-visible');
    resultsContainer.innerHTML = '';
    searchInput.dataset.postTitle = '';

    if (containerExtra) {
        const extraFilterId = containerExtra.dataset.extraFilterId;
        container = document.querySelector(`.ymc-filter-${extraFilterId}`);
    }

    updateDataParams(container, { search: keyword });
    await fetchFilteredPosts(container);
}

async function onClearButtonClick() {
    let container = this.closest('.js-ymc-container');
    const containerExtra = this.closest('.ymc-extra-search');
    const searchInput = this.closest('.search-form').querySelector('.js-search-field');
    const resultsContainer = container.querySelector('.js-autocomplete-results');

    this.classList.remove('is-visible');
    searchInput.value = '';
    resultsContainer.classList.remove('is-visible');
    resultsContainer.innerHTML = '';
    searchInput.dataset.postTitle = '';

    if (containerExtra) {
        const extraFilterId = containerExtra.dataset.extraFilterId;
        container = document.querySelector(`.ymc-filter-${extraFilterId}`);
    }

    removeDataParams(container, 'search');
    await fetchFilteredPosts(container);
}

function capitalizeFirstVisibleLetter(html) {
    const temp = document.createElement('div');
    temp.innerHTML = html;
    function capitalizeTextNode(node) {
        if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
            node.textContent = node.textContent.replace(/^(\s*)(\S)/, (match, spaces, firstChar) => {
                return spaces + firstChar.toUpperCase();
            });
            return true;
        }
        for (let child of node.childNodes) {
            if (capitalizeTextNode(child)) return true;
        }
        return false;
    }
    capitalizeTextNode(temp);

    return temp.innerHTML;
}

function autocompleteContainerHidden(event) {
    const containers = document.querySelectorAll('.filter-posts-search');
    containers.forEach(container => {
        const results = container.querySelector('.js-autocomplete-results');
        if (!container.contains(event.target)) {
            results.classList.remove('is-visible');
            results.innerHTML = '';
        }
    });
}

async function onSearchInputKeyDown(e) {
    const input = e.target;
    let container = input.closest('.js-ymc-container');
    const containerExtra = input.closest('.ymc-extra-search');
    const resultsContainer = container.querySelector('.js-autocomplete-results');
    const items = resultsContainer.querySelectorAll('.js-clue-item');
    const stateAutocomplete = input.dataset.stateAutocomplete;

    const isAutocompleteEnabled = stateAutocomplete !== 'no';

    let activeIndex = -1;

    if (isAutocompleteEnabled && items.length && resultsContainer.classList.contains('is-visible')) {
        items.forEach((item, index) => {
            if (item.classList.contains('is-active')) {
                activeIndex = index;
            }
        });

        // Arrow Down
        if (e.key === 'ArrowDown') {
            e.preventDefault();

            if (activeIndex < items.length - 1) {
                if (activeIndex >= 0) items[activeIndex].classList.remove('is-active');
                const newActive = items[activeIndex + 1];
                newActive.classList.add('is-active');
                updateInputFromClue(input, newActive);
            } else {
                items[activeIndex].classList.remove('is-active');
                const newActive = items[0];
                newActive.classList.add('is-active');
                updateInputFromClue(input, newActive);
            }
        }

        // Arrow Up
        if (e.key === 'ArrowUp') {
            e.preventDefault();

            if (activeIndex > 0) {
                items[activeIndex].classList.remove('is-active');
                const newActive = items[activeIndex - 1];
                newActive.classList.add('is-active');
                updateInputFromClue(input, newActive);
            } else if (activeIndex === 0) {
                items[activeIndex].classList.remove('is-active');
                const newActive = items[items.length - 1];
                newActive.classList.add('is-active');
                updateInputFromClue(input, newActive);
            } else {
                const newActive = items[items.length - 1];
                newActive.classList.add('is-active');
                updateInputFromClue(input, newActive);
            }
        }
    }

    // Enter
    if (e.key === 'Enter') {
        e.preventDefault();

        if (isAutocompleteEnabled && resultsContainer.classList.contains('is-visible')) {
            const activeItem = resultsContainer.querySelector('.js-clue-item.is-active');
            if (activeItem) {
                const postTitle = activeItem.dataset.postTitle;
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = activeItem.innerHTML;
                const clueText = tempDiv.textContent.trim();

                input.value = clueText;
                input.dataset.postTitle = postTitle;

                resultsContainer.innerHTML = '';
                resultsContainer.classList.remove('is-visible');

                const clearButton = container.querySelector('.js-clear-button');
                clearButton.classList.add('is-visible');

                if (containerExtra) {
                    const extraFilterId = containerExtra.dataset.extraFilterId;
                    container = document.querySelector(`.ymc-filter-${extraFilterId}`);
                }

                updateDataParams(container, {
                    search: postTitle,
                    paged: 1
                });

                await fetchFilteredPosts(container);
                return;
            }
        }

        // If autocomplete is disabled or there is no active element, run a regular search
        const searchButton = container.querySelector('.js-search-button');
        if (searchButton) {
            searchButton.click();
        }
    }

    // Escape — close and clear the tips
    if (e.key === 'Escape') {
        resultsContainer.classList.remove('is-visible');
        resultsContainer.innerHTML = '';
        items.forEach(item => item.classList.remove('is-active'));
        input.focus();
    }

    function updateInputFromClue(input, clueItem) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = clueItem.innerHTML;
        const clueText = tempDiv.textContent.trim();
        const postTitle = clueItem.dataset.postTitle;

        input.value = clueText;
        input.dataset.postTitle = postTitle;
    }

}




