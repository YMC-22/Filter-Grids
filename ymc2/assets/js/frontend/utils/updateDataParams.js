/**
 * Updates JSON properties in data-params DOM element
 * @param {HTMLElement} element - DOM element containing data-params
 * @param {Object} updates - Keys and values to add or update
 */

export function updateDataParams(element, updates) {
    if (!element || !element.dataset.params) return;
    let params;
    try {
        params = JSON.parse(element.dataset.params);
    } catch (e) {
        console.error("Error parsing data-params:", e);
        return;
    }
    // Update params
    Object.assign(params, updates);
    // Save в data-params
    element.dataset.params = JSON.stringify(params);
}

/**
 * Returns JSON object from data-params
 * @param element
 * @returns {*}
 */
export function getDataParams(element) {
    if (!element || !element.dataset.params) return;
    let params;
    try {
        params = JSON.parse(element.dataset.params);
    } catch (e) {
        console.error("Error parsing data-params:", e);
        return;
    }
    return params;
}



/**
 * Removes specified keys from JSON data-params on an element
 * @param {HTMLElement} element - DOM element containing data-params
 * @param {string|string[]} keys - Key or array of keys to remove
 */
export function removeDataParams(element, keys) {
    if (!element || !element.dataset.params) return;
    let params;
    try {
        params = JSON.parse(element.dataset.params);
    } catch (e) {
        console.error("Error parsing data-params:", e);
        return;
    }

    // Convert to array if only one key is passed
    const keysToRemove = Array.isArray(keys) ? keys : [keys];

    keysToRemove.forEach(key => {
        delete params[key];
    });

    // Save updated JSON back to dataset
    element.dataset.params = JSON.stringify(params);
}
