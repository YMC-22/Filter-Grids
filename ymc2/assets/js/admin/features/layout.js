import { YMC_LAYOUT } from "../core/constants.js";

export function setupLayout() {
    const postLayout = YMC_LAYOUT.querySelector('.js-selected-post-layout');
    const gridSettings = YMC_LAYOUT.querySelector('.js-grid-settings');
    const gridStyle = YMC_LAYOUT.querySelector('.js-grid-style');
    const carouselSettings = YMC_LAYOUT.querySelector('.js-carousel-settings');
    const defaultCarouselSettings = YMC_LAYOUT.querySelector('.js-default-carousel-settings');
    const checkboxCustomCarouselSettings = YMC_LAYOUT.querySelector('.js-checkbox-custom-carousel-settings');

    if(postLayout) {
        postLayout.addEventListener('change', function() {

            if(this.value === 'layout_carousel') {
                gridSettings.style.display = 'none';
                gridStyle.style.display = 'none';
                carouselSettings.style.display = 'block';
            } else {
                gridSettings.style.display = 'block';
                gridStyle.style.display = 'block';
                carouselSettings.style.display = 'none';
            }
        });
    }

    if(defaultCarouselSettings) {
        checkboxCustomCarouselSettings.addEventListener('change', function() {
            if(this.checked) {
                defaultCarouselSettings.style.display = 'none';
            } else {
                defaultCarouselSettings.style.display = 'block';
            }
        });
    }

    trimSpace();
    tooltipTrigger();

}

function tooltipTrigger() {
    const trigger = YMC_LAYOUT.querySelector('.js-tooltip-trigger');
    const example = YMC_LAYOUT.querySelector('.js-field-example');
    let hideTimeout;

    function showExample() {
        clearTimeout(hideTimeout);
        example.classList.add('visible');
    }
    function hideExample() {
        hideTimeout = setTimeout(() => {
            example.classList.remove('visible');
        }, 200);
    }

    trigger.addEventListener('mouseenter', showExample);
    trigger.addEventListener('mouseleave', hideExample);

    example.addEventListener('mouseenter', showExample);
    example.addEventListener('mouseleave', hideExample);
}

function trimSpace() {
    YMC_LAYOUT.querySelectorAll('.field-description pre code').forEach(block => {
        let lines = block.innerHTML.split('\n');
        while (lines.length && lines[0].trim() === '') lines.shift();
        while (lines.length && lines[lines.length - 1].trim() === '') lines.pop();
        let minIndent = Math.min(...lines.filter(l => l.trim()).map(l => l.match(/^(\s*)/)[0].length));
        block.innerHTML = lines.map(l => l.slice(minIndent)).join('\n');
    });
}


