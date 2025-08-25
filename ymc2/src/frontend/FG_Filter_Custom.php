<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\abstracts\FG_Abstract_Filter_Impl;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\interfaces\IFilter;

defined( 'ABSPATH' ) || exit;


/**
 * Class FG_Filter_Custom
 *
 * @since 3.0.0
 */
class FG_Filter_Custom extends FG_Abstract_Filter_Impl implements IFilter {
	public static int $filter_counter = 1;

	public function render( int $filter_id, array $tax_name, array $filter_options ): string {

		if (empty($tax_name)) {
			return '';
		}

		$this->get_options($filter_id);
		$class_by_name_taxonomy = implode('-', $tax_name);
		$is_multiple_mode = Data_Store::get_meta_value($filter_id, 'ymc_fg_selection_mode');
		$placement = $filter_options['placement'];
		$term_settings = $this->build_term_settings($tax_name);
        $container_class = 'ymc-filter-'. $filter_id .'-'. self::$filter_counter;

		// Generate HTML block guide
		$guide_html  = '<div class="filter-custom-guide">';
		$guide_html .= '<div class="filter-usage">';
		$guide_html .= '<div class="filter-usage-inner">';
		$guide_html .= '<span class="headline">'. esc_html__("Use a filter:", "ymc-smart-filters") .'</span>';
		$guide_html .= '<span class="description">add_filter("ymc/filter/layout/'. $placement .'/custom", "callback_function", 10, 5);</span>';
		$guide_html .= '<span class="description">add_filter("ymc/filter/layout/'. $placement .'/custom_'. $filter_id .'", "callback_function", 10, 5);</span>';
		$guide_html .= '<span class="description">add_filter("ymc/filter/layout/'. $placement .'/custom_'. $filter_id .'_'. self::$filter_counter .'", "callback_function", 10, 5);</span>';
		$guide_html .= '</div>';
		$guide_html .= '<div class="filter-usage-inner">';
		$guide_html .= '<a class="link" target="_blank" href="https://github.com/YMC-22/Filter-Grids/tree/main?tab=readme-ov-file#custom-filter-layout">'. esc_html__("See documentation", "ymc-smart-filters") .'</a>';
		$guide_html .= '</div>';
		$guide_html .= '</div>';
		$guide_html .= '</div>';

		// Apply filters to this part
		$filter_keys = [
			"ymc/filter/layout/{$placement}/custom",
			"ymc/filter/layout/{$placement}/custom_{$filter_id}",
			"ymc/filter/layout/{$placement}/custom_{$filter_id}_" . self::$filter_counter
		];

		/**
		 * Custom filter block HTML filter.
		 *
		 * @param string $guide_html
		 * @param int $filter_id
		 * @param array $tax_name
		 * @param array $term_settings
		 * @param string $container_class
         *
		 * @return string Modified HTML of the filter block.
		 */
		foreach ($filter_keys as $hook_name) {
			$guide_html = apply_filters($hook_name, $guide_html, $filter_id, $tax_name, $term_settings, $container_class);
		}

		self::$filter_counter++;

		ob_start();
		?>

        <div class="filter filter-custom filter-<?php echo esc_attr($class_by_name_taxonomy); ?> filter-<?php echo esc_attr($filter_id); ?>"
             data-filter-type="custom"
             data-selection-mode="<?php echo esc_attr($is_multiple_mode); ?>">
             <div class="filter-custom-inner">
				<?php
				// phpcs:ignore WordPress
                echo $guide_html; ?>
             </div>
        </div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Collection of data on terms of given taxonomies.
	 *
	 * @param array $taxonomies
	 * @return array An associative array with term settings for each taxonomy.
	 */
	private function build_term_settings(array $taxonomies): array {
		$all_term_settings = [];

		foreach ($taxonomies as $taxonomy) {
			$terms = get_terms([
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			]);

			if (is_wp_error($terms)) {
				continue;
			}

			$term_data = [];

			foreach ($terms as $term) {
				$term_id = $term->term_id;

				$term_data[$term_id] = [
					'term_id'             => $term_id,
					'term_name'           => $term->name,
					'term_slug'           => $term->slug,
					'term_background'     => $this->get_term_background($term_id),
					'term_color'          => $this->get_term_color($term_id),
					'term_class'          => $this->get_term_class( $term_id ),
					'term_default'        => $this->get_term_default( $term_id ) === 'true',
					'term_visible'        => $this->get_term_visible( $term_id ) === 'true',
					'term_checked'        => $this->get_term_checked( $term_id) === 'true',
					'term_icon_class'     => $this->get_icon_class( $term_id),
					'term_icon_color'     => $this->get_icon_color( $term_id),
					'term_icon_alignment' => $this->get_icon_alignment( $term_id),
					'term_icon_url'       => $this->get_icon_url( $term_id)
				];
			}

			$all_term_settings[$taxonomy] = [
				'taxonomy' => $taxonomy,
				'terms'    => $term_data,
			];
		}

		return $all_term_settings;
	}

}
