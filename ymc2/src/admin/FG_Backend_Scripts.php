<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

defined( 'ABSPATH' ) || exit;

/**
 * Backend Scripts Class
 *
 * @since 3.0.0
 */
class FG_Backend_Scripts {

	/**
	 * Hook in methods.
	 */
	public static function init() : void {
		add_action('admin_enqueue_scripts', array( __CLASS__, 'load_scripts' ));
		add_action('admin_print_scripts', array( __CLASS__, 'localize_script' ));
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
			'ymc_color-picker-alpha' => array(
				'src'       => YMC_PLUGIN_URL . 'assets/js/lib/wp-color-picker-alpha.min.js',
				'deps'      => array('jquery', 'wp-color-picker'),
				'version'   => $version,
				'in_footer' => true
			),
			'ymc_script'      => array(
				'src'       => YMC_PLUGIN_URL . 'assets/js/admin/main'. $suffix .'.js',
				'deps'      => array('jquery', 'jquery-ui-tooltip', 'wp-hooks'),
				'version'   => $version,
				'in_footer' => true
			)
		);
		foreach ( $register_scripts as $name => $props ) {
			self::enqueue_script( $name, $props['src'], $props['deps'], $props['version'], $props['in_footer'] );
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
			'ymc_style'    => array(
				'src'     => YMC_PLUGIN_URL . 'assets/css/admin'. $suffix .'.css',
				'deps'    => array(),
				'version' => $version
			)
		);
		foreach ( $register_styles as $name => $props ) {
			self::enqueue_style( $name, $props['src'], $props['deps'], $props['version'], 'all');
		}
	}


	/**
	 * Register/queue backend scripts.
	 */
	public static function load_scripts() : void {
		$screen = get_current_screen();

		if ( $screen->id === 'ymc_filters' ) {
			$settings_css = wp_enqueue_code_editor(array(
				'type'       => 'text/css',
				'codemirror' => array(
					'indentUnit' => 2,
					'tabSize'    => 2,
					'placeholder' => "Code CSS...",
					'lineNumbers'    => true,
					'lineWrapping'   => true
				)
			));
			$settings_js = wp_enqueue_code_editor([
				'type'       => 'text/javascript',
				'codemirror' => [
					'indentUnit'     => 2,
					'tabSize'        => 2,
					'placeholder'    => "Code JS...",
					'lineNumbers'    => true,
					'lineWrapping'   => true
				]
			]);

			wp_enqueue_script( 'wp-codemirror' );
			wp_enqueue_script( 'code-editor' );
			wp_enqueue_style( 'code-editor' );
			wp_add_inline_script(
				'code-editor',
				'window.ymcEditors = {};
				jQuery(function () {
				    if (typeof wp !== "undefined" && wp.codeEditor) {
				        const cssEditor = wp.codeEditor.initialize("ymc-fg-custom-css", ' . wp_json_encode($settings_css) . ');
				        const jsEditor = wp.codeEditor.initialize("ymc-fg-custom-js", ' . wp_json_encode($settings_js) . ');				
				        window.ymcEditors["css"] = cssEditor.codemirror;
				        window.ymcEditors["js"] = jsEditor.codemirror;
				    }
				});'
			);

			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script( 'wp-color-picker');

			self::register_scripts();
			self::register_styles();
		}
		if( $screen->id === 'edit-ymc_filters' || $screen->id === 'ymc_filters_page_ymc-license' || $screen->id === 'ymc_filters_page_ymc-settings' ) {
			self::register_styles();
		}
	}


	/**
	 * Localize a FG script once.
	 *
	 * @since 3.0.0
	 * @param string $handle Script handle the data will be attached to.
	 */
	public static function localize_script() : void {

		if ( wp_script_is( 'ymc_script' ) ) {
			 wp_localize_script( 'ymc_script', '_ymc_fg_object',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'getTaxAjax_nonce' => wp_create_nonce('get-taxonomy-ajax-nonce'),
					'getTermAjax_nonce' => wp_create_nonce('get-term-ajax-nonce'),
					'removeTermsAjax_nonce' => wp_create_nonce('remove-terms-ajax-nonce'),
					'updatedTaxAjax_nonce' => wp_create_nonce('updated-tax-ajax-nonce'),
					'sortTaxAjax_nonce' => wp_create_nonce('sort-tax-ajax-nonce'),
					'sortTermAjax_nonce' => wp_create_nonce('sort-term-ajax-nonce'),
					'selectPostsAjax_nonce' => wp_create_nonce('select-posts-ajax-nonce'),
					'searchFeedPostsAjax_nonce' => wp_create_nonce('search-feed-posts-ajax-nonce'),
					'saveTaxAttrAjax_nonce' => wp_create_nonce('save-taxonomy-attr-ajax-nonce'),
					'saveTermAttrAjax_nonce' => wp_create_nonce('save-term-attr-ajax-nonce'),
					'getSelectTaxAjax_nonce' => wp_create_nonce('get-select-tax-ajax-nonce'),
					'uploadTermIconAjax_nonce' => wp_create_nonce('upload-term-icon-ajax-nonce'),
					'exportSettingsAjax_nonce' => wp_create_nonce('export-settings-ajax-nonce'),
					'importSettingsAjax_nonce' => wp_create_nonce('import-settings-ajax-nonce'),
					'loadedFeedPosts_page' => 2,
					'path' => YMC_PLUGIN_URL
			));
		}
	}

}