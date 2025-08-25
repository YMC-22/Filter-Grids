import {updateDataParams} from "../utils/updateDataParams.js";
import {fetchFilteredPosts} from "../utils/fetchFilteredPosts.js";

export function initPostSort() {
    const dropdowns = document.querySelectorAll('.js-ymc-container .js-sort-dropdown');

    if(dropdowns) {
        dropdowns.forEach( dropdown => {
            let container= dropdown.closest('.js-ymc-container');
            const containerExtra = dropdown.closest('.ymc-extra-sort');
            const toggle   = dropdown.querySelector(".js-sort-dropdown-toggle");
            const menu     = dropdown.querySelector(".js-sort-dropdown-menu");
            const input    = dropdown.querySelector(".js-sort-order-input");
            const selected = dropdown.querySelector(".js-selected-value");

            toggle.addEventListener("click", function () {
                dropdown.classList.toggle("dropdown-open");
            });

            menu.querySelectorAll(".js-sort-item").forEach((item) => {
                item.addEventListener("click", async function () {
                    const orderBy = this.getAttribute("data-orderby");
                    let order = this.getAttribute("data-order");

                    item.closest('.js-sort-dropdown-menu').querySelectorAll('.js-sort-item').forEach((item) => {
                        item.classList.remove("is-asc", "is-desc");
                    });

                    if( order === 'DESC' ) {
                        order = 'ASC';
                        this.setAttribute("data-order", 'ASC');
                        this.classList.add("is-asc");
                        this.classList.remove("is-desc");
                        selected.classList.add("is-asc");
                        selected.classList.remove("is-desc");
                    } else {
                        order = 'DESC';
                        this.setAttribute("data-order", 'DESC');
                        this.classList.add("is-desc");
                        this.classList.remove("is-asc");
                        selected.classList.add("is-desc");
                        selected.classList.remove("is-asc");
                    }

                    selected.textContent = this.textContent;
                    input.value = orderBy;

                    if (containerExtra) {
                        const extraFilterId = containerExtra.dataset.extraFilterId;
                        container = document.querySelector(`.ymc-filter-${extraFilterId}`);
                    }

                    updateDataParams(container, {
                        order: order,
                        orderby: orderBy,
                        paged: 1
                    });
                    await fetchFilteredPosts(container);
                });
            });

            document.addEventListener("click", function (e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove("dropdown-open");
                }
            });
        });
    }
}




