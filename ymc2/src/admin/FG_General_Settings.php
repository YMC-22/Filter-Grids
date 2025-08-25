<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

defined( 'ABSPATH' ) || exit;


/**
 * Class FG_General_Settings
 *
 * @since 3.0.0
 */
class FG_General_Settings {
	public static function init() : void {
		add_action('admin_menu', [__CLASS__, 'add_settings_menu']);

		add_action('admin_notices', [__CLASS__, 'admin_notices']);

		add_action('admin_post_plugin_settings_save', [__CLASS__, 'plugin_settings']);
	}

	public static function plugin_settings(): void {
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('You are not allowed to do this.', 'ymc-smart-filters'));
		}
		check_admin_referer('plugin_settings_save');

		// Legacy Plugin
        if(!empty($_POST['ymc_plugin_legacy_is'])) {
            $legacy_plugin = sanitize_text_field(wp_unslash($_POST['ymc_plugin_legacy_is']));
            update_option( 'ymc_plugin_legacy_is', $legacy_plugin, false );
	        wp_safe_redirect(admin_url('edit.php?post_type=ymc_filters'));
	        exit;
        }

		// JavaScript Filter API
		$enable_js_filter_api = isset($_POST['ymc_fg_enable_js_filter_api'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_enable_js_filter_api'])) : 'no';
		update_option( 'ymc_fg_enable_js_filter_api', $enable_js_filter_api, false );

		wp_safe_redirect(
			add_query_arg('fg_update', 'done', admin_url('edit.php?post_type=ymc_filters&page=ymc-settings'))
		);

        exit;
	}

	public static function admin_notices(): void {
		if (!current_user_can('manage_options')) return;

		$screen = get_current_screen();
		if (!$screen || $screen->id !== 'ymc_filters_page_ymc-settings') return;

		if (isset($_GET['fg_update'])) {
            if ($_GET['fg_update'] === 'done') {
                echo '<div class="notice notice-success is-dismissible"><p>' .
                      esc_html__('Settings updated successfully.', 'ymc-smart-filters') . '</p></div>';
            }
		}
		return;
	}

	public static function add_settings_menu(): void {
		add_submenu_page(
			'edit.php?post_type=ymc_filters',
			'Settings',
			'Settings',
			'manage_options',
			'ymc-settings',
			[__CLASS__, 'settings_html']
		);
	}

	public static function settings_html(): void {
		if (!current_user_can('manage_options')) return;

	?>
		<div class="wrap settings-page">
            <div class="settings-inner">
                <h2 class="page-title-settings">
                <span class="dashicons dashicons-admin-generic"></span>
                <?php esc_html_e('General Settings', 'ymc-smart-filters'); ?></h2>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
		            <?php wp_nonce_field('plugin_settings_save'); ?>
                    <input type="hidden" name="action" value="plugin_settings_save">
                    <div class="form-group">
                        <div class="sub-headline"><?php esc_html_e('Legacy Mode', 'ymc-smart-filters'); ?></div>
                        <div class="field-description">
	                        <?php esc_html_e('Activates the legacy version of the plugin for compatibility with your theme or other plugins.
                            Not recommended for new sites.', 'ymc-smart-filters'); ?>
                        </div>
                        <input class="form-checkbox" name="ymc_plugin_legacy_is" id="ymc_plugin_legacy_is" type="checkbox" value="yes">
                        <label class="form-label" for="ymc_plugin_legacy_is">
                            <?php esc_html_e('Enable Legacy Version', 'ymc-smart-filters'); ?></label>
                    </div>

                    <div class="form-group">
                        <div class="sub-headline"><?php esc_html_e('JavaScript Filter API', 'ymc-smart-filters'); ?></div>
                        <div class="field-description">
		                    <?php esc_html_e('Enable dynamic post filtering using JavaScript. 
		                    Enable dynamic post filtering using JavaScript. When enabled, the filter grid will update posts 
		                    asynchronously without reloading the page.', 'ymc-smart-filters'); ?>
                        </div>
                        <input class="form-checkbox" type="checkbox"
                               name="ymc_fg_enable_js_filter_api"
                               id="ymc_fg_enable_js_filter_api"
                               value="yes" <?php checked(get_option('ymc_fg_enable_js_filter_api'), 'yes'); ?>>
                        <label class="form-label" for="ymc_fg_enable_js_filter_api">
		                    <?php esc_html_e('Enable JavaScript Filter API', 'ymc-smart-filters'); ?></label>
                    </div>

                    <div class="spacer-15"></div>

	                <?php submit_button('Save settings','button button--primary'); ?>
                </form>
            </div>
        </div>

	<?php
	}

}


