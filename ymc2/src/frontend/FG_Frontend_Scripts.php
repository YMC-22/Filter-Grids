<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Handle frontend scripts
 *
 * @since 3.0.0
 */

class FG_Frontend_Scripts {

	/**
	 * Hook in methods.
	 */
	public static function init() : void {
		add_action('wp_enqueue_scripts', array( __CLASS__, 'load_styles'));
		add_action('wp_print_scripts', array( __CLASS__, 'load_scripts'), 99999);
		add_action('wp_print_scripts', array( __CLASS__, 'localize_script'), 99999);
	}


	/**
	 * Register and enqueue a script for use.
	 *
	 * @uses   wp_enqueue_script()
	 * @param  string   $handle    Name of the script. Should be unique.
	 * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param  string[] $deps      An array of registered script handles this script depends on.
	 * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 */
	private static function enqueue_script( $handle, $path, $deps, $version, $in_footer = array( 'strategy' => 'defer' ) ) : void {
		wp_enqueue_script( $handle, $path, $deps, $version, $in_footer );
		add_filter('script_loader_tag', function($tag, $handle, $src) {
			if ( 'ymc_script' === $handle ) {
				$tag = "<script id='{$handle}-js' type='module' src='". esc_url($src) ."'></script>";
			}
			if ( 'ymc_api' === $handle ) {
				$tag = "<script id='{$handle}-js' type='module' src='". esc_url($src) ."'></script>";
			}
			return $tag;
		}, 10, 3);
	}


	/**
	 * Register and enqueue a styles for use.
	 *
	 * @uses   wp_enqueue_style()
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 */
	private static function enqueue_style( $handle, $path, $deps, $version, $media = 'all' ) : void {
		wp_enqueue_style( $handle, $path, $deps, $version, $media );
	}


	/**
	 * Register all Filter Grids scripts.
	 */
	private static function register_scripts() : void {
		$suffix = '.min';
		//$suffix = '';
		$version = YMC_VERSION;

		$register_scripts = array(
			'ymc_handlebar' => array(
				'src'       => YMC_PLUGIN_URL . 'assets/js/lib/handlebars.min-v4.7.8.js',
				'deps'      => array(),
				'version'   => $version,
				'in_footer' => false
			),
		 	'ymc_script'    => array(
				'src'     => YMC_PLUGIN_URL . 'assets/js/frontend/main'. $suffix .'.js',
				'deps'    => array('jquery', 'wp-hooks'),
				'version' => $version,
				'in_footer' => true
			)
		);

		if ('yes' === get_option('ymc_fg_enable_js_filter_api')) {
			$register_scripts['ymc_api'] = array(
				'src'       => YMC_PLUGIN_URL . 'assets/js/frontend/rest/YMCFilterGrid' . $suffix . '.js',
				'deps'      => array('jquery', 'wp-hooks'),
				'version'   => $version,
				'in_footer' => true
			);
		}

		if ('yes' === get_option('ymc_fg_enable_js_masonry')) {
			$register_scripts['ymc_masonry'] = array(
				'src'       => YMC_PLUGIN_URL . 'assets/js/lib/masonry.min.js',
				'deps'      => array('jquery', 'wp-hooks'),
				'version'   => $version,
				'in_footer' => false
			);
		}

		foreach ($register_scripts as $name => $props) {
			self::enqueue_script($name, $props['src'], $props['deps'], $props['version'], $props['in_footer']);
		}
	}


	/**
	 * Register all Filter Grids styles.
	 */
	private static function register_styles() : void {
		$suffix = '.min';
		//$suffix = '';
		$version = YMC_VERSION;

		$register_styles = array(
			'query_ui'    => array(
				'src'     => YMC_PLUGIN_URL . 'assets/css/lib/query-ui.css',
				'deps'    => array(),
				'version' => $version
			),
			'ymc_style'   => array(
				'src'     => YMC_PLUGIN_URL .  'assets/css/style'. $suffix .'.css',
				'deps'    => array(),
				'version' => $version
			)
		);
		foreach ( $register_styles as $name => $props ) {
			self::enqueue_style( $name, $props['src'], $props['deps'], $props['version'], 'all');
		}
	}


	/**
	 * Register/queue frontend styles.
	 */
	public static function load_styles() : void {
		self::register_styles();
	}


	/**
	 * Register/queue frontend scripts.
	 */
	public static function load_scripts() : void {
		wp_enqueue_script( 'jquery-ui-datepicker');
		self::register_scripts();
	}


	/**
	 * Localize a FG script once.
	 * @since 3.0.0
	 */
	public static function localize_script() : void {
		if ( wp_script_is( 'ymc_script' ) ) {
			 wp_localize_script( 'ymc_script', '_ymc_fg_object',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'getPosts_nonce' => wp_create_nonce('get_filtered_posts-ajax-nonce'),
					'getPostToPopup_nonce' => wp_create_nonce('get_post_to_popup-ajax-nonce'),
					'getAutocompletePosts_nonce' => wp_create_nonce('get_autocomplete_posts-ajax-nonce'),
					'current_page'   => 1,
					'path'           => YMC_PLUGIN_URL
			));
		}
	}


}