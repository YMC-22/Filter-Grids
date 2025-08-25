<?php

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">
	<div class="header"><?php echo esc_html($section_name); ?></div>
	<div class="body">
        <div class="headline js-headline-accordion" data-hash="license_updates">
            <span class="inner">
                <span class="dashicons dashicons-admin-network"></span>
                <span class="text"><?php esc_html_e('License Information', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>

        <div class="form-wrap license-updates-wrapper">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('License Key',
		                'Enter the license key from your purchase email to enable updates and Pro features.'); ?>
                    <input class="form-input" type="text" name="ymc_fg_license_key"
                           value="<?php //echo esc_attr($ymc_fg_custom_container_class); ?>">
                </div>

            </fieldset>

        </div>

    </div>
</div>