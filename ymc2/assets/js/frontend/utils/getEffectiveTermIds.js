export function getEffectiveTermIds(container) {
    const allTermIds = new Set();

    const filterId = container.dataset.filterId;

    const internalFilters = Array.from(container.querySelectorAll('[data-filter-type]'));
    const extraFilters = Array.from(
        document.querySelectorAll(`[data-extra-filter-id="${filterId}"] [data-filter-type]`)
    );

    const allFilters = [...internalFilters, ...extraFilters];

    // Карта для отсечения дубликатов: key -> JSON.stringify(allTerms), value -> filter element
    const uniqueFiltersMap = new Map();

    for (const filter of allFilters) {
        const allTermsElement = filter.querySelector('[data-all-terms]');
        if (!allTermsElement) continue;

        let key;
        try {
            const allTerms = JSON.parse(allTermsElement.dataset.allTerms);
            key = JSON.stringify([...new Set(allTerms)].sort((a, b) => a - b)); // нормализуем
        } catch (e) {
            console.warn('Parsing error data-all-terms:', e);
            continue;
        }

        // Уже есть такой фильтр?
        if (uniqueFiltersMap.has(key)) {
            const existing = uniqueFiltersMap.get(key);

            const isExtra = filter.closest('[data-extra-filter-id]'); // текущий
            const existingIsExtra = existing.closest('[data-extra-filter-id]'); // предыдущий

            // Приоритет: extraFilter > обычный
            if (isExtra && !existingIsExtra) {
                uniqueFiltersMap.set(key, filter);
            }

        } else {
            uniqueFiltersMap.set(key, filter);
        }
    }
    // Добавить стратегию извлечения термов
    const filterStrategies = {

        dropdown: (filter) => {
            return Array.from(
                filter.querySelectorAll('input[type="checkbox"]:not(.js-dropdown-all-checkbox)'))
                .filter(cb => cb.checked)
                .map(cb => Number(cb.dataset.value));
        },

        default: (filter) => {
            return Array.from(
                filter.querySelectorAll('.filter-button.is-active:not(.js-filter-button-all)'))
                .map(btn => Number(btn.dataset.termid));
        },

        range: (filter) => {
            let tagsSelected = '';
            filter.querySelectorAll('.js-tag-values').forEach((el) => {
                tagsSelected += el.dataset.selectedTags+',';
            });
            tagsSelected = tagsSelected.split(',');
            tagsSelected = tagsSelected.filter((el) => el !== '');

            return tagsSelected.map((el) => Number(el));
        },

        _default: () => []

    };

    // Используем только уникальные фильтры
    for (const filter of uniqueFiltersMap.values()) {
        const strategy = filterStrategies[filter.dataset.filterType] || filterStrategies._default;
        const selected = strategy(filter);

        if (selected.length > 0) {
            selected.forEach(id => allTermIds.add(id));
        } else {
            const hasManual = filter.querySelector(
                '.filter-button.is-active:not(.js-filter-button-all), input[type="checkbox"]:checked:not(.js-dropdown-all-checkbox)');

            if (!hasManual) {
                const allTermsElement = filter.querySelector('[data-all-terms]');
                if (allTermsElement) {
                    try {
                        JSON.parse(allTermsElement.dataset.allTerms).map(Number).forEach(id => {
                            allTermIds.add(id);
                        });
                    } catch (e) {
                        console.warn('Parsing error data-all-terms', e);
                    }
                }
            }
        }
    }

    const final = Array.from(allTermIds);
    console.log('[getEffectiveTermIds] FINAL term IDs:', final);
    return final;
}

