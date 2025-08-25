<?php declare( strict_types = 1 );

namespace YMCFilterGrids;

defined( 'ABSPATH' ) || exit;

/**
 * Class FG_Template
 * Include and render templates
 *
 * @package YMCFilterGrids
 * @since 3.0.0
 */
class FG_Template {
	public static function render($template_path, $data = []) : void {
		if (file_exists($template_path)) {
			extract($data);
			include($template_path);
		} else {
			wp_die("Template not found:" . esc_url($template_path));
		}
	}

}