<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

use YMCFilterGrids\admin\FG_Taxonomy as Taxonomy;
use YMCFilterGrids\FG_Data_Store as Data_Store;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Term Class
 * Get terms and data attributes
 *
 * @since 3.0.0
 */

class FG_Term {

	/**
	 * Class icon
	 * Icon associated with the term.
	 * Value for data attribute is class name by font awesome. Ex.: "fa-solid fa-image".
	 * @var string
	 */
	private static string $icon_class = '';

	/**
	 * Icon alignment
	 * Value for data attribute is "left" or "right".
	 * @var string
	 */
	private static string $icon_alignment = '';

	/**
	 * Icon color
	 * Value for data attribute is color in hash code. Ex.: "#ff0000".
	 * @var string
	 */
	private static string $icon_color = '';


	/**
	 * Icon URL
	 * The attachment_id value for the loaded term icon
	 * @var string
	 */
	private static string $icon_url = '';


	/**
	 * Term background
	 * Value for data attribute is background color in hash code. Ex.: "#ff0000".
	 * @var string
	 */
	private static string $term_background = '';

	/**
	 * Term color
	 * Value for data attribute is color in hash code. Ex.: "#ff0000".
	 * @var string
	 */
	private static string $term_color = '';

	/**
	 * Class term
	 * Value for data attribute is class name. Ex.: "my-class".
	 * @var string
	 */
	private static string $term_class = '';

	/**
	 * Term default
	 * Value for data attribute is "true" or "false".
	 * Determines whether the term will be used by default.
	 * @var string
	 */
	private static string $term_default = '';

	/**
	 * Term hide
	 * Value for data attribute is "true" or "false".
	 * Determines whether the term will be visible.
	 * @var string
	 */
	private static string $term_visible = '';

	/**
	 * Term name
	 * Value for data attribute is term name.
	 * @var string
	 */
	private static string $term_name = '';


	/**
	 * Term checked
	 * Value for data attribute is "true" or "false".
	 * Determines whether the term will be selected.
	 * @var string
	 */
	private static string $term_checked = '';


	/**
	 * Status term
	 * Value for data attribute is "changed" or "".
	 * Determines whether changes have been made to the term settings.
	 * @var string
	 */
	private static string $term_status = '';


	/**
	 * Taxonomy label
	 * @var string
	 */
	private static string $tax_label = '';


	/**
	 * Cache key
	 * @var string
	 */
	private static string $cache_key = 'ymc_fg_term_post_counts';


	/**
	 * Get cache key
	 * @return string
	 */
	public static function get_cache_key() : string {
		return self::$cache_key;
	}


	/**
	 * Clear data attributes for term
	 *
	 * @return void
	 */
	private static function clear_data_attributes() : void {
		self::$icon_class = '';
		self::$icon_alignment = '';
		self::$icon_color = '';
		self::$icon_url = '';
		self::$term_background = '';
		self::$term_color = '';
		self::$term_class = '';
		self::$term_default = 'false';
		self::$term_visible = 'true';
		self::$term_name = '';
		self::$term_checked = 'false';
		self::$term_status = '';
	}


	/**
	 * Set data attributes for term
	 * Override default values
	 *
	 * @param int $term_id
	 * @param array $term_attrs
	 *
	 * @return void
	 */
	private static function set_data_attributes(int $term_id, array &$term_attrs) : void {
		if($term_attrs) {
			foreach ($term_attrs as $items) {
				if($term_id === (int) $items['term_id']) {
					self::$icon_class      = $items['icon_class'];
					self::$icon_alignment  = $items['icon_alignment'];
					self::$icon_color      = $items['icon_color'];
					self::$icon_url        = $items['icon_url'];
					self::$term_background = $items['term_background'];
					self::$term_color      = $items['term_color'];
					self::$term_class      = $items['term_class'];
					self::$term_default    = $items['term_default'];
					self::$term_visible    = $items['term_visible'];
					self::$term_name       = $items['term_name'];
					self::$term_checked    = $items['term_checked'];
					self::$term_status     = $items['term_status'];
					break;
				}
			}
		}
	}


	/**
	 * Set taxonomy label
	 * @param string $name
	 * @param array $tax_attrs
	 *
	 * @return void
	 */
	private static function set_taxonomy_label(string $name, array &$tax_attrs) : void {
		if($tax_attrs) {
			foreach ($tax_attrs as $items) {
				if($name === $items['name']) {
					self::$tax_label = $items['label'];
					break;
				}
			}
		}
	}


	/**
	 * Sort terms manually
	 *
	 * @param array $term_sort
	 * @param array $terms
	 *
	 * @return void
	 */
	private static function sort_terms_manual(array $term_sort, array &$terms) : void {
		if($term_sort) {
			$sorted_terms = [];
			foreach($term_sort as $id) {
				$key = (int) $id;
				if( !array_key_exists($key, $terms) ) continue;
					$sorted_terms[$key] = $terms[$key];
			}
			if($sorted_terms) {
				$terms = $sorted_terms;
			}
		}
	}


	/**
	 * Determine term sorting
	 * @param string $term_sort_direction
	 * @param array  $term_sort
	 * @param array  $terms
	 *
	 * @return void
	 */
	private static function determine_term_sorting(string $term_sort_direction, array $term_sort, array &$terms) : void {
		if ($term_sort_direction === 'asc') {
			asort($terms);
		} elseif ($term_sort_direction === 'desc') {
			arsort($terms);
		} else {
			// Manual sorting
			$temp_array = [];
			if (!empty($term_sort)) {
				// Add sorted terms (if they are in $terms)
				foreach ($term_sort as $term_id) {
					if (isset($terms[$term_id])) {
						$temp_array[$term_id] = $terms[$term_id];
					}
				}
			}
			// Add new terms (which are not in $term_sort)
			foreach ($terms as $term_id => $term_name) {
				if (!isset($temp_array[$term_id])) {
					$temp_array[$term_id] = $term_name;
				}
			}
			$terms = $temp_array;
		}
	}


	/**
	 * Check if all terms are selected
	 * @param array $terms
	 * @param array $selected_terms
	 *
	 * @return bool
	 */
	private static function checked_selected_terms(array $terms, array $selected_terms) : bool {
		$selected_terms = array_map(function ($item) { return (int) $item; }, $selected_terms);
		$selected_terms = array_flip($selected_terms);
		return count(array_intersect_key($terms, $selected_terms)) === count($terms);
	}


	/**
	 * Update post counts
	 * @param string $taxName
	 * @param int $termId
	 * @param string $cache_key
	 *
	 * @return void
	 */
	public static function update_post_counts(string $tax_name, int $term_id, string $cache_key) : void {
		if (get_transient($cache_key) === false) {
			$post_types = (array) get_taxonomy($tax_name)->object_type;
			foreach ($post_types as $type) {
				$args = [
					'posts_per_page' => -1,
					'post_type' => $type,
					'tax_query' => [['taxonomy' => $tax_name, 'terms' => $term_id]],
				];
				$countQuery = new \WP_Query($args);
				update_term_meta($term_id, "ymc_fg_count_{$type}", $countQuery->found_posts);
			}
		}
	}


	/**
	 * Get post counts
	 * @param array $post_types
	 * @param int $term_id
	 *
	 * @return int
	 */
	public static function get_post_counts(array $post_types, int $term_id): int {
		$post_count = 0;
		foreach ($post_types as $type) {
			$post_count += (int) get_term_meta( $term_id, "ymc_fg_count_{$type}", true);
		}
		return $post_count;
	}


	/**
	 * Output HTML term item.
	 *
	 * @param int $post_id
	 * @param string $tax
	 *
	 * @return string
	 */
	public static function output_html(int $post_id, array $post_types) : string {

		$all_tax = Taxonomy::get_taxonomies($post_types);
		$selected_tax = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
		$selected_terms = Data_Store::get_meta_value($post_id, 'ymc_fg_terms');
		$term_attrs = Data_Store::get_meta_value($post_id, 'ymc_fg_term_attrs');
		$term_sort = Data_Store::get_meta_value($post_id, 'ymc_fg_term_sort');
		$tax_attrs = Data_Store::get_meta_value($post_id, 'ymc_fg_tax_attrs');
		$term_sort_direction = Data_Store::get_meta_value($post_id, 'ymc_fg_term_sort_direction');

		ob_start();

		if($all_tax) {
			foreach( $all_tax as $tax_name => $label ) {
				if( in_array($tax_name, $selected_tax) ) {
					$terms = self::get_terms($tax_name);
					$checked = (self::checked_selected_terms($terms, $selected_terms)) ? 'checked' : '';

					self::set_taxonomy_label($tax_name, $tax_attrs);
					self::$tax_label = (self::$tax_label) ? : $label;

					echo '<article class="cel js-tax-'. esc_attr($tax_name) .'">';
					echo '<div class="tax-name">
                          <input class="checkbox-control js-select-all-terms" id="all-terms-'. esc_attr($tax_name) .'" '. esc_attr($checked) .' type="checkbox">
                          <label class="field-label" for="all-terms-'. esc_attr($tax_name) .'">'. esc_html(self::$tax_label) .'</label></div>';
					echo '<div class="terms-inner js-term-sortable">';

					self::$tax_label = '';
					self::determine_term_sorting($term_sort_direction, $term_sort,$terms);

					if($terms) {
						foreach ($terms as $term_id => $name) {
							self::update_post_counts($tax_name, $term_id, self::$cache_key);
							self::set_data_attributes($term_id, $term_attrs);
							self::$term_name = (self::$term_name) ? : $name;

							$post_count = self::get_post_counts($post_types, $term_id);
							$is_term_sel = (in_array($term_id, $selected_terms)) ? 'checked' : '';
							$class_status = (self::$term_status) ? ' '. self::$term_status : '';
							$term_slug = get_term( $term_id )->slug;

							echo '<div class="term-item'. esc_attr($class_status) .'" 
							data-term-id="'. esc_attr($term_id) .'" 
							data-term-slug="'. esc_attr($term_slug) .'" 
							data-term-name-orig="'. esc_attr($name) .'"
							data-term-name="'. esc_attr(self::$term_name) .'"
							data-term-background="'. esc_attr(self::$term_background) .'"
							data-term-color="'. esc_attr(self::$term_color) .'"
							data-term-class="'. esc_attr(self::$term_class) .'"
							data-term-default="'. esc_attr(self::$term_default) .'"
							data-term-visible="'. esc_attr(self::$term_visible) .'"							
							data-term-checked="'. esc_attr(self::$term_checked) .'"
							data-term-status="'. esc_attr(self::$term_status).'"							
							data-term-icon-class="'. esc_attr(self::$icon_class) .'" 
							data-term-icon-alignment="'. esc_attr(self::$icon_alignment) .'" 
							data-term-icon-color="'. esc_attr(self::$icon_color) .'"
							data-term-icon-url="'. esc_attr(self::$icon_url) .'">					  
							<i class="fa-solid fa-up-down-left-right icon-is-drag js-term-handle"></i>
							<input class="checkbox-control" id="term-id-'. esc_attr($term_id) .'" name="ymc_fg_terms[]" type="checkbox" '. esc_attr($is_term_sel) .' value="'. esc_attr($term_id) .'">					  
							<label class="field-label" for="term-id-'. esc_attr($term_id) .'">'. esc_html(self::$term_name) .' <span class="count">('. esc_html($post_count) .')</span></label>
							<i class="fa-solid fa-ellipsis-vertical icon-is-settings js-term-settings"></i>
							<span class="icon-term js-icon-term"></span></div>';

							self::clear_data_attributes();
						}
					} else {
						echo '<div class="no-terms">
					          <div class="notification notification--warning">'. esc_html__('No terms found.', 'ymc-smart-filters') .'</div></div>';
					}
					echo '</div></article>';

					if (get_transient(self::$cache_key) === false) {
						set_transient(self::$cache_key, true, 30);
					}
				}
			}

		} else {
			echo '<div class="no-terms">
			      <div class="notification notification--warning">'. esc_html__('No terms found.', 'ymc-smart-filters') .'</div></div>';
		}

		return ob_get_clean();
	}

	/**
	 * Get terms by taxonomy
	 * @param string $tax
	 *
	 * Key is term id and value is term name
	 * Sort by term name ASC
	 * @return array
	 */
	public static function get_terms( string $tax ) : array {
		$args = [
			'taxonomy' => $tax,
			'hide_empty' => false
		];
		$list_terms = [];
		$terms = get_terms($args);
		if( $terms && ! is_wp_error( $terms ) ) {
			foreach( $terms as $term ) {
				$list_terms[$term->term_id] = $term->name;
			}
		}

		return $list_terms;
	}

}