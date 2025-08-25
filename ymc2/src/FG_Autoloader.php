<?php declare( strict_types = 1 );

namespace YMCFilterGrids;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Autoloader class.
 * Implementations of PSR-4
 *
 * @since 3.0.0
 */
class FG_Autoloader {

	public static function init() : void {

		spl_autoload_register( function ( $class ) {

			$prefix = 'YMCFilterGrids';
			$base_dir = __DIR__;

			$len = strlen( $prefix );
			if ( strncmp( $prefix, $class, $len ) !== 0 ) {
				return;
			}

			$relative_class = substr( $class, $len );
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			if ( file_exists( $file ) ) {
				require $file;
			}

		});
	}
}




