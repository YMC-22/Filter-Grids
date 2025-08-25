<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\abstracts\FG_Abstract_Filter_Impl;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\interfaces\IFilter;

defined( 'ABSPATH' ) || exit;


/**
 * Class FG_Filter_Default
 *
 * @since 3.0.0
 */
class FG_Filter_Default extends FG_Abstract_Filter_Impl implements IFilter {

	public function render(int $filter_id, array $tax_name, array $filter_options): string {

		if (empty($filter_id) && empty($tax_name) && empty($filter_options)) {
			return '';
		}

		$this->get_options( $filter_id );
		$class_by_name_taxonomy = implode( '-', $tax_name );

        // Get all button settings
		$filter_all_button = Data_Store::get_meta_value($filter_id, 'ymc_fg_filter_all_button');
		$all_button_label = 'All';
		$is_visible_all_button = 'yes';
		foreach ($tax_name as $tax) {
			if (!empty($filter_all_button[$tax])) {
				$settings = $filter_all_button[$tax];
				$all_button_label = !empty($settings['all_label']) ? $settings['all_label'] : 'All';
				$is_visible_all_button = $settings['is_visible'] ?? 'yes';
				break;
			}
		}

		// Get selection mode
		$is_multiple_mode  = Data_Store::get_meta_value($filter_id, 'ymc_fg_selection_mode');

        // Get all terms
		$all_terms = [];
		$display_terms_mode = (string) Data_Store::get_meta_value($filter_id, 'ymc_fg_display_terms_mode');
		$hide_empty = ($display_terms_mode === 'all_terms_hide_empty' || $display_terms_mode === 'selected_terms_hide_empty');
		$term_selected = in_array($display_terms_mode, ['all_terms', 'all_terms_hide_empty'], true)
			? []
			: array_map('intval', (array) Data_Store::get_meta_value($filter_id, 'ymc_fg_terms'));

		foreach ($tax_name as $tax) {
			$terms = get_terms([
				'taxonomy'   => $tax,
				'include'    => $term_selected,
				'hide_empty' => $hide_empty
			]);

			if (!is_wp_error($terms) && ! empty($terms)) {
				foreach ($terms as $term) {
					$all_terms[$term->term_id] = $term->name;
				}
			}
		}

		ob_start();

		?>

        <div class="filter filter-default filter-<?php echo esc_attr( $class_by_name_taxonomy ); ?> filter-<?php echo esc_attr( $filter_id ); ?>"
             data-filter-type="default"
             data-selection-mode="<?php echo esc_attr( $is_multiple_mode ); ?>">
            <div class="filter-default-inner">
                <div class="filter-buttons">
                    <?php if('yes' === $is_visible_all_button) : ?>
                        <button class="filter-button filter-button--all js-filter-button-all"
                                data-all-terms='<?php echo json_encode(array_keys( $all_terms )); ?>'>
                            <span class="text">
                            <?php
                            // phpcs:ignore WordPress
                            echo esc_html(sprintf(__( '%s', 'ymc-smart-filters' ), $all_button_label)); ?></span>
                        </button>
                    <?php endif; ?>

                    <?php
					foreach ($tax_name as $tax) {
						$terms = $this->get_selected_terms_by_taxonomy($filter_id, $tax);
						$terms = $this->sort_terms_manual( $terms, $filter_id );

						if (!empty($terms)) {
							foreach ($terms as $term_id => $term_label) {
								if ('false' === $this->get_term_visible( $term_id)) {
									continue;
								}
								// phpcs:ignore WordPress
								echo $this->render_term_button( $term_id, $term_label );
							}
						}
					}
					?>
                </div>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	private function render_term_button( int $term_id, string $fallback_name ): string {
		$term_class_is_default = $this->get_term_default( $term_id );
		$term_class_is_default = 'true' === $term_class_is_default ? 'is-default' : '';
		$term_style            = $this->get_term_style( $term_id );
		$term_icon             = $this->get_icon( $term_id );
		$icon_alignment        = $this->get_icon_alignment( $term_id );
		$term_class            = $this->get_term_class( $term_id );
		$term_name             = $this->get_term_name( $term_id );
		$term_name             = ! empty( $term_name ) ? $term_name : $fallback_name;
        $term_is_disabled      = ! $this->hasAttachedPosts( $term_id ) ? 'is-disabled' : '';

		$classes = array_filter([
			$icon_alignment,
			$term_class,
			$term_class_is_default,
			$term_is_disabled
		]);


		ob_start();
		?>
        <button class="filter-button <?php echo esc_attr(implode(' ', $classes)); ?>"
			<?php echo wp_kses_post( $term_style ); ?>
                data-termid="<?php echo esc_attr( $term_id ); ?>"
                aria-pressed="false">
			<?php
			// phpcs:ignore WordPress
            echo $term_icon; ?>
            <span class="text"><?php echo esc_html( $term_name ); ?></span>
        </button>
		<?php
		return ob_get_clean();
	}

}