<?php
use YMCFilterGrids\admin\FG_UiLabels as UiLabels;

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">

	<div class="header"><?php echo esc_html($section_name); ?></div>
	<div class="body">
        <div class="headline js-headline-accordion" data-hash="filter_typography">
            <span class="inner">
                <i class="dashicons dashicons-editor-spellcheck"></i>
                <span class="text"><?php esc_html_e('Filter Typography Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
	        <?php $filter_typography_settings = UiLabels::all('filter_typography'); ?>

            <fieldset class="form-group form-group--with-bg typography">
                <div class="group-elements">
                    <legend class="form-legend">
		                <?php esc_html_e('Typography','ymc-smart-filters'); ?></legend>
                </div>

                <div class="group-elements">
	                <?php ymc_render_field_header('Font Family', 'Choose the font to be used for filter text'); ?>
                    <select class="form-select js-filter-font-family" name="ymc_fg_filter_typography[font_family]">
	                    <?php
	                    if($filter_typography_settings['font_family']) :
		                    foreach ($filter_typography_settings['font_family'] as $key => $value) :
			                    printf(
				                    '<option value="%s"%s>%s</option>',
				                    esc_attr($key),
				                    selected($ymc_fg_filter_typography['font_family'], $key, false),
				                    esc_html($value)
			                    );
		                    endforeach;
	                    endif;
	                    ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

	            <?php $is_hidden = ($ymc_fg_filter_typography['font_family'] === 'custom') ? '' : ' is-hidden'; ?>
                <div class="custom-font-fields js-filter-custom-font-fields<?php echo esc_attr($is_hidden); ?>">
	                <?php ymc_render_field_header('Enter font name exactly as used in CSS',
                        'Type the font name as it appears in CSS. This must match the font name loaded via the URL. 
                        For example: "Roboto", "Open Sans", or "MyCustomFont".'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom Font', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_filter_typography[custom_font_family]"
                           value="<?php echo esc_attr($ymc_fg_filter_typography['custom_font_family']); ?>">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Paste the font link URL from Google Fonts or another provider',
                        'Paste the full link to your custom font. For example, use a Google Fonts URL like:
                               https://fonts.googleapis.com/css2?family=YourFontName&display=swap'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom Font URL', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_filter_typography[custom_font_url]"
                           value="<?php echo esc_attr($ymc_fg_filter_typography['custom_font_url']); ?>">
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Size', 'Set the size of the text (e.g., 16px, 1em)'); ?>
                    <input class="form-input font-size" type="number" placeholder="16" min="0" step="0.1" name="ymc_fg_filter_typography[font_size]"
                           value="<?php echo esc_attr($ymc_fg_filter_typography['font_size']); ?>" required>
                    <select class="form-select font-size-unit" name="ymc_fg_filter_typography[font_size_unit]">
			            <?php
			            if($filter_typography_settings['font_size_unit']) :
				            foreach ($filter_typography_settings['font_size_unit'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_filter_typography['font_size_unit'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Weight', 'Set the thickness of the text (e.g., normal, bold, 500)'); ?>
                    <select class="form-select" name="ymc_fg_filter_typography[font_weight]">
		                <?php
		                if($filter_typography_settings['font_weight']) :
			                foreach ($filter_typography_settings['font_weight'] as $key => $value) :
				                printf(
					                '<option value="%s"%s>%s</option>',
					                esc_attr($key),
					                selected($ymc_fg_filter_typography['font_weight'], $key, false),
					                esc_html($value)
				                );
			                endforeach;
		                endif;
		                ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Style', 'Define the style of the font: normal, italic, or oblique'); ?>
                    <select class="form-select" name="ymc_fg_filter_typography[font_style]">
			            <?php
			            if($filter_typography_settings['font_style']) :
				            foreach ($filter_typography_settings['font_style'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_filter_typography['font_style'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Line Height', 'Controls the vertical spacing between lines of text'); ?>
                    <input class="form-input" type="number" step="0.01" placeholder="1" name="ymc_fg_filter_typography[line_height]"
                           value="<?php echo esc_attr($ymc_fg_filter_typography['line_height']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Letter Spacing', 'Adjust the space between letters (e.g., 1px, 0.05em)'); ?>
                    <input class="form-input" type="number" placeholder="1" step="0.01" name="ymc_fg_filter_typography[letter_spacing]"
                           value="<?php echo esc_attr($ymc_fg_filter_typography['letter_spacing']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Text Transform', 'Transform the case of the text (uppercase, lowercase, capitalize)'); ?>
                    <select class="form-select" name="ymc_fg_filter_typography[text_transform]">
                        <?php
                        if($filter_typography_settings['text_transform']) :
                            foreach ($filter_typography_settings['text_transform'] as $key => $value) :
                                printf(
                                    '<option value="%s"%s>%s</option>',
                                    esc_attr($key),
                                    selected($ymc_fg_filter_typography['text_transform'], $key, false),
                                    esc_html($value)
                                );
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="spacer-25"></div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg box-settings">
                <div class="group-elements">
                    <legend class="form-legend">
			            <?php esc_html_e('Box Settings','ymc-smart-filters'); ?></legend>
                </div>
                <div class="group-elements">
	                <?php ymc_render_field_header('Justify Content',
		                'Control the horizontal alignment of elements inside their container using Flexbox justify options.'); ?>
                    <select class="form-select" name="ymc_fg_filter_typography[justify_content]">
                        <?php
                        if($filter_typography_settings['justify_content']) :
                            foreach ($filter_typography_settings['justify_content'] as $key => $value) :
                                printf(
                                    '<option value="%s"%s>%s</option>',
                                    esc_attr($key),
                                    selected($ymc_fg_filter_typography['justify_content'], $key, false),
                                    esc_html($value)
                                );
                            endforeach;
                        endif;
                        ?>
                    </select>
                    <div class="spacer-25"></div>
                    <div class="grid-wrapper">
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Padding Top', 'Padding top, px'); ?>
                            <input class="form-input" type="number" placeholder="10" step="0.1" name="ymc_fg_filter_typography[padding][top]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['padding']['top']); ?>">
                        </div>
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Padding Right', 'Padding right, px'); ?>
                            <input class="form-input" type="number" placeholder="20" step="0.1" name="ymc_fg_filter_typography[padding][right]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['padding']['right']); ?>">
                        </div>
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Padding Bottom', 'Padding bottom, px'); ?>
                            <input class="form-input" type="number" placeholder="10" step="0.1" name="ymc_fg_filter_typography[padding][bottom]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['padding']['bottom']); ?>">
                        </div>
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Padding Left', 'Padding left, px'); ?>
                            <input class="form-input" type="number" placeholder="20" step="0.1" name="ymc_fg_filter_typography[padding][left]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['padding']['left']); ?>">
                        </div>
                    </div>
                    <div class="grid-wrapper">
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Margin Top', 'Margin top, px'); ?>
                            <input class="form-input" type="number" placeholder="0" step="0.1" name="ymc_fg_filter_typography[margin][top]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['margin']['top']); ?>">
                        </div>
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Margin Right', 'Margin right, px'); ?>
                            <input class="form-input" type="number" placeholder="10" step="0.1" name="ymc_fg_filter_typography[margin][right]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['margin']['right']); ?>">
                        </div>
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Margin Bottom', 'Margin bottom, px'); ?>
                            <input class="form-input" type="number" placeholder="10" step="0.1" name="ymc_fg_filter_typography[margin][bottom]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['margin']['bottom']); ?>">
                        </div>
                        <div class="grid-item">
	                        <?php ymc_render_field_header('Margin Left', 'Margin left, px'); ?>
                            <input class="form-input" type="number" placeholder="0" step="0.1" name="ymc_fg_filter_typography[margin][left]"
                                   value="<?php echo esc_attr($ymc_fg_filter_typography['margin']['left']); ?>">
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg color-settings">
                <div class="group-elements">
                    <legend class="form-legend">
		                <?php esc_html_e('Color Settings','ymc-smart-filters'); ?></legend>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Background Color', 'Background color of the filter element'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_filter_typography[background_color]'
                           value="<?php echo esc_attr($ymc_fg_filter_typography['background_color']); ?>" />
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Text Color', 'Default text color'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_filter_typography[color]'
                           value="<?php echo esc_attr($ymc_fg_filter_typography['color']); ?>" />
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Active Background Color', 'Background color for the active/selected filter'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_filter_typography[active_background_color]'
                           value="<?php echo esc_attr($ymc_fg_filter_typography['active_background_color']); ?>" />
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Active Text Color', 'Text color when the filter is in active/selected state'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_filter_typography[active_color]'
                           value="<?php echo esc_attr($ymc_fg_filter_typography['active_color']); ?>" />
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Hover Background Color', 'Background color on hover'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_filter_typography[hover_background_color]'
                           value="<?php echo esc_attr($ymc_fg_filter_typography['hover_background_color']); ?>" />
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Hover Text Color', 'Text color when hovering over the filter'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_filter_typography[hover_text_color]'
                           value="<?php echo esc_attr($ymc_fg_filter_typography['hover_text_color']); ?>" />
                </div>
                <div class="spacer-25"></div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="post_typography">
            <span class="inner">
                <i class="dashicons dashicons-editor-spellcheck"></i>
                <span class="text"><?php esc_html_e('Post Typography Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
	        <?php $post_typography_settings = UiLabels::all('post_typography'); ?>

            <fieldset class="form-group form-group--with-bg typography">
                <div class="group-elements">
                    <legend class="form-legend">
			            <?php esc_html_e('Post Title','ymc-smart-filters'); ?></legend>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Family', 'Choose the font to be used for post text'); ?>
                    <select class="form-select js-post-title-font-family" name="ymc_fg_post_typography[title][font_family]">
			            <?php
			            if($post_typography_settings['font_family']) :
				            foreach ($post_typography_settings['font_family'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['title']['font_family'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

	            <?php $is_hidden = ($ymc_fg_post_typography['title']['font_family'] === 'custom') ? '' : ' is-hidden'; ?>
                <div class="custom-font-fields js-post-title-custom-font-fields<?php echo esc_attr($is_hidden); ?>">
		            <?php ymc_render_field_header('Enter font name exactly as used in CSS',
			            'Type the font name as it appears in CSS. This must match the font name loaded via the URL. 
                        For example: "Roboto", "Open Sans", or "MyCustomFont".'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom Font', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_post_typography[title][custom_font_family]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['title']['custom_font_family']); ?>">
                    <div class="spacer-25"></div>

		            <?php ymc_render_field_header('Paste the font link URL from Google Fonts or another provider',
			            'Paste the full link to your custom font. For example, use a Google Fonts URL like:
                               https://fonts.googleapis.com/css2?family=YourFontName&display=swap'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom Font URL', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_post_typography[title][custom_font_url]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['title']['custom_font_url']); ?>">
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Size', 'Set the size of the text (e.g., 16px, 1em)'); ?>
                    <input class="form-input font-size" type="number" placeholder="16" min="0" step="0.1" name="ymc_fg_post_typography[title][font_size]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['title']['font_size']); ?>" required>
                    <select class="form-select font-size-unit" name="ymc_fg_post_typography[title][font_size_unit]">
			            <?php
			            if($post_typography_settings['font_size_unit']) :
				            foreach ($post_typography_settings['font_size_unit'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['title']['font_size_unit'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Weight', 'Set the thickness of the text (e.g., normal, bold, 500)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[title][font_weight]">
			            <?php
			            if($post_typography_settings['font_weight']) :
				            foreach ($post_typography_settings['font_weight'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['title']['font_weight'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Line Height', 'Controls the vertical spacing between lines of text'); ?>
                    <input class="form-input" type="number" step="0.01" placeholder="1" name="ymc_fg_post_typography[title][line_height]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['title']['line_height']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Letter Spacing Excerpt', 'Adjust the space between letters (e.g., 1px, 0.05em)'); ?>
                    <input class="form-input" type="number" placeholder="1" step="0.01" name="ymc_fg_post_typography[title][letter_spacing]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['title']['letter_spacing']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Text Transform Excerpt', 'Transform the case of the text (uppercase, lowercase, capitalize)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[title][text_transform]">
			            <?php
			            if($post_typography_settings['text_transform']) :
				            foreach ($post_typography_settings['text_transform'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['title']['text_transform'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Color', 'Default title color'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_post_typography[title][title_color]'
                           value="<?php echo esc_attr($ymc_fg_post_typography['title']['title_color']); ?>" />
                </div>
                <div class="spacer-25"></div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg typography">
                <div class="group-elements">
                    <legend class="form-legend">
				        <?php esc_html_e('Meta Info','ymc-smart-filters'); ?></legend>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Family', 'Choose the font to be used for post text'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[meta][font_family]">
			            <?php
			            if($post_typography_settings['font_family']) :
				            foreach ($post_typography_settings['font_family'] as $key => $value) :
                                if($key === 'custom') continue;
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['meta']['font_family'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Size', 'Set the size of the text (e.g., 16px, 1em)'); ?>
                    <input class="form-input font-size" type="number" placeholder="16" min="0" step="0.1"
                           name="ymc_fg_post_typography[meta][font_size]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['meta']['font_size']); ?>" required>
                    <select class="form-select font-size-unit" name="ymc_fg_post_typography[meta][font_size_unit]">
			            <?php
			            if($post_typography_settings['font_size_unit']) :
				            foreach ($post_typography_settings['font_size_unit'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['meta']['font_size_unit'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Weight', 'Set the thickness of the text (e.g., normal, bold, 500)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[meta][font_weight]">
			            <?php
			            if($post_typography_settings['font_weight']) :
				            foreach ($post_typography_settings['font_weight'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['meta']['font_weight'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Meta Color', 'Default meta color: author, date'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_post_typography[meta][meta_color]'
                           value="<?php echo esc_attr($ymc_fg_post_typography['meta']['meta_color']); ?>" />
                </div>
                <div class="spacer-25"></div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg typography">
                <div class="group-elements">
                    <legend class="form-legend">
				        <?php esc_html_e('Excerpt','ymc-smart-filters'); ?></legend>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Family', 'Choose the font to be used for post text'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[excerpt][font_family]">
			            <?php
			            if($post_typography_settings['font_family']) :
				            foreach ($post_typography_settings['font_family'] as $key => $value) :
					            if($key === 'custom') continue;
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['excerpt']['font_family'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Size', 'Set the size of the text (e.g., 16px, 1em)'); ?>
                    <input class="form-input font-size" type="number" placeholder="16" min="0" step="0.1"
                           name="ymc_fg_post_typography[excerpt][font_size]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['excerpt']['font_size']); ?>" required>
                    <select class="form-select font-size-unit" name="ymc_fg_post_typography[excerpt][font_size_unit]">
			            <?php
			            if($post_typography_settings['font_size_unit']) :
				            foreach ($post_typography_settings['font_size_unit'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['excerpt']['font_size_unit'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Weight', 'Set the thickness of the text (e.g., normal, bold, 500)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[excerpt][font_weight]">
			            <?php
			            if($post_typography_settings['font_weight']) :
				            foreach ($post_typography_settings['font_weight'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['excerpt']['font_weight'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Style', 'Define the style of the font: normal, italic, or oblique'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[excerpt][font_style]">
			            <?php
			            if($post_typography_settings['font_style']) :
				            foreach ($post_typography_settings['font_style'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['excerpt']['font_style'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Line Height', 'Controls the vertical spacing between lines of text'); ?>
                    <input class="form-input" type="number" step="0.01" placeholder="1"
                           name="ymc_fg_post_typography[excerpt][line_height]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['excerpt']['line_height']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Letter Spacing', 'Adjust the space between letters (e.g., 1px, 0.05em)'); ?>
                    <input class="form-input" type="number" placeholder="1" step="0.01"
                           name="ymc_fg_post_typography[excerpt][letter_spacing]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['excerpt']['letter_spacing']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Text Transform', 'Transform the case of the text (uppercase, lowercase, capitalize)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[excerpt][text_transform]">
			            <?php
			            if($post_typography_settings['text_transform']) :
				            foreach ($post_typography_settings['text_transform'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['excerpt']['text_transform'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Color', 'Default excerpt color'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_post_typography[excerpt][excerpt_color]'
                           value="<?php echo esc_attr($ymc_fg_post_typography['excerpt']['excerpt_color']); ?>" />
                </div>
                <div class="spacer-25"></div>
            </fieldset>

            <fieldset class="form-group form-group--with-bg typography">
                <div class="group-elements">
                    <legend class="form-legend">
				        <?php esc_html_e('Read More Link','ymc-smart-filters'); ?></legend>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Family', 'Choose the font to be used for post text'); ?>
                    <select class="form-select js-post-link-font-family" name="ymc_fg_post_typography[link][font_family]">
			            <?php
			            if($post_typography_settings['font_family']) :
				            foreach ($post_typography_settings['font_family'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['link']['font_family'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

	            <?php $is_hidden = ($ymc_fg_post_typography['link']['font_family'] === 'custom') ? '' : ' is-hidden'; ?>
                <div class="custom-font-fields js-post-link-custom-font-fields<?php echo esc_attr($is_hidden); ?>">
		            <?php ymc_render_field_header('Enter font name exactly as used in CSS',
			            'Type the font name as it appears in CSS. This must match the font name loaded via the URL. 
                        For example: "Roboto", "Open Sans", or "MyCustomFont".'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom Font', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_post_typography[link][custom_font_family]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['link']['custom_font_family']); ?>">
                    <div class="spacer-25"></div>

		            <?php ymc_render_field_header('Paste the font link URL from Google Fonts or another provider',
			            'Paste the full link to your custom font. For example, use a Google Fonts URL like:
                               https://fonts.googleapis.com/css2?family=YourFontName&display=swap'); ?>
                    <input class="form-input" type="text" placeholder="<?php esc_attr_e('Custom Font URL', 'ymc-smart-filters'); ?>"
                           name="ymc_fg_post_typography[link][custom_font_url]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['link']['custom_font_url']); ?>">
                    <div class="spacer-25"></div>
                </div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Size', 'Set the size of the text (e.g., 16px, 1em)'); ?>
                    <input class="form-input font-size" type="number" placeholder="16" min="0" step="0.1" name="ymc_fg_post_typography[link][font_size]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['link']['font_size']); ?>" required>
                    <select class="form-select font-size-unit" name="ymc_fg_post_typography[link][font_size_unit]">
			            <?php
			            if($post_typography_settings['font_size_unit']) :
				            foreach ($post_typography_settings['font_size_unit'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['link']['font_size_unit'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Font Weight', 'Set the thickness of the text (e.g., normal, bold, 500)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[link][font_weight]">
			            <?php
			            if($post_typography_settings['font_weight']) :
				            foreach ($post_typography_settings['font_weight'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['link']['font_weight'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Line Height', 'Controls the vertical spacing between lines of text'); ?>
                    <input class="form-input" type="number" step="0.01" placeholder="1"
                           name="ymc_fg_post_typography[link][line_height]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['link']['line_height']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Letter Spacing', 'Adjust the space between letters (e.g., 1px, 0.05em)'); ?>
                    <input class="form-input" type="number" placeholder="1" step="0.01"
                           name="ymc_fg_post_typography[link][letter_spacing]"
                           value="<?php echo esc_attr($ymc_fg_post_typography['link']['letter_spacing']); ?>" required>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Text Transform', 'Transform the case of the text (uppercase, lowercase, capitalize)'); ?>
                    <select class="form-select" name="ymc_fg_post_typography[link][text_transform]">
			            <?php
			            if($post_typography_settings['text_transform']) :
				            foreach ($post_typography_settings['text_transform'] as $key => $value) :
					            printf(
						            '<option value="%s"%s>%s</option>',
						            esc_attr($key),
						            selected($ymc_fg_post_typography['link']['text_transform'], $key, false),
						            esc_html($value)
					            );
				            endforeach;
			            endif;
			            ?>
                    </select>
                </div>
                <div class="spacer-25"></div>

                <div class="group-elements">
		            <?php ymc_render_field_header('Link Color', 'Default link color'); ?>
                    <input class="js-picker-color-alpha" data-alpha-enabled="true" type="text"
                           name='ymc_fg_post_typography[link][link_color]'
                           value="<?php echo esc_attr($ymc_fg_post_typography['link']['link_color']); ?>" />
                </div>
                <div class="spacer-25"></div>
            </fieldset>
        </div>
    </div>

</div>