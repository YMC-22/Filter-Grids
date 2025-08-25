export function resolveMainContainer(element) {
    if (!element) return null;

    const containerExtra = element.closest('.ymc-extra-filter');
    if (containerExtra) {
        const extraFilterId = containerExtra.dataset.extraFilterId;
        if (extraFilterId) {
            const target = document.querySelector(`.ymc-filter-${extraFilterId}`);
            if (target) return target;
        }
    }

    return element.closest('.js-ymc-container');
}


