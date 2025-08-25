import {YMC_ADVANCED, YMC_TYPOGRAPHY} from "../core/constants.js";

export function setupAdvancedControls() {
    YMC_ADVANCED.querySelector('.js-advanced-query-type').addEventListener('change', toggleCallbackFunctionse);
}

function toggleCallbackFunctionse() {
    const value = this.value;
    const callbackFunction = YMC_ADVANCED.querySelector('.js-callback-function');
    const advancedQuery    = YMC_ADVANCED.querySelector('.js-advanced-query');
    const isCallback = value === 'callback';
    const isAdvanced = value === 'advanced';

    if (callbackFunction) {
        callbackFunction.style.display = isCallback ? 'block' : 'none';
    }
    if (advancedQuery) {
        advancedQuery.style.display = isAdvanced ? 'block' : 'none';
    }
}

