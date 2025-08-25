import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH } from "../core/constants.js";
import { setCookie, getCookie } from '../utils/cookieUtils.js';
import { accordionTabs } from '../utils/accordionTabs.js';

export function initTabs() {
    let items = YMC_CONTAINER.querySelectorAll('.tabs-sidebar .nav .nav__item');
    let sections = YMC_CONTAINER.querySelectorAll('.tabs-content .section');
    items.forEach((item) => {
        item.addEventListener('click', (e) => {
            let navItem = e.target.closest('.nav__item');
            let hash = navItem.dataset.hash;
            setCookie("hashymc", hash, 30);
            items.forEach((item) => {
                item.classList.remove('is-current');
            });
            sections.forEach((section) => {
                if(hash === section.getAttribute('id')) {
                    section.classList.add('is-active');
                } else {
                    section.classList.remove('is-active');
                }
            });
            navItem.classList.add('is-current');
        });
    });
    if(getCookie("hashymc") !== '') {
        let hash = getCookie("hashymc");
        items.forEach((item) => {
            if(hash === item.dataset.hash) {
                items.forEach((item) => {
                    item.classList.remove('is-current');
                });
                item.classList.add('is-current');
                sections.forEach((section) => {
                    if(hash === section.getAttribute('id')) {
                        section.classList.add('is-active');
                    } else {
                        section.classList.remove('is-active');
                    }
                });
            }
        });
    }
    accordionTabs();
}
