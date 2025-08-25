import { YMC_BODY, YMC_CONTAINER, YMC_POST_ID, YMC_PATH } from "../core/constants.js";
import { setCookie, getCookie } from './cookieUtils.js';

export function accordionTabs() {
    YMC_CONTAINER.querySelectorAll('.tabs-content .js-headline-accordion').forEach(tab => {
        tab.addEventListener('click', openAccordionTab);
    });
    if(getCookie("hashAccordionYmc")) {
        let value = getCookie("hashAccordionYmc");
        YMC_CONTAINER.querySelectorAll(`.tabs-content .js-headline-accordion[data-hash="${value}"]`).forEach(tab => {
            tab.classList.add('is-active');
            tab.nextElementSibling.style.display = 'block';
            tab.querySelector('.js-icon-accordion').classList.add('fa-chevron-up');
            tab.querySelector('.js-icon-accordion').classList.remove('fa-chevron-down');
        });
    }
}

function openAccordionTab(e) {
    const self = e.target.closest('.js-headline-accordion');
    const content = self.nextElementSibling;
    const icon = self.querySelector('.js-icon-accordion');
    const hash = self.dataset.hash;

    self.classList.toggle('is-active');
    jQuery(content).slideToggle();
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
    setCookie("hashAccordionYmc", hash, 30);

    // Refresh Editor Codemirror
    if(hash === 'custom_css' || hash === 'custom_js') {
        setTimeout(function () {
            if (window.ymcEditors) {
                if (window.ymcEditors.css) {
                    window.ymcEditors.css.refresh();
                }
                if (window.ymcEditors.js) {
                    window.ymcEditors.js.refresh();
                }
            }
        }, 300);
    }
}

