import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";
import { removeDataParams, updateDataParams } from "../utils/updateDataParams.js";
import { clearSearchForm } from "../utils/clearSearchForm.js";
import { globalSelectedTerms } from "./filterState.js";
import { getEffectiveTermIds } from '../utils/getEffectiveTermIds.js';
import { resolveMainContainer } from '../utils/resolveMainContainer.js';

export function initDropdownFilter() {
    const filters = document.querySelectorAll('[data-filter-type="dropdown"]');
    const selectedTagsBySection = new Map();
    const initialSelectedTermsBySection = new Map();

    filters.forEach(filter => {
        const section = filter;
        const dropdowns = filter.querySelectorAll('.js-dropdown');
        const selectionMode = filter.getAttribute('data-selection-mode') || 'single';

        if (!selectedTagsBySection.has(section)) {
            selectedTagsBySection.set(section, []);
        }

        dropdowns.forEach(dropdown => {
            const selected = dropdown.querySelector('.js-dropdown-selected');
            const label = dropdown.querySelector('.js-dropdown-label');
            const items = dropdown.querySelectorAll('.js-dropdown-item');
            const closeBtn = dropdown.querySelector('.dropdown-close-btn');

            if (!initialSelectedTermsBySection.has(section)) {
                const initialIds = Array.from(filter.querySelectorAll('input[type="checkbox"]'))
                    .map(cb => Number(cb.getAttribute('data-value')));
                initialSelectedTermsBySection.set(section, initialIds);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.classList.remove('open');
                });
            }

            if (selectionMode === 'multiple') {
                const list = dropdown.querySelector('.ymc-dropdown__list');
                let allItem = dropdown.querySelector('.js-dropdown-all');
                let allCheckbox;

                if (!allItem) {
                    allItem = document.createElement('li');
                    allItem.className = 'ymc-dropdown__item js-dropdown-item js-dropdown-all';
                    allItem.innerHTML = `
                    <label class="ymc-dropdown__checkbox">
                        <input type="checkbox" class="js-dropdown-all-checkbox" />
                        <span class="checkmark"></span>
                        <span class="ymc-dropdown__checkbox">All</span>
                    </label>`;

                    const closeItem = list.querySelector('.ymc-dropdown__close');
                    if (closeItem) {
                        closeItem.insertAdjacentElement('afterend', allItem);
                    } else {
                        list.insertBefore(allItem, list.firstChild);
                    }
                }

                allCheckbox = allItem.querySelector('.js-dropdown-all-checkbox');

                const newCheckbox = allCheckbox.cloneNode(true);
                allCheckbox.parentNode.replaceChild(newCheckbox, allCheckbox);
                allCheckbox = newCheckbox;

                allCheckbox.addEventListener('change', async (e) => {
                    e.stopPropagation();

                    const container = resolveMainContainer(allCheckbox);
                    const selectedTags = selectedTagsBySection.get(section);
                    const initialIds = initialSelectedTermsBySection.get(section) || [];
                    const isAllChecked = allCheckbox.checked;

                    selectedTags.length = 0;

                    dropdown.querySelectorAll('input[type="checkbox"]:not(.js-dropdown-all-checkbox)').forEach(cb => {
                        cb.checked = false;
                        const item = cb.closest('.js-dropdown-item');
                        if (item) item.classList.remove('is-selected');
                    });

                    if (isAllChecked) {
                        initialIds.forEach(valueNum => {
                            const cb = dropdown.querySelector(`input[data-value="${valueNum}"]`);
                            if (cb) {
                                cb.checked = true;
                                const itemEl = cb.closest('.js-dropdown-item');
                                itemEl.classList.add('is-selected');

                                const text = itemEl.textContent.trim();
                                selectedTags.push({ value: valueNum.toString(), text, dropdown });
                                globalSelectedTerms.add(valueNum);
                            }
                        });
                    } else {
                        initialIds.forEach(valueNum => globalSelectedTerms.delete(valueNum));
                    }

                    updateTagDisplay(section, selectedTags);
                    clearSearchForm(container);
                    removeDataParams(container, 'search');

                    updateDataParams(container, {
                        terms: getEffectiveTermIds(container),
                        paged: 1
                    });

                    await fetchFilteredPosts(container);
                });
            }

            selected.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdownsExcept(dropdown);
                dropdown.classList.toggle('open');
            });

            items.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');

                checkbox.addEventListener('change', async (e) => {
                    e.stopPropagation();

                    const container = resolveMainContainer(checkbox);
                    const value = checkbox.getAttribute('data-value');
                    const valueNum = Number(value);
                    const text = item.querySelector('.term-name').textContent.trim();
                    const selectedTags = selectedTagsBySection.get(section);

                    if (selectionMode === 'multiple') {
                        item.classList.toggle('is-selected', checkbox.checked);

                        if (checkbox.checked) {
                            if (!selectedTags.some(v => v.value === value)) {
                                selectedTags.push({ value, text, dropdown });
                                globalSelectedTerms.add(valueNum);
                            }
                        } else {
                            const index = selectedTags.findIndex(v => v.value === value);
                            if (index !== -1) selectedTags.splice(index, 1);
                            globalSelectedTerms.delete(valueNum);

                            const allCheckbox = dropdown.querySelector('.js-dropdown-all-checkbox');
                            if (allCheckbox) allCheckbox.checked = false;
                        }

                        updateTagDisplay(section, selectedTags);
                        clearSearchForm(container);
                        removeDataParams(container, 'search');

                        updateDataParams(container, {
                            terms: getEffectiveTermIds(container),
                            paged: 1
                        });

                        await fetchFilteredPosts(container);
                    } else {
                        const wasChecked = checkbox.checked;

                        items.forEach(i => {
                            i.classList.remove('is-selected');
                            i.querySelector('input[type="checkbox"]').checked = false;
                        });

                        const existingIndex = selectedTags.findIndex(tag => tag.dropdown === dropdown);
                        if (existingIndex !== -1) {
                            const oldValue = selectedTags[existingIndex].value;
                            globalSelectedTerms.delete(Number(oldValue));
                            selectedTags.splice(existingIndex, 1);
                        }

                        if (wasChecked) {
                            checkbox.checked = true;
                            item.classList.add('is-selected');
                            label.textContent = text;
                            selectedTags.push({ value, text, dropdown });
                            globalSelectedTerms.add(valueNum);
                        } else {
                            label.textContent = dropdown.getAttribute('data-label') || 'Select option';
                        }

                        updateTagDisplay(section, selectedTags);
                        clearSearchForm(container);
                        removeDataParams(container, 'search');

                        updateDataParams(container, {
                            terms: getEffectiveTermIds(container),
                            paged: 1
                        });

                        await fetchFilteredPosts(container);
                    }
                });
            });
        });

        const tagContainer = ensureTagContainer(section);

        if (!tagContainer.dataset.listenerAdded) {
            tagContainer.addEventListener("click", async (e) => {
                if (e.target.classList.contains("tag__remove")) {
                    const value = e.target.getAttribute("data-value");
                    const valueNum = Number(value);
                    const selectedTags = selectedTagsBySection.get(section);
                    const index = selectedTags.findIndex(v => v.value === value);

                    if (index !== -1) {
                        const { dropdown } = selectedTags[index];
                        selectedTags.splice(index, 1);

                        const input = dropdown.querySelector(`input[data-value="${value}"]`);
                        if (input) {
                            input.checked = false;
                            input.closest('.js-dropdown-item').classList.remove('is-selected');
                        }

                        globalSelectedTerms.delete(valueNum);
                        updateTagDisplay(section, selectedTags);

                        const container = resolveMainContainer(dropdown);
                        clearSearchForm(container);
                        removeDataParams(container, 'search');

                        updateDataParams(container, {
                            terms: getEffectiveTermIds(container),
                            paged: 1
                        });

                        await fetchFilteredPosts(container);
                    }
                }
            });

            tagContainer.dataset.listenerAdded = "true";
        }
    });

    function ensureTagContainer(section) {
        let container = section.querySelector('.js-dropdown-selected-tags');
        if (!container) {
            container = document.createElement('div');
            container.className = 'filter-dropdown-selected-tags js-dropdown-selected-tags';
            section.appendChild(container);
        }
        return container;
    }

    function updateTagDisplay(section, values) {
        const filter = section;
        const selectionMode = filter.getAttribute('data-selection-mode') || 'single';
        if (selectionMode !== 'multiple') return;

        const container = ensureTagContainer(section);
        container.innerHTML = "";
        values.forEach(({ value, text }) => {
            const tag = document.createElement("div");
            tag.className = "tag";
            tag.dataset.value = value;
            tag.innerHTML = `${text} <span class="tag__remove" data-value="${value}">×</span>`;
            container.appendChild(tag);
        });
    }

    function closeAllDropdownsExcept(current) {
        document.querySelectorAll('.ymc-dropdown.open').forEach(d => {
            if (d !== current) d.classList.remove('open');
        });
    }

    document.addEventListener("click", (e) => {
        if (!e.target.closest('.ymc-dropdown')) {
            document.querySelectorAll('.ymc-dropdown.open').forEach(d => d.classList.remove("open"));
        }
    });
}
