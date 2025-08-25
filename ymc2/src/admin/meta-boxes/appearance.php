<?php
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\admin\FG_UiLabels as UiLabels;

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">
	<div class="header"><?php echo esc_html($section_name); ?></div>

	<div class="body">
        <div class="headline js-headline-accordion" data-hash="filter_settings">
            <span class="inner">
                <i class="fas fa-filter"></i>
                <span class="text"><?php echo esc_html__('Filter Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap js-toggle-switch-filter-state">
            <fieldset class="form-group filter-state">
                <?php ymc_render_field_header('Disable Filter', 'Disable filter on frontend.'); ?>
                <label class="toggle-switch js-toggle-switch">
                    <input type="checkbox" name="ymc_fg_filter_hidden" value="yes"
                        <?php checked( $ymc_fg_filter_hidden, 'yes' ); ?>>
                    <span class="slider round">
                        <span class="on"><?php esc_html_e('ON', 'ymc-smart-filters'); ?></span>
                        <span class="off"><?php esc_html_e('OFF', 'ymc-smart-filters'); ?></span>
                    </span>
                </label>
            </fieldset>
	        <?php $is_hidden_filter_options = $ymc_fg_filter_hidden === 'yes' ? 'is-hidden' : ''; ?>
            <fieldset class="form-group filter-options js-is-disabled-filter-options <?php echo esc_attr($is_hidden_filter_options); ?>">

                <div class="group-elements">
	                <?php ymc_render_field_header('Display Terms',
		                'Select how taxonomy terms should be displayed in the filter.
                     You can choose to show only selected terms or automatically populate all terms, with an option 
                     to hide empty terms.<hr>
                     <ul>
                     <li><b>Selected Terms Only</b> Display only manually selected terms.</li>
                     <li><b>Selected Terms (Hide Empty)</b> Show selected terms, but hide those with no posts.</li>
                     <li><b>All Terms (Auto Populate)</b> Display all terms, with an option to hide empty terms.</li>
                     <li><b>All Terms (Hide Empty)</b> Auto display all terms, excluding those with no posts.</li>
                     </ul>'); ?>
	                <?php $display_terms_mode = UiLabels::all('display_terms_mode'); ?>
                    <select class="form-select" name="ymc_fg_display_terms_mode" id="ymc_fg_display_terms_mode">
		                <?php
		                if($display_terms_mode) :
			                foreach ($display_terms_mode as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_display_terms_mode, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
	                <?php ymc_render_field_header('Term Sort Direction', 'Choose how to order the terms:<hr>
                    <ul><li>ascending (A–Z)</li><li>descending (Z–A)</li><li>or manual</li></ul>'); ?>
	                <?php $term_sort_direction = UiLabels::all('term_sort_direction'); ?>
                    <select class="form-select js-term-sort-direction" name="ymc_fg_term_sort_direction" id="ymc_fg_term_sort_direction">
		                <?php
		                if($term_sort_direction) :
			                foreach ($term_sort_direction as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_term_sort_direction, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>
                </div>

	            <?php $is_hidden = ('manual' === $ymc_fg_term_sort_direction) ? ' is-hidden' : ''; ?>
                <div class="group-elements js-term-sort-field<?php echo esc_attr($is_hidden); ?>">
	                <?php ymc_render_field_header('Field to Sort by Terms', 'Select the field to use to sort terms.<br> 
	                If manual sorting is selected, this field will not be taken into account when sorting terms.'); ?>
	                <?php $term_sort_field_by = UiLabels::all('term_sort_field'); ?>
                    <select class="form-select" name="ymc_fg_term_sort_field" id="ymc_fg_term_sort_field">
		                <?php
		                if($term_sort_field_by) :
			                foreach ($term_sort_field_by as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_term_sort_field, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
	                <?php ymc_render_field_header('Multiple Taxonomy',
		                'Allow users to filter posts using more than one taxonomy at the same time
                     (e.g., categories and tags together.'); ?>
                    <input class="form-checkbox" type="checkbox" value="multiple" name="ymc_fg_selection_mode"
                           id="ymc_fg_selection_mode" <?php checked( $ymc_fg_selection_mode, 'multiple' );  ?>>
                    <label class="field-label" for="ymc_fg_selection_mode">
                        <?php esc_html_e('Use multiple taxonomy filters', 'ymc-smart-filters'); ?></label>
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
                    <?php
                    $tax_selected = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
                    $filter_all_button = Data_Store::get_meta_value($post_id, 'ymc_fg_filter_all_button');
                    $taxonomies = array_intersect_key(ymc_get_taxonomies($ymc_fg_post_types), array_flip($tax_selected));
                    ?>
	                <?php ymc_render_field_header('"All" Button Settings', 'Customize text and visibility for the "All" option in filters.'); ?>
                    <div class="filter-all-settings">
                        <div class="filter-all-settings__header">
                            <div class="filter-all-settings__cell filter-all-settings__cell--headline">
                                <?php ymc_render_field_header('Taxonomy', 'Taxonomy name'); ?>
                            </div>
                            <div class="filter-all-settings__cell filter-all-settings__cell--headline">
	                            <?php ymc_render_field_header('"All" button label', 'Set the label for the "All" button'); ?>
                            </div>
                            <div class="filter-all-settings__cell filter-all-settings__cell--headline">
                                <?php ymc_render_field_header('Visibility', 'Set the visibility for the "All" button'); ?>
                            </div>
                        </div>
                        <?php
                        if($taxonomies) :
                        foreach ($taxonomies as $key => $value) : ?>
                            <div class="filter-all-settings__body">
	                            <?php printf('<div class="filter-all-settings__cell filter-all-settings__cell--headline">%s</div>',
		                               esc_attr($value)); ?>

	                            <?php printf('<div class="filter-all-settings__cell">
                                    <input class="form-input" type="text" name="ymc_fg_filter_all_button[%s][all_label]" placeholder="All" value="%s"></div>',
                                           esc_attr($key), esc_attr($filter_all_button[$key]['all_label'] ?? 'All')); ?>

                                <?php $is_visible_btn_all = $filter_all_button[$key]['is_visible'] ?? 'yes'; ?>
	                            <div class="filter-all-settings__cell">
                                    <select class="form-select" name="ymc_fg_filter_all_button[<?php echo esc_attr($key); ?>][is_visible]"">
                                    <option value="yes"
	                                    <?php selected($is_visible_btn_all, 'yes'); ?>>
                                        <?php echo esc_html('Visibile'); ?>
                                    </option>
                                    <option value="no"
                                        <?php selected($is_visible_btn_all, 'no'); ?>>
                                        <?php echo esc_html('Hidden'); ?>
                                    </option>
                                    </select>
                                </div>
                            </div>
                           <?php endforeach;
                        else :
                            printf('<div class="notification notification--info">%s</div>',
                                esc_html__('No taxonomies selected', 'ymc-smart-filters'));
                        endif; ?>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="post_settings">
            <span class="inner">
                <i class="far fa-address-card"></i>
                <span class="text"><?php echo esc_html__('Post Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">

            <fieldset class="form-group form-group--with-bg post-display-settings">
                <div class="group-elements">
                    <legend class="form-legend">
		            <?php esc_html_e('Post Elements','ymc-smart-filters'); ?></legend>
	                <?php ymc_render_field_header('Post Display Settings',
		                'Control which elements of the post are visible on the front end.'); ?>
	                <?php $post_display_settings = UiLabels::all('post_display_settings'); ?>
                    <div class="post-settings-section">
                        <div class="post-elements-grid">
			                <?php foreach ($post_display_settings as $key => $item): ?>
                                <div class="post-element">
	                                <?php ymc_render_field_header($item['label'], $item['tooltip']); ?>
                                    <select class="form-select" name="ymc_fg_post_display_settings[<?php echo esc_attr($key); ?>]">
                                        <option value="show" <?php selected($ymc_fg_post_display_settings[$key], 'show'); ?>>
                                            <?php esc_html_e('Show', 'ymc-smart-filters'); ?></option>
                                        <option value="hide" <?php selected($ymc_fg_post_display_settings[$key], 'hide'); ?>>
                                            <?php esc_html_e('Hide', 'ymc-smart-filters'); ?></option>
                                    </select>
                                </div>
			                <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset class="form-group form-group--with-bg">
                <div class="group-elements">
                    <legend class="form-legend">
                        <?php esc_html_e('Image settings','ymc-smart-filters'); ?></legend>
	                <?php ymc_render_field_header('Post Image Size',
                        'Select the size of the image to display in the post. Options: Thumbnail, Medium, Large, 
                        or Full size based on your media settings.'); ?>
	                <?php $post_image_size = UiLabels::all('post_image_size'); ?>
                    <select class="form-select" name="ymc_fg_post_image_size">
		                <?php
		                if($post_image_size) :
			                foreach ($post_image_size as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_post_image_size, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Image Clickable',
		                'Enable this option to make the post image clickable and link to the post or a custom URL.'); ?>
                    <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_image_clickable" id="ymc_fg_image_clickable"
                    <?php checked( $ymc_fg_image_clickable, 'yes' ); ?>>
                    <label class="field-label" for="ymc_fg_image_clickable">
		                <?php esc_html_e('Make Image Clickable', 'ymc-smart-filters'); ?></label>
                </div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg">
                <div class="group-elements">
                    <legend class="form-legend">
		                <?php esc_html_e('Button settings','ymc-smart-filters'); ?></legend>

	                <?php ymc_render_field_header('Button Text','Edit button text.'); ?>
                    <input class="form-input" type="text" placeholder="Read More" name="ymc_fg_post_button_text"
                           value="<?php echo esc_attr($ymc_fg_post_button_text); ?>">
                    <div class="spacer-25"></div>
	                <?php ymc_render_field_header('Link Target',
                        'Select whether the link opens in a new tab or the current one.'); ?>
	                <?php $target_option = UiLabels::all('target_option'); ?>
                    <select class="form-select" name="ymc_fg_target_option">
		                <?php
		                if($target_option) :
			                foreach ($target_option as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_target_option, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                </div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg">
                <div class="group-elements">
                    <legend class="form-legend">
		                <?php esc_html_e('Post settings','ymc-smart-filters'); ?></legend>

	                <?php ymc_render_field_header('Truncate Post Excerpt',
		                'Limit the post excerpt to a specific length, ending with an ellipsis if it exceeds the set limit.
				        Set the post excerpt truncate method:
				        <ul><li>Truncate text: truncate text to the specified number of words (default 30 words).</li>
				        <li>The first block of content: the first block of content (tags p or h1,h2,h3,h4,h5,h6).</li>
				        <li>At the first line break: at the first line break (tag: br)</li></ul>'); ?>
	                <?php $truncate_post_excerpt = UiLabels::all('truncate_post_excerpt'); ?>
                    <select class="form-select" name="ymc_fg_truncate_post_excerpt">
		                <?php
		                if($truncate_post_excerpt) :
			                foreach ($truncate_post_excerpt as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_truncate_post_excerpt, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

                    <?php ymc_render_field_header('Post Excerpt Length',
                        'Limit the post excerpt to a specific length, ending with an ellipsis if it exceeds the set limit.'); ?>
                    <input class="form-input" type="number" placeholder="30" min="0" name="ymc_fg_post_excerpt_length"
                           value="<?php echo esc_attr($ymc_fg_post_excerpt_length); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Post Order Type',
		                'Choose whether to show posts in ascending (ASC) or descending (DESC) order.'); ?>
	                <?php $post_order = UiLabels::all('post_order'); ?>
                    <select class="form-select" name="ymc_fg_post_order">
		                <?php
		                if($post_order) :
			                foreach ($post_order as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_post_order, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php $post_order_by = UiLabels::all('post_order_by'); ?>
	                <?php ymc_render_field_header('Post Order By',
		                'Choose the sorting method for posts (e.g., date, title, random).'); ?>
                    <select class="form-select js-post-order-by" name="ymc_fg_post_order_by">
	                    <?php
	                    if($post_order_by) :
		                    foreach ($post_order_by as $key => $value) :
			                    printf(
				                    '<option value="%s"%s>%s</option>',
				                    esc_attr($key),
				                    selected($ymc_fg_post_order_by, $key, false),
				                    esc_html($value)
			                    );
		                    endforeach;
	                    endif;
	                    ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php $is_hidden = ($ymc_fg_post_order_by === 'meta_key') ? '' : ' is-hidden'; ?>
                    <div class="order-fields-meta-key js-order-fields-meta-key<?php echo esc_attr($is_hidden); ?>">
	                    <?php ymc_render_field_header('Meta Key',
		                    'Set value of meta_key parameter (field data key).'); ?>
                        <input class="form-input" type="text" placeholder="meta_key" name="ymc_fg_order_meta_key"
                               value="<?php echo esc_attr($ymc_fg_order_meta_key); ?>">
                        <div class="spacer-25"></div>

	                    <?php ymc_render_field_header('Meta Value',
		                    'Set options: meta_value or meta_value_num (for numbers) to sort by meta field.'); ?>
                        <input class="form-input" type="text" placeholder="meta_value or meta_value_num" name="ymc_fg_order_meta_value"
                               value="<?php echo esc_attr($ymc_fg_order_meta_value); ?>">
                        <div class="spacer-25"></div>
                    </div>

	                <?php $is_hidden = ($ymc_fg_post_order_by === 'multiple_fields') ? '' : ' is-hidden'; ?>
                    <div class="order-fields-multiple-fields js-order-fields-multiple-fields<?php echo esc_attr($is_hidden); ?>">
                        <div class="post-order-fields-inner">
	                        <?php if(!empty($ymc_fg_post_order_by_multiple['fields'])) : ?>
		                        <?php foreach ($ymc_fg_post_order_by_multiple['fields'] as $index => $field) : ?>
                                    <div class="field-group">
                                        <div class="cel">
	                                        <?php ymc_render_field_header('Field name','Select field name'); ?>
                                            <select class="form-select" name="ymc_fg_post_order_by_multiple[fields][<?php echo esc_attr($index); ?>][field_name]">
	                                            <?php
	                                            $post_order_by = UiLabels::all('post_order_by');
	                                            if($post_order_by) :
		                                            foreach ($post_order_by as $key => $value) :
			                                            if($key === 'meta_key' || $key === 'multiple_fields') {
				                                            continue;
			                                            }
			                                            printf(
				                                            '<option value="%s"%s>%s</option>',
				                                            esc_attr($key),
				                                            selected($field['field_name'], $key, false),
				                                            esc_html($value)
			                                            );
		                                            endforeach;
	                                            endif;
	                                            ?>
                                            </select>
                                        </div>
                                        <div class="cel">
	                                        <?php ymc_render_field_header('Post Order Type','Select post order type'); ?>
                                            <select class="form-select" name="ymc_fg_post_order_by_multiple[fields][<?php echo esc_attr($index); ?>][order_type]">
	                                            <?php
	                                            $post_order = UiLabels::all( 'post_order' );
	                                            if($post_order) :
		                                            foreach ($post_order as $key => $value) :
			                                            printf(
				                                            '<option value="%s"%s>%s</option>',
				                                            esc_attr($key),
				                                            selected($field['order_type'], $key, false),
				                                            esc_html($value)
			                                            );
		                                            endforeach;
	                                            endif;
	                                            ?>
                                            </select>
                                        </div>
                                        <div class="cel">
                                            <button type="button" class="button--secondary js-remove-order-field">
			                                    <?php esc_html_e('Delete','ymc-smart-filters'); ?></button>
                                        </div>
                                    </div>
		                        <?php endforeach;
	                         endif; ?>
                        </div>
                        <template class="field-multiple-template">
                            <div class="field-group">
                                <div class="cel">
	                                <?php ymc_render_field_header('Field name','Select field name'); ?>
                                    <select class="form-select" name="ymc_fg_post_order_by_multiple[fields][index][field_name]">
	                                    <?php
	                                    $post_order_by = UiLabels::all('post_order_by');
	                                    if($post_order_by) :
		                                    foreach ($post_order_by as $key => $value) :
                                                if($key === 'meta_key' || $key === 'multiple_fields') {
                                                    continue;
                                                }
			                                    printf(
				                                    '<option value="%s">%s</option>',
				                                    esc_attr($key),
				                                    esc_html($value)
			                                    );
		                                    endforeach;
	                                    endif;
	                                    ?>
                                    </select>
                                </div>
                                <div class="cel">
	                                <?php ymc_render_field_header('Post Order Type','Select post order type'); ?>
                                    <select class="form-select" name="ymc_fg_post_order_by_multiple[fields][index][order_type]">
	                                    <?php
	                                    $post_order = UiLabels::all( 'post_order' );
	                                    if($post_order) :
		                                    foreach ($post_order as $key => $value) :
			                                    printf(
				                                    '<option value="%s">%s</option>',
				                                    esc_attr($key),
				                                    esc_html($value)
			                                    );
		                                    endforeach;
	                                    endif;
	                                    ?>
                                    </select>
                                </div>
                                <div class="cel">
                                    <button type="button" class="button--secondary js-remove-order-field">
                                        <?php esc_html_e('Delete','ymc-smart-filters'); ?></button>
                                </div>
                            </div>
                        </template>
                        <button type="button" class="button button--primary js-add-order-multiple-field">
                            <i class="fa-solid fa-plus"></i> <?php esc_html_e('Add Field', 'ymc-smart-filters'); ?></button>
                        <div class="spacer-25"></div>
                    </div>

	                <?php $post_status = UiLabels::all('post_status'); ?>
	                <?php ymc_render_field_header('Post Status',
		                'Select the status of posts to include (e.g., published, draft, pending).'); ?>
                    <select class="form-select" name="ymc_fg_post_status">
		                <?php
		                if($post_status) :
			                foreach ($post_status as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_post_status, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('No posts found',
		                'Customize the text shown when no posts are found.'); ?>
                    <input class="form-input" type="text" placeholder="No posts found" name="ymc_fg_no_results_message"
                           value="<?php echo esc_attr($ymc_fg_no_results_message); ?>">
                </div>

            </fieldset>

            <fieldset class="form-group form-group--with-bg">
                <div class="group-elements">
                    <legend class="form-legend">
		                <?php esc_html_e('Animation settings','ymc-smart-filters'); ?></legend>
	                <?php ymc_render_field_header('Animation Effect',
		                'Select how posts animate when they appear (e.g., Fade In, Bounce, Zoom).'); ?>
	                <?php $post_animation_effect = UiLabels::all('post_animation_effect'); ?>
                    <select class="form-select" name="ymc_fg_post_animation_effect">
		                <?php
		                if($post_animation_effect) :
			                foreach ($post_animation_effect as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_post_animation_effect, $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                </div>
            </fieldset>

        </div>

        <div class="headline js-headline-accordion" data-hash="popup_settings">
            <span class="inner">
                <i class="fas fa-window-restore"></i>
                <span class="text"><?php echo esc_html__('Popup Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap js-toggle-switch-popup">
            <fieldset class="form-group popup-state">
                <div class="group-elements">
					<?php ymc_render_field_header('Enable Popup', 'Enable popup on frontend.'); ?>

                    <label class="toggle-switch js-toggle-switch">
                        <input type="checkbox" name="ymc_fg_popup_enable" value="yes"
			                <?php checked( $ymc_fg_popup_enable, 'yes' ); ?>>
                        <span class="slider round">
                        <span class="on"><?php esc_html_e('ON', 'ymc-smart-filters'); ?></span>
                        <span class="off"><?php esc_html_e('OFF', 'ymc-smart-filters'); ?></span>
                    </span>
                    </label>
                </div>
            </fieldset>

	        <?php $is_enable_popup = $ymc_fg_popup_enable === 'no' ? ' is-hidden' : ''; ?>
            <fieldset class="form-group popup-settings js-is-enable-popup<?php echo esc_attr($is_enable_popup); ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Animation Type',
                        'If the <b>Popup Position</b> is <b>"Center Right"</b> or <b>"Center Left"</b>, only the "Slide" animation type will work.<br> 
                        For other positions, Fade In, Rotate, and Zoom In are available.'); ?>
	                <?php $popup_animation_type = UiLabels::all('popup_fields')['animation_type']; ?>
                    <select class="form-select" name="ymc_fg_popup_settings[animation_type]">
		                <?php
		                if($popup_animation_type) :
			                foreach ($popup_animation_type as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_popup_settings['animation_type'], $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Animation Origin', 'Set the animation speed for the popup.'); ?>
	                <?php $popup_animation_origin = UiLabels::all('popup_fields')['animation_origin']; ?>
                    <select class="form-select" name="ymc_fg_popup_settings[animation_origin]">
		                <?php
		                if($popup_animation_origin) :
			                foreach ($popup_animation_origin as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_popup_settings['animation_origin'], $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Popup Position', 'Choose where the popup will be displayed on the screen.'); ?>
	                <?php $popup_position = UiLabels::all('popup_fields')['position']; ?>
                    <select class="form-select" name="ymc_fg_popup_settings[position]">
		                <?php
		                if($popup_position) :
			                foreach ($popup_position as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_popup_settings['position'], $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                    <div class="spacer-25"></div>

                    <div class="group-input">
	                    <?php ymc_render_field_header('Popup Width', 'Set the width of the popup window. You can use units like px, %'); ?>
                        <input class="form-input popup-width" type="number" placeholder="600" name="ymc_fg_popup_settings[width][default]"
                               value="<?php echo esc_attr($ymc_fg_popup_settings['width']['default']); ?>">
	                    <?php $popup_unit_width = UiLabels::all('popup_fields')['width']['unit']; ?>
                        <select class="form-select popup-unit" name="ymc_fg_popup_settings[width][unit]">
		                    <?php
		                    if($popup_unit_width) :
			                    foreach ($popup_unit_width as $key => $value) :
				                    printf(
					                    '<option value="%s"%s>%s</option>',
					                    esc_attr($key),
					                    selected($ymc_fg_popup_settings['width']['unit'], $key, false),
					                    esc_html($value)
				                    );
			                    endforeach;
		                    endif;
		                    ?>
                        </select>
                    </div>
                    <div class="spacer-25"></div>

                    <div class="group-input">
	                    <?php ymc_render_field_header('Popup Height', 'Set the height of the popup window. You can use units like px, %'); ?>
                        <input class="form-input popup-height" type="number" placeholder="600" name="ymc_fg_popup_settings[height][default]"
                               value="<?php echo esc_attr($ymc_fg_popup_settings['height']['default']); ?>">
	                    <?php $popup_unit_height = UiLabels::all('popup_fields')['height']['unit']; ?>
                        <select class="form-select popup-unit" name="ymc_fg_popup_settings[height][unit]">
		                    <?php
		                    if($popup_unit_height) :
			                    foreach ($popup_unit_height as $key => $value) :
				                    printf(
					                    '<option value="%s"%s>%s</option>',
					                    esc_attr($key),
					                    selected($ymc_fg_popup_settings['height']['unit'], $key, false),
					                    esc_html($value)
				                    );
			                    endforeach;
		                    endif;
		                    ?>
                        </select>
                    </div>
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Popup Background Overlay', 'Set a custom background overlay for the popup.'); ?>
                    <input class="js-picker-color-alpha" type="text" name='ymc_fg_popup_settings[background_overlay]'
                           data-alpha-enabled="true" value="<?php echo esc_attr($ymc_fg_popup_settings['background_overlay']); ?>" />
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="pagination_settings">
            <span class="inner">
                <i class="fas fa-sort-numeric-down-alt"></i>
                <span class="text"><?php echo esc_html__('Pagination Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap js-toggle-switch-pagination">
            <fieldset class="form-group">
                <div class="group-elements">
					<?php ymc_render_field_header('Disable Pagination', 'Disable pagination on frontend.'); ?>
                    <label class="toggle-switch js-toggle-switch">
                        <input type="checkbox" name="ymc_fg_pagination_hidden" value="yes"
			                <?php checked( $ymc_fg_pagination_hidden, 'yes' ); ?>>
                        <span class="slider round">
                            <span class="on"><?php esc_html_e('ON', 'ymc-smart-filters'); ?></span>
                            <span class="off"><?php esc_html_e('OFF', 'ymc-smart-filters'); ?></span>
                        </span>
                    </label>
                </div>
            </fieldset>

	        <?php $is_hidden_pagination = $ymc_fg_pagination_hidden === 'yes' ? 'is-hidden' : ''; ?>
            <fieldset class="form-group js-is-disabled-pagination <?php echo esc_attr($is_hidden_pagination); ?>">

                <div class="group-elements">
                    <?php ymc_render_field_header('Pagination Type', 'Select pagination type.'); ?>
	                <?php $pagination_type = UiLabels::all('pagination_type'); ?>
                    <select class="form-select js-pagination_type" name="ymc_fg_pagination_type" id="ymc_fg_pagination_type">
	                    <?php
	                    if($pagination_type) :
		                    foreach ($pagination_type as $key => $value) :
			                    printf(
				                    '<option value="%s"%s>%s</option>',
				                    esc_attr($key),
				                    selected($ymc_fg_pagination_type, $key, false),
				                    esc_html($value)
			                    );
		                    endforeach;
	                    endif;
	                    ?>
                    </select>
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
	                <?php ymc_render_field_header('Posts Per Page',
                        'Number of posts to display per page before pagination or loading more. Use -1 to display all posts.'); ?>
                    <input class="form-input" type="text" placeholder="5" name="ymc_fg_per_page"
                           value="<?php echo esc_attr($ymc_fg_per_page); ?>">
                    <div class="spacer-25"></div>
                </div>

	            <?php $is_hidden = (in_array($ymc_fg_pagination_type, ['loadmore', 'infinite'], true )) ? ' is-hidden' : ''; ?>
                <div class="group-elements js-navigation-buttons<?php echo esc_attr($is_hidden); ?>">
	                <?php ymc_render_field_header('Prevision Button Text',
                        'Text displayed on the button to navigate to the previous item or page.'); ?>
                    <input class="form-input" type="text" placeholder="Prev" name="ymc_fg_prev_button_text"
                           value="<?php echo esc_attr($ymc_fg_prev_button_text); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Next Button Text',
		                'Text displayed on the button to navigate to the next item or page.'); ?>
                    <input class="form-input" type="text" placeholder="Prev" name="ymc_fg_next_button_text"
                           value="<?php echo esc_attr($ymc_fg_next_button_text); ?>">
                    <div class="spacer-25"></div>
                </div>

	            <?php $is_hidden = (in_array($ymc_fg_pagination_type, ['numeric'], true )) ? ' is-hidden' : ''; ?>
                <div class="group-elements js-load-more-button<?php echo esc_attr($is_hidden); ?>">
		            <?php ymc_render_field_header('Load More Button Text',
			            'Text shown on the button that loads additional items or content.'); ?>
                    <input class="form-input" type="text" placeholder="Load More" name="ymc_fg_load_more_text"
                           value="<?php echo esc_attr($ymc_fg_load_more_text); ?>">
                    <div class="spacer-25"></div>
                </div>

            </fieldset>

        </div>
    </div>
</div>