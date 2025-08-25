/**
 * Initializes the color picker
 */
export function colorPicker($) {
    $('.js-picker-color').wpColorPicker();
    $('.js-picker-color-alpha').wpColorPicker({
        palettes: true,
        alpha: true,
        clear: true
    });

}