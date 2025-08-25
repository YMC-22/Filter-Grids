<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

defined( 'ABSPATH' ) || exit;


/**
 * FG_License_Manager Class
 * Handle license requests
 *
 * @since 3.0.0
 */

class FG_License_Manager {

	const CHECK_URL  = 'https://filters/wp-json/license/v1/check'; // change domain URL on production
	const UPDATE_URL = 'https://filters/wp-json/license/v1/update'; // change domain URL on production

	public static function init() : void {
		add_action('admin_init', [__CLASS__, 'register_options']);

		add_action('admin_menu', [__CLASS__, 'add_license_menu']);

		add_action('admin_notices', [__CLASS__, 'admin_notices']);

		add_action('fg_license_cron_check', [__CLASS__, 'cron_check']);

		add_filter('pre_set_site_transient_update_plugins', [__CLASS__, 'check_for_plugin_update']);

		add_filter('plugins_api', [__CLASS__, 'plugin_info'], 10, 3);

		add_filter('upgrader_pre_download', [__CLASS__, 'verify_package_hash'], 10, 4);

		add_action('admin_post_fg_check_update', [__CLASS__, 'manual_check_update']);

		add_action('admin_post_fg_save_license', [__CLASS__, 'handle_license_form']);
	}

	/**
	 * Check the archive hash before installation
	 */
	public static function verify_package_hash($reply, $package, $upgrader, $hook_extra) {

		$plugin_slug = 'ymc-smart-filters/ymc-smart-filters.php';
		$updates = get_site_transient('update_plugins');

		if (!empty($updates->response[$plugin_slug]->package_hash)) {
			$expected_hash = $updates->response[$plugin_slug]->package_hash;

			$tmp = download_url($package);
			if (is_wp_error($tmp)) {
				return $tmp;
			}

			$real_hash = hash_file('sha256', $tmp);

			if ($real_hash !== $expected_hash) {
				@unlink($tmp);
				return new \WP_Error(
					'fg_package_hash_mismatch',
					__('Update failed: package integrity check failed (hash mismatch).', 'ymc-smart-filters')
				);
			}

			return $tmp;
		}

		return $reply;
	}

	private static function api_key() : string {
		$license_key = get_option('fg_license_key', '');
		$site_url    = home_url();

		if (!$license_key) return '';

		$payload   = $license_key . '|' . $site_url;
		$signature = hash_hmac('sha256', $payload, $license_key);

		return $signature;
	}

	public static function register_options(): void {
		register_setting('fg_license_options', 'fg_license_key');
		register_setting('fg_license_options', 'fg_license_status');
		register_setting('fg_license_options', 'fg_license_expires_at');
		register_setting('fg_license_options', 'fg_license_message');
	}

	public static function add_license_menu(): void {
		add_submenu_page(
			'edit.php?post_type=ymc_filters',
			 'License & Updates',
			 'Updates',
			 'manage_options',
			 'ymc-license',
			[__CLASS__, 'license_page_html']
		);
	}

	public static function manual_check_update(): void {
		if (! current_user_can('update_plugins')) {
			wp_die(esc_html__('Insufficient rights to update.', 'ymc-smart-filters'));
		}

		check_admin_referer('fg_check_update');

		delete_site_transient('update_plugins');
		wp_update_plugins();
		$transient = get_site_transient('update_plugins');

		$plugin_slug = 'ymc-smart-filters/ymc-smart-filters.php';

		if ( isset($transient->response[$plugin_slug]) ) {
			$url = self_admin_url("plugins.php?plugin_status=upgrade");
			wp_safe_redirect($url);
			exit;
		} else {
			wp_safe_redirect(
				add_query_arg(
					'fg_update',
					'none',
					admin_url('edit.php?post_type=ymc_filters&page=ymc-license')
				)
			);
			exit;
		}
	}

	public static function handle_license_form(): void {
		if (!current_user_can('manage_options')) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'ymc-smart-filters' ));
		}

		check_admin_referer('fg_license_save');

		update_option('fg_license_key', sanitize_text_field($_POST['fg_license_key']));
		self::license_check(true);

		// Redirect back to license page
		wp_safe_redirect(
			add_query_arg(
				'fg_license',
				get_option('fg_license_status', 'invalid'),
				admin_url('edit.php?post_type=ymc_filters&page=ymc-license')
			)
		);
		exit;
	}

	public static function license_page_html(): void {
		if (!current_user_can('manage_options')) return;

		if (isset($_POST['fg_license_key'])) {
			check_admin_referer('fg_license_save');
			update_option('fg_license_key', sanitize_text_field($_POST['fg_license_key']));
			self::license_check(true);
			echo '<div class="updated"><p>'. esc_html__('The key has been saved and verified.', 'ymc-smart-filters') .'</p></div>';
		}

		$key     = get_option('fg_license_key', '');
		$status  = get_option('fg_license_status', 'not_checked');
		$msg     = get_option('fg_license_message', '');
		$expires = get_option('fg_license_expires_at', '');

		$current_version = get_plugin_data(WP_PLUGIN_DIR . '/ymc-smart-filters/ymc-smart-filters.php', false, false)['Version'];
		$latest_version  = get_option('fg_latest_version', $current_version);

		?>

		<div class="wrap license-updates-page">
            <div class="license-updates-inner">
                <h2 class="page-title-license-updates">
                    <span class="dashicons dashicons-admin-network"></span>
                    <?php esc_html_e('License Information', 'ymc-smart-filters'); ?></h2>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
		            <?php wp_nonce_field('fg_license_save'); ?>
                    <input type="hidden" name="action" value="fg_save_license">
                    <div class="form-group">
                        <label class="form-label"><?php esc_html_e('License key:', 'ymc-smart-filters'); ?></label>
                        <input class="form-input" type="password" id="fg_license_key" name="fg_license_key" value="<?php echo esc_attr($key); ?>">
                        <p>
                            <input class="form-checkbox" id="fg_show_license_key" type="checkbox" onclick="toggleLicenseKey();">
                            <label class="field-label" for="fg_show_license_key"><?php esc_html_e('Show license key', 'ymc-smart-filters'); ?></label>
                        </p>
                    </div>
		            <?php submit_button('Check and save','button button--primary'); ?>
                </form>
                <script>
                    function toggleLicenseKey() {
                        const field = document.getElementById('fg_license_key');
                        field.type = field.type === 'password' ? 'text' : 'password';
                    }
                </script>
                <hr/>
                <div class="row">
                    <strong><?php esc_html_e('License status:', 'ymc-smart-filters'); ?></strong>
                    <span class="status-license--<?php echo esc_attr($status); ?>"><?php echo esc_html($status); ?></span>
                </div>
	            <?php if ($expires) : ?>
                    <div class="row">
                        <strong><?php esc_html_e('Valid until:', 'ymc-smart-filters'); ?></strong>
                        <span><?php echo esc_html($expires); ?></span></div>
	            <?php endif; ?>
	            <?php if ($msg) : ?>
                    <div class="row">
                        <strong><?php esc_html_e('Message:', 'ymc-smart-filters'); ?></strong>
                        <span><?php echo esc_html($msg); ?></span></div>
	            <?php endif; ?>
            </div>
            <div class="license-updates-inner">
                <h2 class="page-title-license-updates">
                    <span class="dashicons dashicons-info-outline"></span>
                    <?php esc_html_e('Update Information', 'ymc-smart-filters'); ?></h2>
                <div class="row">
                    <strong><?php esc_html_e('Current Version:', 'ymc-smart-filters'); ?></strong>
                    <span><?php echo esc_html($current_version); ?></span>
                </div>
                <div class="row">
                    <strong><?php esc_html_e('Latest Version:', 'ymc-smart-filters'); ?></strong>
                    <span><?php echo esc_html($latest_version); ?></span>
                </div>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
	                <?php wp_nonce_field('fg_check_update'); ?>
                    <input type="hidden" name="action" value="fg_check_update">
		            <?php submit_button(__('Check For Updates', 'ymc-smart-filters'), 'button button--secondary'); ?>
                </form>
            </div>
        </div>

		<?php
	}

	public static function license_check($force = false): bool {
		$key = get_option('fg_license_key', '');
		if (!$key) {
			update_option('fg_license_status', 'not_set');
			update_option('fg_license_message', 'Key not set');
			return false;
		}

		$last = get_transient('fg_license_last_check');
		if (!$force && $last && (time() - (int)$last) < 6 * HOUR_IN_SECONDS) {
			return get_option('fg_license_status') === 'valid';
		}

		$args = [
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => [
				'license_key' => $key,
				'site_url'    => home_url(),
				'api_key'     => self::api_key(),
			],
		];

		$response = wp_remote_post(self::CHECK_URL, $args);

		if (is_wp_error($response)) {
			update_option('fg_license_status', 'error');
			update_option('fg_license_message', 'Error connecting to license server');
			set_transient('fg_license_last_check', time(), 3 * HOUR_IN_SECONDS);
			return false;
		}

		$data = json_decode(wp_remote_retrieve_body($response), true);

		$status  = $data['status']  ?? 'unknown';
		$message = $data['message'] ?? '';
		$expires = $data['expires_at'] ?? '';

		update_option('fg_license_status', $status);
		update_option('fg_license_message', $message);
		update_option('fg_license_expires_at', $expires);

		set_transient('fg_license_last_check', time(), 6 * HOUR_IN_SECONDS);

		return $status === 'valid';
	}

	public static function cron_check(): void {
		self::license_check(false);
	}

	public static function admin_notices(): void {
		if (!current_user_can('manage_options')) return;
		$screen = get_current_screen();
		if (!$screen || $screen->id !== 'ymc_filters_page_ymc-license') return;

		// Update notifications
		if (isset($_GET['fg_update'])) {
			if ($_GET['fg_update'] === 'none') {
				echo '<div class="notice notice-info is-dismissible"><p>' .
				     esc_html__('No updates available. You are using the latest version.', 'ymc-smart-filters') .
				     '</p></div>';
			} elseif ($_GET['fg_update'] === 'done') {
				echo '<div class="notice notice-success is-dismissible"><p>' .
				     esc_html__('Plugin updated successfully.', 'ymc-smart-filters') .
				     '</p></div>';
			} elseif ($_GET['fg_update'] === 'error') {
				echo '<div class="notice notice-error is-dismissible"><p>' .
				     esc_html__('Failed to update plugin.', 'ymc-smart-filters') .
				     '</p></div>';
			}

			return;
		}

		// License Notifications (One-Time)
		if (isset($_GET['fg_license'])) {
			if ($_GET['fg_license'] === 'valid') {
				echo '<div class="notice notice-success is-dismissible"><p>' .
				     esc_html__('License successfully activated.', 'ymc-smart-filters') .
				     '</p></div>';
			} elseif ($_GET['fg_license'] === 'expired') {
				echo '<div class="notice notice-warning is-dismissible"><p>' .
				     esc_html__('Subscription has expired. Updates are not available.', 'ymc-smart-filters') .
				     '</p></div>';
			} elseif ($_GET['fg_license'] === 'invalid') {
				echo '<div class="notice notice-error is-dismissible"><p>' .
				     esc_html__('Invalid or blocked key.', 'ymc-smart-filters') .
				     '</p></div>';
			} elseif ($_GET['fg_license'] === 'error') {
				echo '<div class="notice notice-error is-dismissible"><p>' .
				     esc_html__('Failed to connect to license server.', 'ymc-smart-filters') .
				     '</p></div>';
			}

            return;
		}
	}

	public static function check_for_plugin_update($transient) : object {
		if (empty($transient->checked)) return $transient;

		$license_key = get_option('fg_license_key', '');
		if (!$license_key) return $transient;

		$plugin_slug = 'ymc-smart-filters/ymc-smart-filters.php';
		$current_ver = $transient->checked[$plugin_slug] ?? '0.0.0';

		$response = wp_remote_post(self::UPDATE_URL, [
			'body' => [
				'api_key'     => self::api_key(),
				'license_key' => $license_key,
				'site_url'    => home_url(),
				'version'     => $current_ver
			],
			'sslverify' => false
		]);

		if (is_wp_error($response)) return $transient;

		$data = json_decode(wp_remote_retrieve_body($response), true);

		if (!empty($data['new_version'])) {
			update_option('fg_latest_version', $data['new_version']);
		}

		if (!empty($data['status']) && $data['status'] === 'update_available') {
			$obj = new \stdClass();
			$obj->slug = 'ymc-smart-filters';
			$obj->plugin = $plugin_slug;
			$obj->new_version = $data['new_version'];
			$obj->package = $data['package'];

			if (!empty($data['package_hash'])) {
				$obj->package_hash = $data['package_hash'];
			}

			$obj->tested = $data['tested'] ?? '';
			$obj->requires = $data['requires'] ?? '';
			$obj->last_updated = $data['last_updated'] ?? '';
			$obj->sections = $data['sections'] ?? [];

			$transient->response[$plugin_slug] = $obj;
		}

		return $transient;
	}

	public static function plugin_info($res, $action, $args) {
		if ($action !== 'plugin_information') return $res;
		if ($args->slug !== 'ymc-smart-filters') return $res;

		$license_key = get_option('fg_license_key', '');
		if (!$license_key) return $res;

		$response = wp_remote_post(self::UPDATE_URL, [
			'body' => [
				'api_key'     => self::api_key(),
				'license_key' => $license_key,
				'site_url'    => home_url(),
				'version'     => '0.0.0'
			],
			'sslverify' => false
		]);

		if (is_wp_error($response)) return $res;

		$data = json_decode(wp_remote_retrieve_body($response), true);

		if (!empty($data['status']) && $data['status'] === 'update_available') {
			$res = (object) $data;
		}

		return $res;
	}

}


