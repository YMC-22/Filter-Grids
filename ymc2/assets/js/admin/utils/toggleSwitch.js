import { YMC_CONTAINER } from "../core/constants.js";

export function toggleSwitch() {
    // Appearance -> Filter
    actionToggleSwitch('#appearance .js-toggle-switch-filter-state','#appearance .js-is-disabled-filter-options', 'hide');
    // Appearance -> Pagination
    actionToggleSwitch('#appearance .js-toggle-switch-pagination','#appearance .js-is-disabled-pagination', 'hide');
    // Popup -> Filter
    actionToggleSwitch('#appearance .js-toggle-switch-popup','#appearance .js-is-enable-popup', 'show');
    // Search -> Search
    actionToggleSwitch('#search .js-toggle-switch-search-posts','#search .js-is-disabled-search-options', 'show');
    // Advanced -> Advanced Query
    actionToggleSwitch('#advanced .js-toggle-switch-advanced-query', '.js-is-enable-advanced-query', 'show');
    // Advanced -> Sort Posts
    actionToggleSwitch('#advanced .js-toggle-switch-sort-posts', '.js-is-enable-sort-posts', 'show');

}

/**
 * Toggle Switch
 * @param switchSelector
 * @param target
 * @param state
 */
function actionToggleSwitch(switchSelector, target, state = 'show') {
    document.querySelectorAll(`${switchSelector} .js-toggle-switch input[type="checkbox"]`).forEach((toggleSwitch) => {
        const container = toggleSwitch.closest(switchSelector);
        const element = container.querySelector(target);

        if (!element) return;

        const toggleVisibility = () => {
            const shouldShow = (toggleSwitch.checked && state === 'show') || (!toggleSwitch.checked && state === 'hide');
            element.style.display = shouldShow ? 'block' : 'none';
        };

        toggleSwitch.addEventListener('change', toggleVisibility);
        toggleVisibility();
    });
}

