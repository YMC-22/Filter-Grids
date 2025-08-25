
export function clearSearchForm(container) {
    const inputSearch = container.querySelector('.js-search-field');
    if (inputSearch) {
        const resultsContainer = container.querySelector('.js-autocomplete-results');
        container.querySelector('.js-clear-button')?.classList.remove('is-visible');
        resultsContainer.classList.remove('is-visible');
        resultsContainer.innerHTML = '';
        inputSearch.dataset.postTitle = '';
        inputSearch.value = '';
    }

}