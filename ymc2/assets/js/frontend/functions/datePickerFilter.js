import { resolveMainContainer } from "../utils/resolveMainContainer.js";
import { clearSearchForm } from "../utils/clearSearchForm.js";
import { removeDataParams, updateDataParams } from "../utils/updateDataParams.js";
import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";

export function initDatePickerFilter() {
    const dropdownWrappers = document.querySelectorAll('.date-picker-wrapper');

    dropdownWrappers.forEach(wrapper => {
        const selectedEl = wrapper.querySelector('.js-dropdown-selected');
        const dropdownEl = wrapper.querySelector('.date-picker__dropdown');
        const rangeEl = wrapper.querySelector('.js-date-range');
        const items = wrapper.querySelectorAll('.js-dropdown-item');
        const closeBtn = wrapper.querySelector('.js-dropdown-close');
        const cancelBtn = wrapper.querySelector('.js-btn-cancel');
        const applyBtn = wrapper.querySelector('.js-btn-apply');

        selectedEl.addEventListener('click', () => {
            dropdownEl.classList.toggle('is-open');
            selectedEl.classList.toggle('is-open');
        });

        items.forEach(item => {
            item.addEventListener('change', async (e) => {
                const container = resolveMainContainer(e.target);
                const input = item.querySelector('input');
                const value = input.dataset.value;

                items.forEach(i => i.classList.remove('is-selected'));
                item.classList.add('is-selected');
                selectedEl.textContent = input.value;

                if(value === 'other_time') {
                    rangeEl.classList.add('is-open');
                    dropdownEl.classList?.remove('is-open');
                } else {
                    rangeEl.classList?.remove('is-open')
                }

                clearSearchForm(container);
                removeDataParams(container, 'search');

                updateDataParams(container, {
                    date_filter: {
                        "type": value
                    },
                    paged: 1
                });

                if(value !== 'other_time') {
                    await fetchFilteredPosts(container);
                }
            });
        });

        if (cancelBtn) {
            cancelBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                rangeEl.classList?.remove('is-open');
                dropdownEl.querySelector('.js-dropdown-item').classList.add('is-selected');

                const input = dropdownEl.querySelector('input[data-value="all_time"]');
                if (input) {
                    input.checked = true;
                    selectedEl.textContent = input.value;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownEl.classList.remove('is-open');
                selectedEl.classList.remove('is-open');
            });
        }

        if (applyBtn) {
            applyBtn.addEventListener('click', async (e) => {
                e.stopPropagation();

                const container = resolveMainContainer(e.target);
                const dateFrom = parseInt(wrapper.querySelector('.js-date-range input[name="date_from"]').dataset.timestamp);
                const dateTo = parseInt(wrapper.querySelector('.js-date-range input[name="date_to"]').dataset.timestamp);
                const notification = wrapper.querySelector('.notification');

                if (dateTo >= dateFrom) {
                    notification.classList.add('is-hidden');
                    clearSearchForm(container);
                    removeDataParams(container, 'search');
                    updateDataParams(container, {
                        date_filter: {
                            "type": "other_time",
                            "from": dateFrom,
                            "to": dateTo
                        },
                        paged: 1
                    });

                    await fetchFilteredPosts(container);
                } else {
                    notification.classList.remove('is-hidden');
                }
            });
        }

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                dropdownEl.classList.remove('is-open');
                selectedEl.classList.remove('is-open');
            }
        });

        // Init datepicker
        jQuery('.datepicker').datepicker({
            dateFormat: 'M dd, yy',
            showAnim: 'slideDown',
            monthNamesShort: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ],
            onSelect: function(dateText, inst) {
                let timestamp = new Date(dateText).getTime() / 1000;
                let input = inst.input[0];
                input.dataset.timestamp = timestamp;
            }
        });

    });
}