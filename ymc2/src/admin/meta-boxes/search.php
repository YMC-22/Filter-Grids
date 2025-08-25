<?php
use YMCFilterGrids\admin\FG_UiLabels as UiLabels;

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">

	<div class="header"><?php echo esc_html($section_name); ?>></div>
	<div class="body">
        <div class="headline js-headline-accordion" data-hash="search_layout">
            <span class="inner">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span class="text"><?php esc_html_e('Search Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap js-toggle-switch-search-posts">
            <fieldset class="form-group filter-state">
		        <?php ymc_render_field_header('Enable Search', 'Enable / disable search on frontend.'); ?>
                <label class="toggle-switch js-toggle-switch">
                    <input type="checkbox" name="ymc_fg_search_enable" value="yes"
				        <?php checked( $ymc_fg_search_enable, 'yes' ); ?>>
                    <span class="slider round">
                        <span class="on"><?php esc_html_e('ON', 'ymc-smart-filters'); ?></span>
                        <span class="off"><?php esc_html_e('OFF', 'ymc-smart-filters'); ?></span>
                    </span>
                </label>
            </fieldset>
	        <?php $is_enabled_search_options = $ymc_fg_search_enable === 'yes' ? '' : ' is-hidden'; ?>
            <fieldset class="form-group filter-options js-is-disabled-search-options<?php echo esc_attr($is_enabled_search_options); ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Search Mode','Defines how search behaves in combination with filters.<hr>
                        <ul>
                            <li>Global search – ignores filters and searches across all posts.</li>
                            <li>Search within filtered posts – applies search only to posts matching active filters.</li>
                        </ul>'); ?>
	                <?php $search_mode = UiLabels::all('search_mode'); ?>
                    <select class="form-select" name="ymc_fg_search_mode" id="ymc_fg_search_mode">
		                <?php
		                if($search_mode) :
			                foreach ($search_mode as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_search_mode, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Placeholder', 'Change placeholder field.'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Search...', 'ymc-smart-filters'); ?>" name="ymc_fg_search_placeholder"
                           value="<?php echo esc_attr($ymc_fg_search_placeholder); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Search Button Text', 'Defines the text displayed on the search submit button.'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Search', 'ymc-smart-filters'); ?>" name="ymc_fg_submit_button_text"
                           value="<?php echo esc_attr($ymc_fg_submit_button_text); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Results Found Text', 'Customize the message shown when search results are found. For example: "Results found", "Matching items", or any custom phrase.'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Results Found', 'ymc-smart-filters'); ?>" name="ymc_fg_results_found_text"
                           value="<?php echo esc_attr($ymc_fg_results_found_text); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Search in Custom Fields',
		                'Includes custom field (post meta) values in the search results. Useful for searching additional post information not found in titles or content.'); ?>
                    <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_search_meta_fields"
                           id="ymc_fg_search_meta_fields" <?php checked($ymc_fg_search_meta_fields, 'yes'); ?>>
                    <label class="field-label" for="ymc_fg_search_meta_fields">
		                <?php esc_html_e('Enable Search in Custom Fields', 'ymc-smart-filters'); ?></label>
                    <div class="spacer-25"></div>

                    <?php ymc_render_field_header('Exact Phrase',
		                'Search results will only include content that contains the full phrase exactly as entered.'); ?>
                    <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_exact_phrase"
                           id="ymc_fg_exact_phrase" <?php checked($ymc_fg_exact_phrase, 'yes'); ?>>
                    <label class="field-label" for="ymc_fg_exact_phrase">
		                <?php esc_html_e('Enable Exact Phrase', 'ymc-smart-filters'); ?></label>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Search Autocomplete',
                        'Turns on automatic suggestions as users type in the search field.'); ?>
                    <input class="form-checkbox js-autocomplete-enabled" type="checkbox" value="yes" name="ymc_fg_autocomplete_enabled"
                           id="ymc_fg_autocomplete_enabled" <?php checked($ymc_fg_autocomplete_enabled, 'yes'); ?>>
                    <label class="field-label" for="ymc_fg_autocomplete_enabled">
                        <?php esc_html_e('Enable Autocomplete', 'ymc-smart-filters'); ?></label>
                    <div class="spacer-25"></div>

	                <?php $is_hidden = in_array($ymc_fg_autocomplete_enabled, ['no'], true ) ? ' is-hidden' : ''; ?>
                    <div class="group-elements js-autocomplete-settings<?php echo esc_attr($is_hidden); ?>">
	                    <?php ymc_render_field_header('Maximum Number of Suggestions',
		                    'Limit how many suggestions appear as the user types. Recommended range is 5–15. Defaults to 10.'); ?>
                        <input class="form-input" type="number" name="ymc_fg_max_autocomplete_suggestions"
                               value="<?php echo esc_attr( $ymc_fg_max_autocomplete_suggestions ?? 10 ); ?>"  min="1" max="50" step="1" />
                    </div>

                </div>
            </fieldset>
        </div>
    </div>

</div>
