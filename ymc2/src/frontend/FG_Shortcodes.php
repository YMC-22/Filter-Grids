<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\frontend\FG_Components as Components;
use YMCFilterGrids\frontend\FG_Json_Builder as Json_Builder;
use YMCFilterGrids\FG_Data_Store as Data_Store;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode Class
 *
 * @since 3.0.0
 */
class FG_Shortcodes {

	/**
	 * Counter for shortcodes
	 * @var int $counter_filter
	 */
	private static int $counter_filter = 1;
	private static int $extra_counter_filter = 1;
	private static int $extra_counter_search = 1;
	private static int $extra_counter_sort = 1;


	/**
	 * Hook in methods.
	 */
	public static function init() : void {
		$shortcodes = [
			'ymc_filter'       => 'apply_grid_filters',
			'ymc_extra_filter' => 'apply_extra_filters',
			'ymc_extra_search' => 'apply_extra_search',
			'ymc_extra_sort'   => 'apply_extra_sorting',
		];

		foreach ($shortcodes as $tag => $method) {
			add_shortcode($tag, [__CLASS__, $method]);
		}
	}


	/**
	 * Create shortcode wrapper
	 * @param int $filter_id
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(int $filter_id): string {
		$custom_container_class = esc_attr(Data_Store::get_meta_value($filter_id, 'ymc_fg_custom_container_class'));
		$custom_container_class = $custom_container_class ? " $custom_container_class" : '';
		$post_layout  = Data_Store::get_meta_value($filter_id,'ymc_fg_post_layout');
        $grid_style = ($post_layout === 'layout_carousel') ? 'carousel' : Data_Store::get_meta_value($filter_id, 'ymc_fg_grid_style');

		ob_start(); ?>

        <?php $custom_css = Data_Store::get_meta_value($filter_id, 'ymc_fg_custom_css'); ?>
		<?php if (!empty($custom_css)) :
			$minified_css = ymc_minify_css($custom_css); ?>
            <style id="ymc-custom-css-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr(self::$counter_filter); ?>"><?php echo esc_html($minified_css); ?></style>
		<?php endif; ?>

        <?php $custom_js = Data_Store::get_meta_value($filter_id, 'ymc_fg_custom_js'); ?>
        <?php if (!empty($custom_js)) : ?>
            <?php // phpcs:ignore WordPress ?>
            <script id="ymc-custom-js-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr(self::$counter_filter); ?>"><?php echo $custom_js; ?></script>
        <?php endif; ?>

		<div id="ymc-filter-<?php echo esc_attr(self::$counter_filter); ?>"
             class="ymc ymc-filter-grids ymc-container js-ymc-container ymc-filter-<?php echo esc_attr($filter_id); ?> ymc-filter-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr(self::$counter_filter); ?><?php echo esc_attr($custom_container_class); ?>"
             data-params="<?php echo esc_attr(Json_Builder::build($filter_id, self::$counter_filter)); ?>"
             data-loading-enabled="true"
             data-grid-style="<?php echo esc_attr($grid_style); ?>"
             data-filter-id="<?php echo esc_attr($filter_id); ?>">
			<?php
			// phpcs:ignore WordPress
            echo Components::init($filter_id, self::$counter_filter); ?>
		</div>
		<?php
		self::$counter_filter++;
		return ob_get_clean();
	}


	/**
	 * Shortcode filter grids
	 * @param array $attrs
	 * @return string
	 */
	public static function apply_grid_filters( array $attrs ) : string {
		$filter_id     = isset($attrs['id']) ? (int) $attrs['id'] : 0;
		$post_status   = $filter_id > 0 ? get_post_status($filter_id) : '';
		$ymc_post_type = $filter_id > 0 ? get_post_type($filter_id) : '';

		if ($filter_id > 0 && $post_status === 'publish' && $ymc_post_type === 'ymc_filters') {
			return self::shortcode_wrapper($filter_id);
		}
		return '<div class="ymc ymc-container">
                <div class="notification notification--error">' . esc_html__('ID parameter is missing or invalid.', 'ymc-smart-filters') .'</div>
                </div>';
	}

	/**
	 * Shortcode extra filter
	 *
	 * @param array $attrs
	 *
	 * @return string
	 */
	public static function apply_extra_filters(array $attrs) : string {
		$filter_id = isset($attrs['id']) ? (int) $attrs['id'] : 0;
		ob_start(); ?>

        <div id="ymc-extra-filter-<?php echo esc_attr(self::$extra_counter_filter); ?>"
             class="ymc ymc-extra-container js-ymc-container ymc-extra-filter ymc-extra-filter-<?php echo esc_attr($filter_id); ?> ymc-extra-filter-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr(self::$extra_counter_filter); ?>"
             data-extra-filter-id="<?php echo esc_attr($filter_id); ?>">
            <div class="filter-section">
		    <?php if ($filter_id > 0) {
			if (!get_post_status($filter_id)) {
				echo '<div class="notification notification--warning">'.esc_html__( 'Filter not found. Please, check the filter ID', 'ymc-smart-filters' ).'</div>';
				return ob_get_clean();
			}
            $filter_type    = Data_Store::get_meta_value($filter_id, 'ymc_fg_extra_filter_type');
			$extra_taxonomy = Data_Store::get_meta_value($filter_id, 'ymc_fg_extra_taxonomy');

			$filter_options = [
				[
					'tax_name'    => [ $extra_taxonomy ],
					'filter_type' => $filter_type,
					'placement'   => 'top'
				]
			];

			Components::get_filter($filter_id, 'top', $filter_options);
            } else {
                echo '<div class="notification notification--error">' . esc_html__('ID parameter is missing or invalid.', 'ymc-smart-filters') .'</div>';
            }
            ?>
            </div>
        </div>
		<?php
		self::$extra_counter_filter++;
        return ob_get_clean();
	}



	/**
	 * Shortcode extra search
	 *
	 * @param array $attrs
	 *
	 * @return string
	 */
	public static function apply_extra_search(array $attrs) : string {
		$filter_id = isset($attrs['id']) ? (int) $attrs['id'] : 0;
        ob_start(); ?>

        <div id="ymc-extra-search-<?php echo esc_attr(self::$extra_counter_search); ?>"
             class="ymc ymc-extra-container js-ymc-container ymc-extra-search ymc-extra-search-<?php echo esc_attr($filter_id); ?> ymc-extra-search-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr(self::$extra_counter_search); ?>"
             data-extra-filter-id="<?php echo esc_attr($filter_id); ?>">
	         <?php if ($filter_id > 0) {
		        if (!get_post_status($filter_id)) {
			        echo '<div class="notification notification--warning">'.esc_html__( 'Filter not found. Please, check the filter ID', 'ymc-smart-filters' ).'</div>';
			        return ob_get_clean();
		        }

		        Components::render_search_bar($filter_id, true);

	         } else {
		        echo '<div class="notification notification--error">' . esc_html__('ID parameter is missing or invalid.', 'ymc-smart-filters') .'</div>';
	         }
	        ?>
        </div>
		<?php
		self::$extra_counter_search++;
		return ob_get_clean();
	}


	/**
	 * Shortcode extra sort
	 *
	 * @param array $attrs
	 *
	 * @return string
	 */
	public static function apply_extra_sorting(array $attrs) : string {
		$filter_id = isset($attrs['id']) ? (int) $attrs['id'] : 0;
        ob_start(); ?>

        <div id="ymc-extra-sort-<?php echo esc_attr(self::$extra_counter_sort); ?>"
             class="ymc ymc-extra-container js-ymc-container ymc-extra-sort ymc-extra-sort-<?php echo esc_attr($filter_id); ?> ymc-extra-sort-<?php echo esc_attr($filter_id); ?>-<?php echo esc_attr(self::$extra_counter_sort); ?>"
             data-extra-filter-id="<?php echo esc_attr($filter_id); ?>">
	         <?php if ($filter_id > 0) {
		        if (!get_post_status($filter_id)) {
			        echo '<div class="notification notification--warning">'.esc_html__( 'Filter not found. Please, check the filter ID', 'ymc-smart-filters' ).'</div>';
			        return ob_get_clean();
		        }

		        Components::render_sort_bar($filter_id, true);

	         } else {
		        echo '<div class="notification notification--error">' . esc_html__('ID parameter is missing or invalid.', 'ymc-smart-filters') .'</div>';
	         }
	        ?>
        </div>

		<?php
		self::$extra_counter_sort++;
		return ob_get_clean();
	}

}