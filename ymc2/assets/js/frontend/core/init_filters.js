
export async function initFilters($) {
    const filterElements = document.querySelectorAll('.js-ymc-container [data-filter-type]');
    const detectedTypes = new Set();

    filterElements.forEach(el => {
        const type = el.dataset.filterType;
        detectedTypes.add(type);
    });

    for (const type of detectedTypes) {
        switch (type) {
            case 'default':
                const { initDefaultFilter } = await import('../functions/defaultFilter.js');
                initDefaultFilter();
                break;

            case 'dropdown':
                const { initDropdownFilter } = await import('../functions/dropdownFilter.js');
                initDropdownFilter();
                break;

            case 'range':
                const { initRangeSliderFilter } = await import('../functions/rangeSliderFilter.js');
                initRangeSliderFilter();
                break;

            case 'date_picker':
                const { initDatePickerFilter } = await import('../functions/datePickerFilter.js');
                initDatePickerFilter();
                break;

            default:
                console.warn(`Unknown filter type: ${type}`);
        }
    }
}