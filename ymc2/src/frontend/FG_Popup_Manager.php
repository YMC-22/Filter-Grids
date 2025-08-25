<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Popup_Manager Class
 * Handle popup requests
 *
 * @package YMCFilterGrids
 * @since 3.0.0
 */
class FG_Popup_Manager {
	protected static $used_grid_ids = [];

	/**
	 * Add grid ID to the list of active grids
	 */
	public static function add_grid_id($grid_id) : void {
		self::$used_grid_ids[] = $grid_id;

		add_filter('ymc_active_grid_ids', function($ids) {
			return array_unique(self::$used_grid_ids);
		});
	}

	/**
	 * Connect render of all popups in footer
	 */
	public static function maybe_render_in_footer($enabled = false) : void {
		if ($enabled && !has_action('wp_footer', [__CLASS__, 'render_all'])) {
			add_action('wp_footer', [__CLASS__, 'render_all']);
		}
	}

	/**
	 * Render all popups by collected IDs
	 */
	public static function render_all() : void {
		$grid_ids = apply_filters('ymc_active_grid_ids', array_unique(self::$used_grid_ids));

		foreach ($grid_ids as $grid_id) {
			ymc_render_single_popup($grid_id);
		}
	}

}