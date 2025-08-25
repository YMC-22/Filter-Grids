<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

use YMCFilterGrids\FG_Data_Store as Data_Store;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Taxonomy Class
 * Get taxonomies and data attributes
 *
 * @since 3.0.0
 */

class FG_Taxonomy {

	/**
	 * @var string
	 */
	private static string $tax_background = '';

	/**
	 * @var string
	 */
	private static string $tax_color = '';

	/**
	 * @var string
	 */
	private static string $tax_label = '';


	/**
	 * @var string
	 */
	private static string $tax_status = '';


	/**
	 * Clear data attributes for taxonomy
	 *
	 * @return void
	 */
	private static function clear_data_attributes() : void {
		self::$tax_background = '';
		self::$tax_color = '';
		self::$tax_label = '';
		self::$tax_status = '';
	}


	/**
	 * Set data attributes for taxonomy
	 * Override default values
	 *
	 * @param string $name
	 * @param array $tax_attrs
	 *
	 * @return void
	 */
	private static function set_data_attributes(string $name, array &$tax_attrs) : void {
		if($tax_attrs) {
			foreach ($tax_attrs as $items) {
				if($name === $items['name']) {
					self::$tax_background = $items['background'];
					self::$tax_color = $items['color'];
					self::$tax_label = $items['label'];
					self::$tax_status = $items['status'];
					break;
				}
			}
		}
	}


	/**
	 * Sort taxonomies
	 *
	 * @param array $tax_sort
	 * @param array $all_tax
	 *
	 * @return void
	 */
	private static function sort_taxonomies(array $tax_sort, array &$all_tax) : void {
		if ($tax_sort) {
			$temp_array = [];
			foreach ($tax_sort as $slug) {
				if (isset($all_tax[$slug])) {
					$temp_array[$slug] = $all_tax[$slug];
				}
			}
			foreach ($all_tax as $slug => $label) {
				if (!isset($temp_array[$slug])) {
					$temp_array[$slug] = $label;
				}
			}
			$all_tax = $temp_array;
		}
	}


	/**
	 * Output HTML term item.
	 *
	 * @param int $post_id
	 * @param array $post_types
	 *
	 * @return string
	 */
	public static function output_html(int $post_id, array $post_types) : string {

		$all_tax = self::get_taxonomies($post_types);
		$selected_tax = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
		$tax_attrs = Data_Store::get_meta_value($post_id, 'ymc_fg_tax_attrs');
		$tax_sort = Data_Store::get_meta_value($post_id, 'ymc_fg_tax_sort');

		self::sort_taxonomies($tax_sort, $all_tax);

		ob_start();

		if($all_tax) {

			echo '<div class="taxonomies-list js-tax-insert js-tax-sortable">';

			foreach($all_tax as $name => $label) {
				self::set_data_attributes($name, $tax_attrs);

				$is_tax_sel = (in_array($name, $selected_tax)) ? 'checked' : '';

				self::$tax_label = (self::$tax_label) ? : $label;
				$class_status = (self::$tax_status) ? ' '. self::$tax_status : '';

				echo '<div class="taxonomies-list__item'. esc_attr($class_status).'"
					  data-tax-original-name="'. esc_attr($label) .'"
					  data-tax-name="'. esc_attr($name) .'"
					  data-tax-label="'. esc_attr(self::$tax_label).'"
					  data-tax-color="'. esc_attr(self::$tax_color).'"
					  data-tax-bg="'. esc_attr(self::$tax_background).'"
					  data-tax-status="'. esc_attr(self::$tax_status).'">
                      <i class="fa-solid fa-up-down-left-right icon-is-drag js-tax-handle"></i>
					  <input class="form-checkbox js-tax-checkbox" id="'. esc_attr($name) .'" data-label="'. esc_attr($label) .'" type="checkbox" name="ymc_fg_taxonomies[]" '. esc_attr($is_tax_sel) .' value="'.esc_attr($name).'">
					  <label class="field-label" for="'. esc_attr($name) .'">'. esc_html(self::$tax_label).'</label>
					  <i class="fa-solid fa-ellipsis-vertical icon-is-settings js-tax-settings"></i>
                     </div>';

				self::clear_data_attributes();
			}
			echo '</div>';

		} else {
			echo '<div class="taxonomies-list js-tax-insert js-tax-sortable">					 
					 <div class="notification notification--warning">'. esc_html__('No taxonomies found.', 'ymc-smart-filters') .'</div>
				  </div>';
		}

		return ob_get_clean();
	}


	/**
	 * Get all taxonomies
	 *
	 * @param array $post_types
	 *
	 * Key is slug and value is label of taxonomy
	 * @return array
	 */
	public static function get_taxonomies(array $post_types = []) : array {
		$result = [];
		$taxonomies = get_object_taxonomies($post_types, 'objects');
		if(!empty($taxonomies)) {
			foreach ($taxonomies as $tax) {
				$result[$tax->name] = $tax->label;
			}
		}
		asort($result);
		return $result;
	}

}