<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\FG_Data_Store as Data_Store;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Json_Builder Class
 * Build JSON data
 *
 * @package YMCFilterGrids
 * @since 3.0.0
 */
class FG_Json_Builder {

	/**
	 * Allowed keys
	 * This keys will be used to build JSON data and send to frontend
	 *
	 * @since 3.0.0
	 */
	private static array $allowed_keys = [
		'ymc_fg_post_types',
		'ymc_fg_taxonomies',
		'ymc_fg_terms',
		'ymc_fg_preloader_settings'
	];

	public static function build(int $filter_id, int $counter): string {
		$display_terms_mode  = (string) Data_Store::get_meta_value($filter_id,'ymc_fg_display_terms_mode');
		$post_layout  = (string) Data_Store::get_meta_value($filter_id,'ymc_fg_post_layout');
		$all_meta_values = Data_Store::get_all_meta_values($filter_id);

		// Filter allowed keys and remove prefix
		$data = array_intersect_key($all_meta_values, array_flip(self::$allowed_keys));

		// Get default term ids
		$default_terms = self::get_default_term_ids($data['ymc_fg_terms'], $filter_id);
		$data['ymc_fg_terms'] = !empty($default_terms)
			? array_map('intval', $default_terms)
			: array_map('intval', $data['ymc_fg_terms']);

		$data = array_combine(
			array_map(fn($key) => preg_replace('/^ymc_fg_/', '', $key), array_keys($data)),
			$data
		);

		if($post_layout === 'layout_carousel') {
			$data['carousel_settings'] = Data_Store::get_meta_value($filter_id,'ymc_fg_carousel_settings');
		}

		// Add additional values
		$data += [
			'filter_id' => $filter_id,
			'counter'   => $counter,
			'page_id'   => get_queried_object_id(),
			'paged'     => 1
		];

		// Get terms based on display mode
		if (in_array($display_terms_mode, ['all_terms', 'all_terms_hide_empty'], true)) {
			$data['terms'] = self::get_terms_by_taxonomy(
				$data['taxonomies'] ?? [],
				$display_terms_mode === 'all_terms_hide_empty'
			);
		}

		return json_encode($data);
	}

	/**
	 * Get terms IDs by taxonomy
	 * @param array $taxonomies
	 * @param bool $hide_empty
	 *
	 * @return array
	 */
	private static function get_terms_by_taxonomy(array $taxonomies,  bool $hide_empty): array {
		$terms_ids = [];

		foreach ($taxonomies as $taxonomy) {
			$terms = get_terms([
				'taxonomy'   => $taxonomy,
				'hide_empty' => $hide_empty
			]);

			if (!is_wp_error($terms)) {
				foreach ($terms as $term) {
					$terms_ids[] = $term->term_id;
				}
			}
		}

		return $terms_ids;
	}


	/**
	 * Get default term ids
	 *
	 * @param array $term_ids
	 * @param int $filter_id
	 *
	 * @return array
	 */
	private static function get_default_term_ids(array $term_ids, int $filter_id): array {
		$term_attrs = Data_Store::get_meta_value($filter_id, 'ymc_fg_term_attrs');

		return array_values(array_map(
			fn($term) => (int) $term['term_id'], // 👈 Приводим к int здесь
			array_filter($term_attrs, function($term) use ($term_ids) {
				return $term['term_default'] === 'true' && in_array($term['term_id'], $term_ids, true);
			})
		));
	}


}