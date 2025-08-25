<?php
/**
 * Template Container: Search
 */

if (!defined( 'ABSPATH' )) exit;

?>

<div class="filter-posts-search">
    <div class="search-form">
        <div class="search-wrapper">
            <input type="text" class="search-field js-search-field"
                   name="search_field"
                   placeholder="<?php echo esc_html($search_placeholder); ?>"
                   autofocus autocomplete="off"
                   data-grid-id="<?php echo esc_attr($filter_id); ?>"
                   data-state-autocomplete="<?php echo esc_attr($autocomplete_enabled); ?>"
                   data-search-mode="<?php echo esc_attr($search_mode); ?>"
                   data-post-title
                   value="" />
            <span class="clear-button js-clear-button" title="<?php esc_html_e('Clear', 'ymc-smart-filters'); ?>">&times;</span>
        </div>
        <div class="autocomplete-results js-autocomplete-results"></div>
        <button class="button button--primary js-search-button" type="button">
            <?php echo esc_attr($submit_button_text); ?></button>
    </div>
    <div class="results-found js-results-found"></div>
</div>


