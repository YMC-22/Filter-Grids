import { YMC_BODY, YMC_CONTAINER, YMC_POST_ID, YMC_PATH } from "../core/constants.js";
export function setPreloader(target) {
    if(undefined === target) {
        YMC_BODY.insertAdjacentHTML("afterbegin",
            `<div class="preloader"><img src="${YMC_PATH}/assets/images/preloader.svg" alt="preloader"></div>
                    <div class="overlay"></div>`);
    } else {
        YMC_BODY.querySelector(target).insertAdjacentHTML("afterbegin",
            `<div class="preloader preloader--is-local"><img src="${YMC_PATH}/assets/images/preloader.svg" alt="preloader"></div>
                    <div class="overlay overlay--is-local"></div>`);
    }
}

export function removePreloader() {
    YMC_BODY.querySelector('.preloader').remove();
    YMC_BODY.querySelector('.overlay').remove();
}
