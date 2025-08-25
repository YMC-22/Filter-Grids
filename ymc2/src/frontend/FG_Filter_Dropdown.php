<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\abstracts\FG_Abstract_Filter_Impl;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\interfaces\IFilter;

defined( 'ABSPATH' ) || exit;

/**
 * Class FG_Filter_Dropdown
 *
 * @since 3.0.0
 */
class FG_Filter_Dropdown extends FG_Abstract_Filter_Impl implements IFilter {

	public function render( int $filter_id, array $tax_name, array $filter_options ): string {

		if (empty($filter_id) && empty($tax_name) && empty($filter_options)) {
			return '';
		}

		$placement = $filter_options['placement'];

		$this->get_options($filter_id);
		$class_by_name_taxonomy = implode('-', $tax_name);
		$is_multiple_mode = Data_Store::get_meta_value($filter_id, 'ymc_fg_selection_mode');
		$tax_attrs = Data_Store::get_meta_value($filter_id, 'ymc_fg_tax_attrs');

		// Get all terms
		$all_terms = [];
		$display_terms_mode = (string) Data_Store::get_meta_value($filter_id, 'ymc_fg_display_terms_mode');
		$hide_empty = ($display_terms_mode === 'all_terms_hide_empty' || $display_terms_mode === 'selected_terms_hide_empty');
		$term_selected = in_array($display_terms_mode, ['all_terms', 'all_terms_hide_empty'], true)
			? []
			: array_map('intval', (array) Data_Store::get_meta_value($filter_id, 'ymc_fg_terms'));

		ob_start();

        ?>

		<?php if($tax_name) :

			foreach ($tax_name as $tax) :

				$terms     = $this->get_selected_terms_by_taxonomy($filter_id, $tax);
				$terms     = $this->sort_terms_manual( $terms, $filter_id );
				$tax_label = $this->get_tax_label_by_name($tax_attrs, $tax);
				$style     = $this->get_tax_style_by_name($tax_attrs, $tax);

				$terms_by_tax = get_terms([
					'taxonomy'   => $tax,
					'include'    => $term_selected,
					'hide_empty' => $hide_empty
				]);

				if (!is_wp_error($terms_by_tax) && ! empty($terms_by_tax)) {
					foreach ($terms_by_tax as $term) {
						$all_terms[$term->term_id] = $term->name;
					}
				}
                ?>

			    <div class="filter filter-dropdown filter-dropdown-<?php echo esc_attr($placement); ?>  filter-<?php echo esc_attr($class_by_name_taxonomy); ?> filter-<?php echo esc_attr($filter_id); ?>"
			     data-filter-type="dropdown"
                 data-selection-mode="<?php echo esc_attr( $is_multiple_mode ); ?>">
                 <div class="filter-dropdown-inner">
                    <div class="ymc-dropdown js-dropdown"
                         data-label="<?php echo esc_attr($tax_label); ?>"
                         data-all-terms="<?php echo json_encode(array_keys( $all_terms )); ?>">
                        <div class="ymc-dropdown__selected js-dropdown-selected"
                            <?php
                                // phpcs:ignore WordPress
                                echo $style ?>>
                            <span class="ymc-dropdown__label js-dropdown-label"><?php echo esc_attr($tax_label); ?></span>
                            <span class="ymc-dropdown__arrow"></span>
                        </div>
                        <ul class="ymc-dropdown__list">
                            <li class="ymc-dropdown__close">
                                <button type="button" class="dropdown-close-btn" aria-label="Close dropdown">×</button>
                            </li>
		                    <?php
			                    if (!empty($terms)) {
				                    foreach ($terms as $term_id => $term_label) {
					                    if ('false' === $this->get_term_visible( $term_id)) {
						                    continue;
					                    }
					                    // phpcs:ignore WordPress
					                    echo $this->render_term_button( $term_id, $term_label, $tax_name, $filter_id );
				                    }
			                    }
		                    ?>
                        </ul>
                    </div>
                </div>
            </div>

        <?php

		$all_terms = [];

        endforeach;

        endif;

        return ob_get_clean();
	}

	private function render_term_button( int $term_id, string $fallback_name, array $tax_name, int $filter_id ): string {
		$post_types = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_types');

		$term_class_is_default = $this->get_term_default( $term_id );
		$term_class_is_default = 'true' === $term_class_is_default ? 'is-default' : '';
		$term_style            = $this->get_term_style( $term_id );
		$term_class            = $this->get_term_class( $term_id );
		$term_name             = $this->get_term_name( $term_id );
		$term_icon             = $this->get_icon( $term_id );
		$term_name             = ! empty( $term_name ) ? $term_name : $fallback_name;
		$term_is_disabled      = ! $this->hasAttachedPosts( $term_id ) ? 'is-disabled' : '';
		$post_count            = $this->get_post_count_by_term_id($term_id, $tax_name, $post_types);

		$classes = array_filter([
			$term_class,
			$term_class_is_default,
			$term_is_disabled
		]);

		ob_start();
		?>

        <li class="ymc-dropdown__item js-dropdown-item <?php echo esc_attr(implode(' ', $classes)); ?>">
            <label class="ymc-dropdown__checkbox">
                <input type="checkbox" value="<?php echo esc_attr( $term_id ); ?>" data-value="<?php echo esc_attr( $term_id ); ?>">
                <span class="checkmark"></span>
	            <span class="term-name" <?php echo wp_kses_post( $term_style ); ?>><?php echo esc_html( $term_name ); ?></span>
                <span class="post-count" <?php echo wp_kses_post( $term_style ); ?>>(<?php echo esc_html( $post_count ); ?>)</span>
                <?php
                    // phpcs:ignore WordPress
                    echo $term_icon;
                ?>
            </label>
        </li>

		<?php
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

	private function get_tax_style_by_name(array $tax_attrs, string $tax): string {
		$names = array_column($tax_attrs, 'name');
		$index = array_search($tax, $names, true);

		if ($index === false) {
			return '';
		}

		$background = $tax_attrs[$index]['background'] ?? '';
		$color      = $tax_attrs[$index]['color'] ?? '';

		$style = [];

		if (!empty($background)) {
			$style[] = "background-color: {$background}";
		}

		if (!empty($color)) {
			$style[] = "color: {$color}";
		}

		return !empty($style) ? 'style="' . esc_attr(implode('; ', $style)) . '"' : '';
	}

}