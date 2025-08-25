<?php

if (!defined( 'ABSPATH')) exit;

?>

<div class="inner">

	<div class="header"><?php echo esc_html($section_name); ?></div>
	<div class="body">
        <div class="headline js-headline-accordion" data-hash="export_data">
            <span class="inner">
                <i class="fas fa-file-export"></i>
                <span class="text"><?php esc_html_e('Export Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap export-wrapper">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Export Settings',
                        'Export all plugin settings as a JSON file for backup or migration. <br>
                               <b>IMPORTANT!</b> Custom JS code and CSS will not be exported to the file.'); ?>

                    <div class="notify-wrapper"></div>
                    <div class="spacer-25"></div>
                    <button class="button button--primary js-button-export-settings" type="button">
                        <?php esc_html_e('Export As JSON', 'ymc-smart-filters'); ?></button>

                </div>
            </fieldset>
        </div>

        <div class="headline js-headline-accordion" data-hash="import_data">
            <span class="inner">
                <i class="fa-solid fa-upload"></i>
                <span class="text"><?php esc_html_e('Import Settings', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap import-wrapper">
            <fieldset class="form-group">
                <div class="group-elements">
	                <?php ymc_render_field_header('Import Settings',
		                'Import plugin settings from a previously exported JSON file. <br>
                        <b>IMPORTANT!</b> Before importing data, make sure that you already have Post Types and Taxonomies that 
                        were exported from other filters.'); ?>
                    <div class="spacer-25"></div>

                    <div class="notify-wrapper"></div>
                    <div class="spacer-25"></div>

                    <div class="import-settings-wrap">
                        <input class="input-file js-file-import-settings" type="file" id="file-import-settings" accept=".json">
                        <label class="label-file" for="file-import-settings">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                            <span><?php esc_html_e('Import JSON', 'ymc-smart-filters'); ?></span>
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

</div>