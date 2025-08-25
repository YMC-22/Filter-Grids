<?php

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">

	<div class="header"><?php echo esc_html($section_name); ?></div>
	<div class="body">
        <div class="headline js-headline-accordion" data-hash="shortcode_posts_grid">
            <span class="inner">
                <i class="dashicons dashicons-shortcode"></i>
                <span class="text"><?php esc_html_e('Shortcode Posts Grid', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Shortcode', 'Directly paste this shortcode in your page.'); ?>
                    <input class="form-input" type="text" readonly
                           value="[ymc_filter id='<?php echo esc_attr($post_id); ?>']"
                           onfocus="this.select()">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Shortcode For Page Template', 'Directly paste this shortcode in your page template.'); ?>
	                <?php $shortcode_code = "&lt;?php echo do_shortcode('[ymc_filter id=&quot;". esc_attr($post_id) ."&quot;]'); ?&gt;"; ?>
                    <input class="form-input" type="text" readonly
                           value="<?php echo esc_attr($shortcode_code); ?>"
                           onfocus="this.select()">
                    <div class="spacer-25"></div>
                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="shortcode_extra_components">
            <span class="inner">
                <i class="dashicons dashicons-shortcode"></i>
                <span class="text"><?php esc_html_e('Shortcode Extra Components', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Shortcode Extra Filter',
                        'Place shortcode filter posts anywhere on your page to filter posts in a grid. 
                        If there are several of the same filters on the page (the filter ID is the same), then this shortcode will be 
                        applied only to the first filter,'); ?>
                    <input class="form-input" type="text" readonly
                           value="[ymc_extra_filter id='<?php echo esc_attr($post_id); ?>']"
                           onfocus="this.select()">
                    <div class="spacer-25"></div>

	                <?php ymc_render_field_header('Shortcode Extra Search',
		                'Place shortcode search posts anywhere on your page to filter posts in a grid. If there are several 
		                of the same filters on the page (the filter ID is the same), then this shortcode will be applied only to the 
		                first filter,'); ?>
                    <input class="form-input" type="text" readonly
                           value="[ymc_extra_search id='<?php echo esc_attr($post_id); ?>']"
                           onfocus="this.select()">
                    <div class="spacer-25"></div>

                    <?php ymc_render_field_header('Shortcode Extra Sort',
                        'Place shortcode sort posts anywhere on your page to filter posts in a grid. If there are several of the same 
                        filters on the page (the filter ID is the same), then this shortcode will be applied only to the first filter,'); ?>
                    <input class="form-input" type="text" readonly
                           value="[ymc_extra_sort id='<?php echo esc_attr($post_id); ?>']"
                           onfocus="this.select()">
                    <div class="spacer-25"></div>
                </div>
            </fieldset>
        </div>
    </div>

</div>
