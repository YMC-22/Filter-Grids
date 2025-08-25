<?php
use YMCFilterGrids\admin\FG_UiLabels as UiLabels;
use YMCFilterGrids\FG_Data_Store as Data_Store;

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">

	<div class="header"><?php echo esc_html($section_name); ?></div>
	<div class="body">
        <div class="headline js-headline-accordion" data-hash="advanced_query_settings">
            <span class="inner">
                <i class="fas fa-database"></i>
                <span class="text"><?php esc_html_e('Advanced Query', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap js-toggle-switch-advanced-query">
            <fieldset class="form-group">
		        <?php ymc_render_field_header('Enable Advanced Query', 'Enable to build a custom query using your own parameters.'); ?>
                <label class="toggle-switch js-toggle-switch">
                    <input type="checkbox" name="ymc_fg_enable_advanced_query" value="yes"
				        <?php checked( $ymc_fg_enable_advanced_query, 'yes' ); ?>>
                    <span class="slider round">
                        <span class="on"><?php esc_html_e('ON', 'ymc-smart-filters'); ?></span>
                        <span class="off"><?php esc_html_e('OFF', 'ymc-smart-filters'); ?></span>
                    </span>
                </label>
            </fieldset>

	        <?php $is_enable_advanced_query = $ymc_fg_enable_advanced_query === 'yes' ? '' : ' is-hidden'; ?>
            <fieldset class="form-group js-is-enable-advanced-query<?php echo esc_attr($is_enable_advanced_query); ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Query Type',
                        'Choose how the query should be built for the posts grid.<br>
                        <ul><li><b>advanced</b> - Manually define WP_Query arguments to fully customize the query. 
                        Use this if you need fine-tuned control over the query logic.</li>
                        <li><b>callback</b> - Call a custom PHP function from your theme that returns WP_Query arguments or a query object.
                        (The function must exist in your theme’s functions.php.)</li></ul>'); ?>
	                <?php $query_type = UiLabels::all('query_type'); ?>
                    <select class="form-select js-advanced-query-type" name="ymc_fg_advanced_query_type">
		                <?php
		                if($query_type) :
			                foreach ($query_type as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_advanced_query_type, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

                    <?php $is_hidden = ($ymc_fg_advanced_query_type === 'advanced') ? '' : ' is-hidden'; ?>
                    <div class="advanced-query js-advanced-query<?php echo esc_attr($is_hidden); ?>">
                        <?php ymc_render_field_header('Query Arguments', 'Manually define WP_Query arguments to fully customize the query.'); ?>
                        <textarea class="form-textarea" name="ymc_fg_advanced_query" placeholder="Enter WP_Query arguments in format: posts_per_page=10&post_type=post&post_status=publish"><?php echo esc_textarea($ymc_fg_advanced_query); ?></textarea>
                    </div>

	                <?php $is_hidden = ($ymc_fg_advanced_query_type === 'callback') ? '' : ' is-hidden'; ?>
                    <div class="callback-function js-callback-function<?php echo esc_attr($is_hidden); ?>">
                        <?php ymc_render_field_header('Callback Function',
                            'Call a custom PHP function from your theme that returns WP_Query arguments or a query object.
                            (The function must exist in your theme’s functions.php.)'); ?>
                        <?php
                        $allowed_callback = [];
                        $allowed_callback = apply_filters('ymc/filter/query/wp/allowed_callbacks', $allowed_callback);
                        $allowed_callback = apply_filters('ymc/filter/query/wp/allowed_callbacks_'.$post_id, $allowed_callback);

                        if($allowed_callback) :
                        ?>
                        <select class="form-select" name="ymc_fg_query_allowed_callback">
                            <?php
                            foreach ($allowed_callback as $value) :
                                printf(
                                    '<option value="%s"%s>%s</option>',
                                    esc_attr($value),
                                    selected($ymc_fg_query_allowed_callback, $value, false),
                                    esc_html($value)
                                );
                            endforeach;
                            ?>
                        </select>
                        <?php else : ?>
                        <div class="notification notification--info">
                            <?php esc_html_e('No callbacks found', 'ymc-smart-filters'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
	                <?php ymc_render_field_header('Suppress Filters',
                        'Disable all filters on SQL queries, including those added by plugins or themes. 
                        Useful if you want to prevent external modifications to the query (e.g., by pre_get_posts).'); ?>
                    <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_advanced_suppress_filters"
                           id="ymc_fg_advanced_suppress_filters" <?php checked($ymc_fg_advanced_suppress_filters, 'yes'); ?>>
                    <label class="field-label" for="ymc_fg_advanced_suppress_filters">
		                <?php esc_html_e('Enable Suppress Filters', 'ymc-smart-filters'); ?></label>
                </div>
                <div class="spacer-25"></div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="sort_posts_settings">
            <span class="inner">
                <i class="fa-solid fa-arrow-down-short-wide"></i>
                <span class="text"><?php esc_html_e('Sort Posts (AJAX)', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap js-toggle-switch-sort-posts">
            <fieldset class="form-group">
		        <?php ymc_render_field_header('Enable Sorting', 'Enable to build a custom query using your own parameters.'); ?>
                <label class="toggle-switch js-toggle-switch">
                    <input type="checkbox" name="ymc_fg_enable_sort_posts" value="yes"
				        <?php checked( $ymc_fg_enable_sort_posts, 'yes' ); ?>>
                    <span class="slider round">
                        <span class="on"><?php esc_html_e('ON', 'ymc-smart-filters'); ?></span>
                        <span class="off"><?php esc_html_e('OFF', 'ymc-smart-filters'); ?></span>
                    </span>
                </label>
            </fieldset>

	        <?php $is_enable_sort_posts = $ymc_fg_enable_sort_posts === 'yes' ? '' : ' is-hidden'; ?>
            <fieldset class="form-group js-is-enable-sort-posts<?php echo esc_attr($is_enable_sort_posts); ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Sort Dropdown Label',
		                'Set the title shown above the sort dropdown on the frontend. Leave blank to use the default "Sort Posts By"'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Sort Posts By', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_sort_dropdown_label"
                           value="<?php echo esc_attr($ymc_fg_sort_dropdown_label); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Enable Sortable Fields', 'Select which fields should be available for sorting posts on the frontend.'); ?>
	                <?php $sortable_fields = UiLabels::all('post_sortable_fields'); ?>
                    <div class="checkbox-list">
                    <?php
                        if($sortable_fields) :
                            foreach ($sortable_fields as $key => $value) : ?>
                            <div class="item-checkbox">
                                <input class="form-checkbox" type="checkbox" value="<?php echo esc_attr($key); ?>"
                                       name="ymc_fg_post_sortable_fields[]"
                                       id="ymc_fg_post_sortable_field-<?php echo esc_attr($key); ?>"
                                    <?php checked(in_array($key, $ymc_fg_post_sortable_fields ?? [], true)); ?>>
                                <label class="field-label" for="ymc_fg_post_sortable_field-<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
                            </div>
                        <?php endforeach;
                        endif;
		            ?>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="extra_class_settings">
            <span class="inner">
                <i class="fa-brands fa-css3"></i>
                <span class="text"><?php esc_html_e('Container CSS Class', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Custom Container Class',
		                'Specify a custom CSS class to apply to the filter container. Useful for styling or targeting with JavaScript.'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom CSS Class', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_custom_container_class"
                           value="<?php echo esc_attr($ymc_fg_custom_container_class); ?>">
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="extra_filter_layout">
            <span class="inner">
                <i class="dashicons dashicons-layout"></i>
                <span class="text"><?php esc_html_e('Extra Filter Layout', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Extra Filter Display Types',
                        'Defines how the selected taxonomies will be displayed in the extra filter section.'); ?>
	                <?php $filter_types = UiLabels::all('filter_types'); ?>
                    <select class="form-select" name="ymc_fg_extra_filter_type">
		                <?php
		                if($filter_types) :
			                foreach ($filter_types as $key => $filter_type) :
                                if ($key === 'composite') {
                                    continue;
                                }
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_extra_filter_type, $key, false),
					                esc_html($filter_type)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>
                </div>
                <div class="group-elements">
	                <?php ymc_render_field_header('Taxonomies for Extra Filter',
		                'The selected taxonomies will be used to build external (extra) filters on the page. For a filter of the Date Picker type, selecting a category is not required.'); ?>
                    <?php
                        $tax_name = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
                        $tax_selected = array_intersect_key(ymc_get_taxonomies($ymc_fg_post_types), array_flip($tax_name));
                        ?>
                    <select class="form-select" name="ymc_fg_extra_taxonomy">
                        <?php if($tax_selected) :
                            foreach ($tax_selected as $key => $label) :
                                printf(
                                    '<option value="%s"%s>%s</option>',
                                    esc_attr($key),
                                    selected($ymc_fg_extra_taxonomy, $key, false),
                                    esc_html($label)
                                );
                            endforeach;
                        endif; ?>
                    </select>
                </div>

            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="custom_css">
            <span class="inner">
                <i class="fa-regular fa-file-code"></i>
                <span class="text"><?php esc_html_e('Custom CSS', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
			        <?php ymc_render_field_header('Custom CSS', 'Add custom CSS to the filter container. 
			        Use the #ymc-filter-* identifier. For example, #ymc-filter-1 to override styles.'); ?>
                    <textarea class="form-textarea ymc-fg-custom-css" id="ymc-fg-custom-css" name="ymc_fg_custom_css" rows="10"><?php echo esc_textarea($ymc_fg_custom_css); ?></textarea>
                    <div class="spacer-25"></div>
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="custom_js">
            <span class="inner">
                <i class="fa-regular fa-file-code"></i>
                <span class="text"><?php esc_html_e('Custom Actions', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Custom JavaScript', 'Add custom JavaScript'); ?>
                    <textarea class="form-textarea ymc-fg-custom-js" id="ymc-fg-custom-js" name="ymc_fg_custom_js" rows="10"><?php echo esc_textarea($ymc_fg_custom_js); ?></textarea>
                    <div class="spacer-25"></div>
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="preloader_settings">
            <span class="inner">
                <i class="dashicons dashicons-image-filter"></i>
                <span class="text"><?php esc_html_e('Preloader', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
		            <?php ymc_render_field_header('Preloader Icon',
			            'Choose an icon to display during content loading. This icon will be shown while the filter is fetching posts.'); ?>
		            <?php $preloader_icons = UiLabels::all('preloader_icons'); ?>
                    <select class="form-select js-preloader-icon" name="ymc_fg_preloader_settings[icon]">
			            <?php
			            if($preloader_icons) :
				            foreach ($preloader_icons as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_preloader_settings['icon'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                    <div class="spacer-25"></div>
                    <div class="preview-preloader js-preview-preloader"></div>
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Filter CSS for Preloader Icon', 'Choose a filter CSS to change the color of the icon.'); ?>
		            <?php $filter_preloader = UiLabels::all('filter_preloader'); ?>
                    <select class="form-select js-preloader-filters" name="ymc_fg_preloader_settings[filter_preloader]">
			            <?php
			            if($filter_preloader) :
				            foreach ($filter_preloader as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_preloader_settings['filter_preloader'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                    <div class="spacer-25"></div>
                </div>

	            <?php $is_hidden = $ymc_fg_preloader_settings['filter_preloader'] === 'custom_filter' ? '' : ' is-hidden'; ?>
                <div class="group-elements js-preloader-custom-filters<?Php echo esc_attr($is_hidden); ?>">
		            <?php ymc_render_field_header('Custom Filters CSS', 'Add a list of filters.'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('grayscale(0.5) brightness(0.7) invert(75%)', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_preloader_settings[custom_filters_css]"
                           value="<?php echo esc_attr($ymc_fg_preloader_settings['custom_filters_css']); ?>">
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="scroll_pagination">
            <span class="inner">
                <i class="fa-solid fa-scroll"></i>
                <span class="text"><?php esc_html_e('Scroll Pagination', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Scroll to Filters After Loading',
		                'Automatically scrolls to the filter bar after new posts are loaded.'); ?>
                    <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_scroll_to_filters_on_load"
                           id="ymc_fg_scroll_to_filters_on_load" <?php checked($ymc_fg_scroll_to_filters_on_load, 'yes'); ?>>
                    <label class="field-label" for="ymc_fg_scroll_to_filters_on_load">
		                <?php esc_html_e('Enable Scroll Up To Filters', 'ymc-smart-filters'); ?></label>
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="debug_mode">
            <span class="inner">
                <span class="dashicons dashicons-code-standards"></span>
                <span class="text"><?php esc_html_e('Debug Mode', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Debug Mode',
		                'Display detailed debug information (AJAX, filters, and response data) to help identify potential 
		                issues. <br>Use Chrome DevTools or other tools in other browsers to analyze and view the response from 
		                the server when sending requests. To do this, select the Network tab, then select the current request 
		                and in the Preview tab you will see additional response parameters. The response will display a 
		                Debug section with additional information. This is useful for debugging requests using the JS-hooks 
		                plugin and development of custom scripts. <br><b>For production, disable this option for security!</b>'); ?>
                    <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_debug_mode"
                           id="ymc_fg_debug_mode" <?php checked($ymc_fg_debug_mode, 'yes'); ?>>
                    <label class="field-label" for="ymc_fg_debug_mode">
		                <?php esc_html_e('Enable Debug Mode', 'ymc-smart-filters'); ?></label>
                </div>
            </fieldset>
        </div>

<!--        <div class="headline js-headline-accordion" data-hash="api_js_settings">
            <span class="inner">
                <i class="dashicons dashicons-rest-api"></i>
                <span class="text"><?php /*echo esc_html__('JavaScript API', 'ymc-smart-filters'); */?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
				<?php /*ymc_render_field_header('Enable JavaScript Filter API', 'Enable dynamic post filtering
	            using JavaScript. When enabled, the filter grid will update posts asynchronously without reloading the page.'); */?>
				<?php /*$checked_filter_api = ('yes' === get_option('ymc_fg_enable_js_filter_api')) ? 'checked' : ''; */?>
                <label class="toggle-switch js-toggle-switch">
                    <input type="checkbox" name="ymc_fg_enable_js_filter_api" value="yes"
						<?php /*echo esc_attr($checked_filter_api); */?>>
                    <span class="slider round">
                        <span class="on"><?php /*esc_html_e('ON', 'ymc-smart-filters'); */?></span>
                        <span class="off"><?php /*esc_html_e('OFF', 'ymc-smart-filters'); */?></span>
                    </span>
                </label>
            </fieldset>
        </div>-->

<!--        <div class="headline js-headline-accordion" data-hash="legacy_plugin">
            <span class="inner">
                <i class="fa-solid fa-hourglass-start"></i>
                <span class="text"><?php /*esc_html_e('Legacy Mode', 'ymc-smart-filters'); */?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
                <fieldset class="form-group">
                    <div class="group-elements">
		                <?php /*ymc_render_field_header('Enable Legacy Version', 'Activates the legacy version of the plugin for compatibility with
                         your theme or other plugins.<br> Not recommended for new sites.'); */?>
		                <?php /*$status_legacy = ('yes' === $data['ymc_plugin_legacy_is']) ? 'checked' : ''; */?>
                        <label class="toggle-switch toggle-switch-legacy">
                            <input type="checkbox" name="ymc_plugin_legacy_is" value="yes" <?php /*echo esc_attr($status_legacy); */?>>
                            <span class="slider round">
                        <span class="on"><?php /*esc_html_e('ON', 'ymc-smart-filters'); */?></span>
                        <span class="off"><?php /*esc_html_e('OFF', 'ymc-smart-filters'); */?></span>
                    </span>
                        </label>
                    </div>
                </fieldset>
            </div>-->

    </div>

</div>
