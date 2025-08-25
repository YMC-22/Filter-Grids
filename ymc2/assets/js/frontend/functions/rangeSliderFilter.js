import { fetchFilteredPosts } from "../utils/fetchFilteredPosts.js";
import { removeDataParams, updateDataParams } from "../utils/updateDataParams.js";
import { clearSearchForm } from "../utils/clearSearchForm.js";
import { getEffectiveTermIds } from '../utils/getEffectiveTermIds.js';
import { resolveMainContainer } from '../utils/resolveMainContainer.js';

export function initRangeSliderFilter() {
    const filters = document.querySelectorAll('[data-filter-type="range"]');

    filters.forEach(filter => {
        let params = filter.querySelector('[data-tags]').dataset.tags;
        let selectedTags = filter.querySelector('[data-selected-tags]');

        if(params && selectedTags) {
            params = JSON.parse(params);

            let sliderOne = filter.querySelector(".slider-1");
            let sliderTwo = filter.querySelector(".slider-2");
            let displayValOne = filter.querySelector(".range1");
            let displayValTwo = filter.querySelector(".range2");
            let termsArray = [];
            let sliderTrack = filter.querySelector(".slider-track");
            let length = Object.keys(params).length;
            let entries = Object.entries(params);
            let minGap = 0;

            // Sorting
            entries.sort((a, b) => {
                if (!isNaN(Number(a[1])) && !isNaN(Number(b[1]))) {
                    return a[1] - b[1];
                }
                else {
                    return a[1].localeCompare(b[1]);
                }
            });
            // Add array
            for (const [ key, value ] of entries) {
                termsArray.push([key, value]);
            }

            sliderOne.setAttribute('max', length-1);
            sliderTwo.setAttribute('max', length-1);
            sliderTwo.setAttribute('value', length-1);
            displayValOne.textContent = termsArray[0][1];
            displayValTwo.textContent = termsArray[length-1][1];

            let sliderMaxValue = sliderOne.max;

            sliderOne.addEventListener("input", function (e) {

                if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                    sliderOne.value = parseInt(sliderTwo.value) - minGap;
                }

                sliderOne.style.zIndex = 10;
                sliderTwo.style.zIndex = 0;

                displayValOne.textContent = termsArray[sliderOne.value][1];

                let start = Number(sliderOne.value);
                let end = Number(sliderTwo.value);

                selectedTags.dataset.selectedTags = getRangeTerms(start,end,termsArray);

                fillRangeColor(sliderOne,sliderTwo,sliderMaxValue,sliderTrack);
            });

            sliderTwo.addEventListener("input", function (e) {

                if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                    sliderTwo.value = parseInt(sliderOne.value) + minGap;
                }

                sliderOne.style.zIndex = 0;
                sliderTwo.style.zIndex = 10;

                displayValTwo.textContent = termsArray[sliderTwo.value][1];

                let start = Number(sliderOne.value);
                let end = Number(sliderTwo.value);

                selectedTags.dataset.selectedTags = getRangeTerms(start,end,termsArray);

                fillRangeColor(sliderOne,sliderTwo,sliderMaxValue,sliderTrack);
            });

            fillRangeColor(sliderOne, sliderTwo, sliderMaxValue, sliderTrack);

        }

        filter.querySelector('.apply-button button').addEventListener('click', async function (e) {
            const container = resolveMainContainer(filter);

            clearSearchForm(container);
            removeDataParams(container, 'search');
            updateDataParams(container, {
                terms: getEffectiveTermIds(container),
                paged: 1
            });
            await fetchFilteredPosts(container);
        });
    });
}

function fillRangeColor(sliderOne,sliderTwo,sliderMaxValue,sliderTrack) {
    let percent1 = (sliderOne.value / sliderMaxValue) * 100;
    let percent2 = (sliderTwo.value / sliderMaxValue) * 100;
    sliderTrack.style.background = `linear-gradient(to right, #1F1F1F ${percent1}% , #1F1F1F ${percent1}% , #1F1F1F ${percent2}%, #1F1F1F ${percent2}%)`;
}

function getRangeTerms(s,e,termsArray) {
    let terms = '';
    for(let i = s; i <= e; i++) {
        terms += termsArray[i][0] + ',';
    }
    return terms.replace(/,\s*$/, "");
}


