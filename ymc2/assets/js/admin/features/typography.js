import { YMC_TYPOGRAPHY } from "../core/constants.js";

export function setupTypography() {
    YMC_TYPOGRAPHY.querySelector('.js-filter-font-family').addEventListener('change', toggleCustomFilterFont);
    YMC_TYPOGRAPHY.querySelector('.js-post-title-font-family').addEventListener('change', toggleCustomPostTitleFont);
    YMC_TYPOGRAPHY.querySelector('.js-post-link-font-family').addEventListener('change', toggleCustomPostLinkFont);
}

function toggleCustomFilterFont() {
    const value = this.value;
    const customFontField = YMC_TYPOGRAPHY.querySelector('.js-filter-custom-font-fields');
    const showCustomFonts = value === 'custom';
    customFontField.style.display = showCustomFonts ? 'block' : 'none';
}

function toggleCustomPostTitleFont() {
    const value = this.value;
    const customFontField = YMC_TYPOGRAPHY.querySelector('.js-post-title-custom-font-fields');
    const showCustomFonts = value === 'custom';
    customFontField.style.display = showCustomFonts ? 'block' : 'none';
}

function toggleCustomPostLinkFont() {
    const value = this.value;
    const customFontField = YMC_TYPOGRAPHY.querySelector('.js-post-link-custom-font-fields');
    const showCustomFonts = value === 'custom';
    customFontField.style.display = showCustomFonts ? 'block' : 'none';
}





