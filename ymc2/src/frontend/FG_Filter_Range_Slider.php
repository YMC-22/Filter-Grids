<?php

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\abstracts\FG_Abstract_Filter_Impl;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\interfaces\IFilter;


/**
 * Class FG_Filter_Range_Slider
 *
 * @since 3.0.0
 */
class FG_Filter_Range_Slider extends FG_Abstract_Filter_Impl implements IFilter {

	public function render( int $filter_id, array $tax_name, array $filter_options ): string {

		$is_multiple_mode = Data_Store::get_meta_value($filter_id, 'ymc_fg_selection_mode');
		$tax_attrs = Data_Store::get_meta_value($filter_id, 'ymc_fg_tax_attrs');

		// Настройки отображения terms
		$display_terms_mode = (string) Data_Store::get_meta_value($filter_id, 'ymc_fg_display_terms_mode');
		$hide_empty         = ($display_terms_mode === 'all_terms_hide_empty' || $display_terms_mode === 'selected_terms_hide_empty');
		$term_selected      = in_array($display_terms_mode, ['all_terms', 'all_terms_hide_empty'], true)
			? []
			: array_map('intval', (array) Data_Store::get_meta_value($filter_id, 'ymc_fg_terms'));

		ob_start();

		foreach ($tax_name as $tax) :

			$taxonomy_obj = get_taxonomy($tax);
			if (!$taxonomy_obj) {
				continue;
			}

			$tax_label = $this->get_tax_label_by_name($tax_attrs, $tax);
			$tax_slug  = $taxonomy_obj->name;

			$terms = get_terms([
				'taxonomy'   => $tax,
				'include'    => $term_selected,
				'hide_empty' => $hide_empty
			]);

			if (is_wp_error($terms) || empty($terms)) {
				continue;
			}

			$all_terms = [];
			$tag_ids   = [];

			foreach ($terms as $term) {
				$all_terms[$term->term_id] = $term->name;
				$tag_ids[]                 = $term->term_id;
			}

			$tagAllIds = $tag_ids;
			$tag_ids   = implode(',', $tag_ids);
			?>

            <div class="filter filter-range filter-<?php echo esc_attr($tax); ?> filter-<?php echo esc_attr($filter_id); ?>"
                 data-filter-type="range"
                 data-selection-mode="<?php echo esc_attr($is_multiple_mode); ?>">
                <div class="filter-range-inner">
                    <div class="range-wrapper tax-<?php echo esc_attr($tax_slug); ?>">
                        <div class="range__component tax-label"><?php echo esc_html($tax_label); ?></div>
                        <div class="range__component tag-values js-tag-values"
                             data-tags='<?php echo wp_json_encode($all_terms); ?>'
                             data-all-terms="<?php echo esc_attr(json_encode($tagAllIds)); ?>"
                             data-selected-tags='<?php echo esc_attr($tag_ids); ?>'>
                            <span class="range1"></span>
                            <span> <?php echo !empty($all_terms) ? '&dash;' : esc_html__('No tags','ymc-smart-filters'); ?></span>
                            <span class="range2"></span>
                        </div>

						<?php if(!empty($all_terms)) : ?>
                            <div class="range__component range-container">
                                <div class="slider-track"></div>
                                <input class="slider-1" type="range" min="0" max="" value="0">
                                <input class="slider-2" type="range" min="0" max="" value="">
                            </div>
                            <div class="range__component apply-button">
                                <button class="apply-button__inner"><?php esc_html_e('Apply','ymc-smart-filters'); ?></button>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
            </div>

		<?php
		endforeach;

		return ob_get_clean();
	}

	private function get_tax_label_by_name(array $tax_attrs, string $tax): string {
		$names = array_column($tax_attrs, 'name');
		$index = array_search($tax, $names, true);

		if ($index !== false && !empty($tax_attrs[$index]['label'])) {
			return $tax_attrs[$index]['label'];
		}

		// fallback: standard taxonomy label
		$taxonomy_obj = get_taxonomy($tax);
		return $taxonomy_obj ? $taxonomy_obj->label : $tax;
	}

}