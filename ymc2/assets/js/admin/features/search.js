import { YMC_SEARCH } from "../core/constants.js";

export function setupSearchControls() {
    YMC_SEARCH.querySelector('.js-autocomplete-enabled').addEventListener('change', autocompleteSettings);
}


function autocompleteSettings() {
    const settingsGroup = YMC_SEARCH.querySelector('.js-autocomplete-settings');
    if (!settingsGroup) return;
    if (this.checked) {
        settingsGroup.classList.remove('is-hidden');
    } else {
        settingsGroup.classList.add('is-hidden');
    }
}




