<?php declare(strict_types=1);

/**
 * Main YMC_Filter_Grids Class.
 * YMC_Filter_Grids setup
 *
 * @class YMC_Filter_Grids
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;

use YMCFilterGrids\FG_Autoloader;
use YMCFilterGrids\admin\{FG_Backend_Scripts, FG_Post_Type, FG_Meta_Boxes, FG_Save_Meta_Boxes, FG_Ajax_Admin, FG_License_Manager, FG_General_Settings};
use YMCFilterGrids\frontend\{FG_Frontend_Scripts, FG_Shortcodes, FG_Ajax_Responder};

/**
 * YMC_Filter_Grids Class
 *
 * @since 3.0.0
 */
final class YMC_Filter_Grids {

	/**
	 * YMC_Filter_Grids version.
	 *
	 * @var string
	 */
	public string $version = '3.0.0';


	/**
	 * @var string The plugin domain
	 */
	public string $domain = 'ymc-smart-filters';

	/**
	 * The single instance of the class.
	 *
	 * @var YMC_Filter_Grids
	 * @since 3.0.0
	 */
	protected static ?YMC_Filter_Grids $instance = null;


	/**
	 * YMC_Filter_Grids Constructor
	 *
	 * @since 3.0.0
	 * @access private
	 */
	private function __construct() {
		add_action( 'plugins_loaded', [$this, 'init']);
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 * @since 3.0.0
	 */
	public function init() {
		$this->define_constants();
		$this->includes();
	}


	/**
	 * Cloning is forbidden.
	 *
	 * @since 3.0.0
	 */
	 public function __clone() {
		wp_die('Cloning is forbidden.');
	}


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 3.0.0
	 */
	 public function __wakeup() {
		wp_die('Unserializing instances of this class is forbidden.');
	}


	/**
	 * Main YMC_Filter_Grids Instance.
	 *
	 * Ensures only one instance of YMC_Filter_Grids is loaded or can be loaded.
	 *
	 * @since 3.0.0
	 * @static
	 * @return YMC_Filter_Grids - Main instance.
	 */
	public static function instance() : YMC_Filter_Grids {
		if (is_null( self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Check if legacy plugin.
	 *
	 * Which system should include: new or legacy
	 *
	 * @since 3.0.0
	 * @return string yes or no
	 */
	public static function is_legacy() : string {
		$option = 'ymc_plugin_legacy_is';
		if( false === get_option($option)) {
			update_option($option, 'yes', false);
		}
		return get_option($option);
	}


	/**
	 * Define FG Constants.
	 */
	public function define_constants() : void {
		$this->define( 'YMC_ABSPATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'YMC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'YMC_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
		$this->define( 'YMC_VERSION', $this->version );
		$this->define( 'YMC_DOMAIN', $this->domain );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string $name  Constant name.
	 * @param string $value Constant value.
	 */
	public function define( string $name, string $value) : void {
		if (!defined( $name )) {
			define( $name, $value );
		}
	}


	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, frontend.
	 *
	 * @return bool
	 */
	private function is_request(string $type) : bool {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'frontend' :
				return ! is_admin();
			default :
				 wp_die('Unknown request type.');
		}
	}



	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() : void {

		/**
		 * Autoloader loads all the classes needed to run the plugin.
		 */
		require_once YMC_ABSPATH . 'src/FG_Autoloader.php';
		FG_Autoloader::init();

		/**
		 * Interfaces.
		 */
		require_once YMC_ABSPATH . 'src/interfaces/IFilter.php';

		/**
		 * Abstract classes.
		 */
		require_once YMC_ABSPATH . 'src/abstracts/FG_Abstract_Filter.php';

		/**
		 * Functions.
		 */
		require_once YMC_ABSPATH . 'src/functions/fg-core-functions.php';

		/**
		 * Ajax classes.
		 */
		FG_Ajax_Responder::init();
		FG_Ajax_Admin::init();

		/**
		 * Core classes.
		 */
		if($this->is_request( 'frontend')) {
			FG_Frontend_Scripts::init();
			FG_Shortcodes::init();
		}
		if($this->is_request( 'admin')) {
			FG_Backend_Scripts::init();
			FG_Post_Type::init();
			FG_Meta_Boxes::init();
			FG_Save_Meta_Boxes::init();
			FG_General_Settings::init();
			FG_License_Manager::init();
		}

	}

}


