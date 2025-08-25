<?php
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\admin\FG_UiLabels as UiLabels;

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">
	<div class="header"><?php echo esc_html($section_name); ?></div>

	<div class="body">
        <div class="headline js-headline-accordion" data-hash="filter_layout">
            <span class="inner">
                <i class="fas fa-th-large"></i>
                <span class="text"><?php echo esc_html__('Filter Layout', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap filter_layout">
	        <?php
                $is_hidden_filter_layout = $ymc_fg_filter_hidden === 'yes' ? ' is-hidden' : '';
                $is_hidden_notification = $ymc_fg_filter_hidden === 'no' ? ' is-hidden' : '';
            ?>
            <fieldset class="form-group js-is-disabled-filter-notification<?php echo esc_attr($is_hidden_notification); ?>">
                <div class="notification notification--info">
                    <?php esc_html_e('Filter is hidden on the frontend if "Disable Filter" is checked.', 'ymc-smart-filters');  ?>
                </div>
            </fieldset>

            <fieldset class="form-group filter-layout js-is-disabled-filter<?php echo esc_attr($is_hidden_filter_layout); ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Filter Type', 'Select Filter Type. <br>
                    Use <b>"Combined Filter"</b> to combine multiple taxonomy-based filters into a 
                    single filter block with individual configurations.'); ?>
	                <?php $filter_types = UiLabels::all('filter_types'); ?>
                    <select class="form-select js-toggle-filter-builder" name="ymc_fg_filter_type" id="ymc_fg_filter_type">
		                <?php
		                if($filter_types) :
			                foreach ($filter_types as $key => $filter_type) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_filter_type, $key, false),
					                esc_html($filter_type)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                </div>
                <div class="group-elements">
	                <?php $is_hidden_filter_builder = $ymc_fg_filter_type !== 'composite' ? 'is-hidden' : ''; ?>
                    <div class="filter-builder <?php echo esc_attr($is_hidden_filter_builder); ?>">
                        <div class="spacer-30"></div>
		                <?php ymc_render_field_header('Filters Settings', 'Set up filters based on taxonomies. 
	                    Choose a filter type and where it should appear on the page layout.'); ?>
                        <div class="filter-builder-header">
                            <div class="filter-builder-header-cell filter-builder-header-cell--taxonomies">
				                <?php ymc_render_field_header('Filter Category', 'Select taxonomy(s).'); ?>
                            </div>
                            <div class="filter-builder-header-cell filter-builder-header-cell--filter-type">
				                <?php ymc_render_field_header('Filter Type', 'Select filter type(s).<br> 
                                For the filter type "Date Selection" categories are ignored.'); ?>
                            </div>
                            <div class="filter-builder-header-cell filter-builder-header-cell--placement">
				                <?php ymc_render_field_header('Filter Position', 'Select the filter placement zone.'); ?>
                            </div>
                            <div class="filter-builder-header-cell filter-builder-header-cell--remove">
	                            <?php ymc_render_field_header('Remove', 'Remove filter.'); ?>
                            </div>
                        </div>
                        <div class="filter-builder-inner js-filter-group">
			                <?php
			                $tax_selected = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
			                $taxonomies = array_intersect_key(ymc_get_taxonomies($ymc_fg_post_types), array_flip($tax_selected));
			                $is_disabled_tax = empty($taxonomies) ? 'is-disabled' : '';

			                $filter_types = UiLabels::all('filter_types');
			                unset($filter_types['composite']);

			                $placements = UiLabels::all('placements');
			                $filter_options = Data_Store::get_meta_value($post_id, 'ymc_fg_filter_options');
			                ?>
			                <?php foreach ($filter_options as $index => $option) : ?>
                                <div class="filter-item">
                                    <select class="form-select form-select--multiple <?php echo esc_attr($is_disabled_tax); ?>"
                                            name="ymc_fg_filter_options[<?php echo esc_attr($index); ?>][tax_name][]"
                                            multiple>
						                <?php if($taxonomies) : ?>
							                <?php foreach ($taxonomies as $value => $label): ?>
                                                <option value="<?php echo esc_attr($value); ?>"
									                <?php echo in_array($value, $option['tax_name'] ?? []) ? 'selected' : ''; ?>>
									                <?php echo esc_html($label); ?>
                                                </option>
							                <?php endforeach; ?>
						                <?php else : ?>
                                            <option value="">
								                <?php esc_html_e('Taxonomy not selected', 'ymc-smart-filters'); ?></option>
						                <?php endif; ?>
                                    </select>

                                    <select class="form-select"
                                            name="ymc_fg_filter_options[<?php echo esc_attr($index); ?>][filter_type]">
						                <?php foreach ($filter_types as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>"
								                <?php selected($option['filter_type'] ?? 'default', $value); ?>>
								                <?php echo esc_html($label); ?>
                                            </option>
						                <?php endforeach; ?>
                                    </select>

                                    <select class="form-select"
                                            name="ymc_fg_filter_options[<?php echo esc_attr($index); ?>][placement]">
						                <?php foreach ($placements as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>"
								                <?php selected($option['placement'] ?? 'top', $value); ?>>
								                <?php echo esc_html($label); ?>
                                            </option>
						                <?php endforeach; ?>
                                    </select>

                                    <button class="button button--secondary js-remove-filter" type="button">
						                <?php esc_html_e('Delete', 'ymc-smart-filters'); ?>
                                    </button>
                                </div>
			                <?php endforeach; ?>
                        </div>
                        <button class="button button--primary js-add-filter" type="button">
                            <i class="fa-solid fa-plus"></i>
			                <?php esc_html_e('Add Filter', 'ymc-smart-filters'); ?></button>
                    </div>
                </div>

            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="post_layout">
            <span class="inner">
                <i class="fas fa-th-large"></i>
                <span class="text"><?php echo esc_html__('Post Layout', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap post-layout">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Post Layout', 'Select the post layout type.'); ?>
	                <?php $post_layouts = UiLabels::all('post_layouts'); ?>
                    <select class="form-select js-selected-post-layout" name="ymc_fg_post_layout" id="ymc_fg_post_layout">
		                <?php
		                if($post_layouts) :
			                foreach ($post_layouts as $key => $layout) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_post_layout, $key, false),
					                esc_html($layout)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                </div>
            </fieldset>

	        <?php $is_hidden_grid_settings = $ymc_fg_post_layout === 'layout_carousel' ? ' is-hidden' : ''; ?>

            <fieldset class="form-group form-group--with-bg js-grid-settings<?php echo esc_attr($is_hidden_grid_settings); ?>">
                <div class="group-elements">
                    <legend class="form-legend">
		                <?php esc_html_e('Grid Settings','ymc-smart-filters'); ?></legend>
	                <?php ymc_render_field_header('Column Layout',
		                'Select the number of columns for different screen sizes. 
		                Set the <b>Posts Per Page</b> parameter (Appearance - Pagination Settings) which allows to display the number of posts per page'); ?>
                    <div class="columns-grid">
	                    <?php
	                    $post_columns_layout = UiLabels::all('post_columns_layout');
	                    if($post_columns_layout) :
                            $icons_size = [
	                            'xl'  => 'fa-desktop',
	                            'lg'  => 'fa-desktop',
	                            'md'  => 'fa-tablet',
	                            'sm'  => 'fa-tablet',
	                            'xs'  => 'fa-mobile',
	                            'xxs' => 'fa-mobile'
                            ];
		                    foreach ($post_columns_layout as $key => $label) :
                                ?>
                                <div class="input-group">
                                    <span class="icon-prepend" title="<?php echo esc_attr($label); ?>">
                                        <i class="fa <?php echo esc_attr($icons_size[$key]) ?>"></i></span>
                                    <input class="form-input" type="number" min="1" max="6"
                                        name="ymc_fg_post_columns_layout[<?php echo esc_attr($key); ?>]"
                                        value="<?php echo esc_attr($ymc_fg_post_columns_layout[$key] ?? 1); ?>"/>
                                </div>
		                    <?php endforeach;
	                    endif;
	                    ?>
                    </div>
                    <div class="spacer-25"></div>

                    <div class="grid-gap-wrapper">
                        <div class="input-group">
		                    <?php ymc_render_field_header('Column Gap',
			                    'Controls the horizontal spacing between columns in the post grid.'); ?>
                            <input class="form-input input-grid_gap" type="number" name="ymc_fg_post_grid_gap[column_gap]"
                                   value="<?php echo esc_attr( $ymc_fg_post_grid_gap['column_gap'] ?? 30 ); ?>"  min="0" max="50" step="10" />
                        </div>
                        <div class="input-group">
		                    <?php ymc_render_field_header('Row Gap',
			                    'Controls the vertical spacing between rows in the post grid.'); ?>
                            <input class="form-input input-grid_gap" type="number" name="ymc_fg_post_grid_gap[row_gap]"
                                   value="<?php echo esc_attr( $ymc_fg_post_grid_gap['row_gap'] ?? 30 ); ?>"  min="0" max="50" step="10" />
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group js-grid-style<?php echo esc_attr($is_hidden_grid_settings); ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Grid Style',
                        'Choose how posts are positioned within the grid. Masonry allows for a dynamic 
                        layout with variable post heights.'); ?>
	                <?php $grid_style = UiLabels::all('grid_style'); ?>
                    <select class="form-select" name="ymc_fg_grid_style">
		                <?php
		                if($grid_style) :
			                foreach ($grid_style as $key => $layout) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_grid_style, $key, false),
					                esc_html($layout)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                </div>
            </fieldset>

	        <?php $is_hidden_carousel_settings = $ymc_fg_post_layout === 'layout_carousel' ? '' : ' is-hidden'; ?>

            <fieldset class="form-group form-group--with-bg carousel-settings js-carousel-settings<?php echo esc_attr($is_hidden_carousel_settings); ?>">

	            <?php $is_hidden_carousel_default_settings = $ymc_fg_carousel_settings['use_custom_settings'] === 'true' ? ' is-hidden' : ''; ?>
                <div class="group-elements js-default-carousel-settings<?php echo esc_attr($is_hidden_carousel_default_settings); ?>">
                    <legend class="form-legend">
		                <?php esc_html_e('Carousel Settings','ymc-smart-filters'); ?></legend>
                    <div class="swiper-settings">
	                    <?php ymc_render_field_header('General Settings',
		                    'Basic display options for the carousel.'); ?>
                        <div class="settings-grid">
                            <div class="cel">
	                            <?php ymc_render_field_header('Auto Height', 'Automatically adjust the carousel height.'); ?>
                            </div>
                            <div class="cel">
	                            <?php $carousel_settings_auto_height = UiLabels::all('ymc_fg_carousel_settings')['general']['auto_height']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][auto_height]">
	                                <?php
	                                if($carousel_settings_auto_height) :
		                                foreach ($carousel_settings_auto_height as $value) :
			                                printf(
				                                '<option value="%s"%s>%s</option>',
				                                esc_attr($value),
				                                selected($ymc_fg_carousel_settings['general']['auto_height'], $value, false),
				                                esc_html($value)
			                                );
		                                endforeach;
	                                endif;
	                                ?>
                                </select>
                            </div>
                            <div class="cel">
	                            <?php ymc_render_field_header('Autoplay',
		                            'Automatically slide to the next post.'); ?>
                            </div>
                            <div class="cel">
	                            <?php $carousel_settings_autoplay = UiLabels::all('ymc_fg_carousel_settings')['general']['autoplay']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][autoplay]">
		                            <?php
		                            if($carousel_settings_autoplay) :
			                            foreach ($carousel_settings_autoplay as $value) :
				                            printf(
					                            '<option value="%s"%s>%s</option>',
					                            esc_attr($value),
					                            selected($ymc_fg_carousel_settings['general']['autoplay'], $value, false),
					                            esc_html($value)
				                            );
			                            endforeach;
		                            endif;
		                            ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Delay', 'Time between slides in autoplay mode (ms).'); ?>
                            </div>
                            <div class="cel">
	                            <?php $carousel_settings_autoplay_delay = UiLabels::all('ymc_fg_carousel_settings')['general']['autoplay_delay']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][autoplay_delay]">
		                            <?php
		                            if($carousel_settings_autoplay_delay) :
			                            foreach ($carousel_settings_autoplay_delay as $value) :
				                            printf(
					                            '<option value="%s"%s>%s</option>',
					                            esc_attr($value),
					                            selected($ymc_fg_carousel_settings['general']['autoplay_delay'], $value, false),
					                            esc_html($value)
				                            );
			                            endforeach;
		                            endif;
		                            ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Loop', 'Enable infinite loop mode.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_loop = UiLabels::all('ymc_fg_carousel_settings')['general']['loop']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][loop]">
			                        <?php
			                        if($carousel_settings_loop) :
				                        foreach ($carousel_settings_loop as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['general']['loop'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Centered Slides', 'Center the active slide in the viewport.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_centered_slides = UiLabels::all('ymc_fg_carousel_settings')['general']['centered_slides']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][centered_slides]">
			                        <?php
			                        if($carousel_settings_centered_slides) :
				                        foreach ($carousel_settings_centered_slides as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['general']['centered_slides'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Slides Per View', 'Number of posts visible at once.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_slides_per_view = UiLabels::all('ymc_fg_carousel_settings')['general']['slides_per_view']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][slides_per_view]">
			                        <?php
			                        if($carousel_settings_slides_per_view) :
				                        foreach ($carousel_settings_slides_per_view as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['general']['slides_per_view'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Space Between', 'Gap in pixels between slides.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_space_between = UiLabels::all('ymc_fg_carousel_settings')['general']['space_between']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][space_between]">
			                        <?php
			                        if($carousel_settings_space_between) :
				                        foreach ($carousel_settings_space_between as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['general']['space_between'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Mouse Wheel', 'Enable slide navigation with mouse wheel.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_mousewheel = UiLabels::all('ymc_fg_carousel_settings')['general']['mousewheel']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][mousewheel]">
			                        <?php
			                        if($carousel_settings_mousewheel) :
				                        foreach ($carousel_settings_mousewheel as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['general']['mousewheel'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Slide Speed', 'Transition speed between slides (ms).'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_speed = UiLabels::all('ymc_fg_carousel_settings')['general']['speed']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][speed]">
			                        <?php
			                        if($carousel_settings_speed) :
				                        foreach ($carousel_settings_speed as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['general']['speed'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Transition Effect', 'Choose the effect used for slide transitions.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_effect = UiLabels::all('ymc_fg_carousel_settings')['general']['effect']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[general][effect]">
			                        <?php
			                        if($carousel_settings_effect) :
				                        foreach ($carousel_settings_effect as $key => $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($key),
						                        selected($ymc_fg_carousel_settings['general']['effect'], $key, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                        </div>

	                    <?php ymc_render_field_header('Pagination',
		                    'Configure pagination bullets or progress.'); ?>
                        <div class="settings-grid">
                            <div class="cel">
	                            <?php ymc_render_field_header('Show Pagination',
		                            'Show navigation bullets below the carousel.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_pagination_visible = UiLabels::all('ymc_fg_carousel_settings')['pagination']['visible']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[pagination][visible]">
			                        <?php
			                        if($carousel_settings_pagination_visible) :
				                        foreach ($carousel_settings_pagination_visible as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['pagination']['visible'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                            <div class="cel">
	                            <?php ymc_render_field_header('Dynamic Bullets',
		                            'Dynamically size bullets based on visible slides.'); ?>
                            </div>
                            <div class="cel">
	                            <?php $carousel_settings_pagination_dynamic_bullets = UiLabels::all('ymc_fg_carousel_settings')['pagination']['dynamic_bullets']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[pagination][dynamic_bullets]">
		                            <?php
		                            if($carousel_settings_pagination_dynamic_bullets) :
			                            foreach ($carousel_settings_pagination_dynamic_bullets as $value) :
				                            printf(
					                            '<option value="%s"%s>%s</option>',
					                            esc_attr($value),
					                            selected($ymc_fg_carousel_settings['pagination']['dynamic_bullets'], $value, false),
					                            esc_html($value)
				                            );
			                            endforeach;
		                            endif;
		                            ?>
                                </select>
                            </div>
                            <div class="cel">
		                        <?php ymc_render_field_header('Pagination Type',
			                        'Select bullet or fraction type.'); ?>
                            </div>
                            <div class="cel">
	                            <?php $carousel_settings_pagination_type = UiLabels::all('ymc_fg_carousel_settings')['pagination']['type']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[pagination][type]">
		                            <?php
		                            if($carousel_settings_pagination_type) :
			                            foreach ($carousel_settings_pagination_type as $key => $value) :
				                            printf(
					                            '<option value="%s"%s>%s</option>',
					                            esc_attr($key),
					                            selected($ymc_fg_carousel_settings['pagination']['type'], $key, false),
					                            esc_html($value)
				                            );
			                            endforeach;
		                            endif;
		                            ?>
                                </select>
                            </div>
                        </div>

	                    <?php ymc_render_field_header('Navigation',
		                    'Configure navigation buttons.'); ?>
                        <div class="settings-grid">
                            <div class="cel">
			                    <?php ymc_render_field_header('Show Navigation', 'Display previous/next arrows for navigation.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_navigation_visible = UiLabels::all('ymc_fg_carousel_settings')['navigation']['visible']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[navigation][visible]">
			                        <?php
			                        if($carousel_settings_navigation_visible) :
				                        foreach ($carousel_settings_navigation_visible as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['navigation']['visible'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                        </div>

	                    <?php ymc_render_field_header('Scrollbar',
		                    'Configure the scrollbar.'); ?>
                        <div class="settings-grid">
                            <div class="cel">
			                    <?php ymc_render_field_header('Show Scrollbar', 'Display a scrollbar.'); ?>
                            </div>
                            <div class="cel">
		                        <?php $carousel_settings_scrollbar_visible = UiLabels::all('ymc_fg_carousel_settings')['scrollbar']['visible']; ?>
                                <select class="form-select" name="ymc_fg_carousel_settings[scrollbar][visible]">
			                        <?php
			                        if($carousel_settings_scrollbar_visible) :
				                        foreach ($carousel_settings_scrollbar_visible as $value) :
					                        printf(
						                        '<option value="%s"%s>%s</option>',
						                        esc_attr($value),
						                        selected($ymc_fg_carousel_settings['scrollbar']['visible'], $value, false),
						                        esc_html($value)
					                        );
				                        endforeach;
			                        endif;
			                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group-elements">
	                <?php ymc_render_field_header('Custom Swiper Settings',
                        "Enable to ignore the plugin's default Swiper configuration and define your own settings in your theme's JavaScript files. 
                        Keep disabled to use the plugin's built-in defaults."); ?>
                    <div class="field-description">The carousel is implemented using the <a href='https://swiperjs.com/swiper-api#parameters' target='_blank'>Swiper API</a>.
                        <a class="tooltip-trigger js-tooltip-trigger" href="#" >Usage example:</a>
                        <div class="field-example js-field-example">
                            <pre><code class="language-js">
                            ymcHooks.addAction('ymc/grid/after_update_72', function(data, container) {
                                new Swiper('.swiper-72', {
                                    speed: 400,
                                    spaceBetween: 100,
                                     // do something...
                                });
                            });</code></pre>
                        </div>
                    </div>
                    <input type="hidden" name="ymc_fg_carousel_settings[use_custom_settings]" value="false">
                    <input class="form-checkbox js-checkbox-custom-carousel-settings" type="checkbox" value="true" name="ymc_fg_carousel_settings[use_custom_settings]"
                           id="ymc_fg_carousel_custom_settings" <?php checked( $ymc_fg_carousel_settings['use_custom_settings'], 'true' ); ?>>
                    <label class="field-label" for="ymc_fg_carousel_custom_settings">
			            <?php esc_html_e('Use Custom Swiper Settings', 'ymc-smart-filters'); ?></label>
                </div>

            </fieldset>
        </div>
    </div>
</div>