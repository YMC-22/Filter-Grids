<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\abstracts\FG_Abstract_Filter as Abstract_Filter;
use YMCFilterGrids\abstracts\FG_Creator_Filter_Default as Filter_Default;
use YMCFilterGrids\abstracts\FG_Creator_Filter_Dropdown as Filter_Dropdown;
use YMCFilterGrids\abstracts\FG_Creator_Filter_Range_Slider as Filter_Range_Slider;
use YMCFilterGrids\abstracts\FG_Creator_Filter_Date_Picker as Filter_Date_Picker;
use YMCFilterGrids\abstracts\FG_Creator_Filter_Custom as Filter_Custom;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\FG_Template as Template;
use YMCFilterGrids\frontend\FG_Popup_Manager as Popup_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Components Class
 * Include filters, templates and other components
 *
 * @package YMCFilterGrids
 * @since 3.0.0
 */
class FG_Components {

	/**
	 * Init filters
	 *
	 * @param int $filter_id
	 * @param int $counter
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public static function init(int $filter_id, int $counter) : string {
		ob_start();

		if (!get_post_status($filter_id)) {
			echo '<div class="notification notification--warning">'.esc_html__( 'Filter not found. Please, check the filter ID', 'ymc-smart-filters' ).'</div>';
			return ob_get_clean();
		}

		$popup_enable = Data_Store::get_meta_value($filter_id, 'ymc_fg_popup_enable');
		if ($popup_enable === 'yes') {
			Popup_Manager::add_grid_id($filter_id);
			Popup_Manager::maybe_render_in_footer(true);
		}

		echo wp_kses(
			self::render_custom_styles($filter_id),
			[
				'style' => [],
				'link'  => [
					'href' => [],
					'rel'  => [],
					'type' => [],
				],
			]
		);

		// phpcs:ignore WordPress
		echo self::render_template_container($filter_id, $counter);

		add_action('admin_bar_menu', array(__CLASS__, 'add_admin_bar_menu'), 120);

		return ob_get_clean();
	}


	/**
	 * Get filters
	 * @param int $filter_id
	 * @param string $placement
	 * @param array $filter_options
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function get_filter(int $filter_id, string $placement, array $filter_options = []) : void {

		$filter_classes = [
			'default'      => Filter_Default::class,
			'dropdown'     => Filter_Dropdown::class,
			'range_slider' => Filter_Range_Slider::class,
			'date_picker'  => Filter_Date_Picker::class,
			'custom'       => Filter_Custom::class
		];

		foreach ($filter_options as $value) {
			if ($placement !== ($value['placement'] ?? '') || empty($value['tax_name'])) {
				continue;
			}

			$filter_type = $value['filter_type'] ?? '';
			$tax_name    = $value['tax_name'] ?? '';

			if (isset($filter_classes[$filter_type])) {
				// phpcs:ignore WordPress
				echo self::create_filter(new $filter_classes[$filter_type](), $filter_id, $tax_name, $value);
			}
		}
	}


	/**
	 * Get template container
	 *
	 * @param int $filter_id
	 * @param int $counter
	 *
	 * @since 3.0.0
	 * @return string
	 */
	protected static function render_template_container(int $filter_id, int $counter) : string {
		ob_start();

		$filter_hidden  = Data_Store::get_meta_value($filter_id, 'ymc_fg_filter_hidden');
		$filter_options = Data_Store::get_meta_value($filter_id, 'ymc_fg_filter_options');
		$columns_layout = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_columns_layout');
		$post_grid_gap  = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_grid_gap');

		$grid_classes = ymc_get_column_classes( $columns_layout );

		if(empty($filter_options) || !is_array($filter_options)) {
			echo '<div class="notification notification--warning">' .
			      esc_html__('There are no options filter.', 'ymc-smart-filters') .'</div>';
			return ob_get_clean();
		}

		$filter_placement = array_unique(array_column($filter_options, 'placement'));
		asort($filter_placement);
		$name_tmpl = 'tmpl_' . implode('_', $filter_placement);

		Template::render(
			__DIR__ . '/views/templates/filters/' . $name_tmpl . '.php',
			[
				'filter_id'       => $filter_id,
				'counter'         => $counter,
				'filter_options'  => $filter_options,
				'filter_hidden'   => $filter_hidden,
				'grid_classes'    => $grid_classes,
				'grid_gap'        => $post_grid_gap
			]
		);

		return ob_get_clean();
	}

	/**
	 * @param Abstract_Filter $creator
	 * @param int $filter_id
	 * @param array $tax_name
	 * @param array $filter_options
	 *
	 * @since 3.0.0
	 * @return string
	 */
	protected static function create_filter(Abstract_Filter $creator, int $filter_id, array $tax_name, array $filter_options) : string {
		 return $creator->create_filter($filter_id, $tax_name, $filter_options);
	}


	/**
	 * Render search form for posts
	 * @param int $filter_id
	 * @param bool $show_search
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function render_search_bar(int $filter_id, bool $show_search = false) : void {
		$search_enable         = Data_Store::get_meta_value($filter_id, 'ymc_fg_search_enable');
		$submit_button_text    = Data_Store::get_meta_value($filter_id, 'ymc_fg_submit_button_text');
		$search_placeholder    = Data_Store::get_meta_value($filter_id, 'ymc_fg_search_placeholder');
		$autocomplete_enabled  = Data_Store::get_meta_value($filter_id, 'ymc_fg_autocomplete_enabled');
		$search_mode           = Data_Store::get_meta_value($filter_id, 'ymc_fg_search_mode');

		if (isset($search_enable) && $search_enable === 'yes' || $show_search) :
			Template::render(__DIR__ . '/views/templates/search/tmpl-search.php',
				[
					'submit_button_text'   => $submit_button_text,
					'search_placeholder'   => $search_placeholder,
					'autocomplete_enabled' => $autocomplete_enabled,
					'search_mode'          => $search_mode,
					'filter_id'            => $filter_id
				]);
        endif;
	}


	/**
	 * Render sort bar
	 * @param int $filter_id
	 * @param bool $show_sort
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function render_sort_bar(int $filter_id, bool $show_sort = false) : void {
		$sort_enable  = Data_Store::get_meta_value($filter_id, 'ymc_fg_enable_sort_posts');
		$allowed_sort_fields  = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_sortable_fields');
		$sort_dropdown_label  = Data_Store::get_meta_value($filter_id, 'ymc_fg_sort_dropdown_label');

		if (isset($sort_enable) && $sort_enable === 'yes' || $show_sort) :
			Template::render(__DIR__ . '/views/templates/sort/tmpl-sort.php',
				[
					'allowed_sort_fields' => $allowed_sort_fields,
					'sort_dropdown_label' => $sort_dropdown_label,
					'filter_id' => $filter_id
				]);
		endif;

	}


	/**
	 * Render custom styles
	 * @param int $filter_id
	 *
	 * @return string
	 */
	private static function render_custom_styles(int $filter_id): string {
		$filter_typo = Data_Store::get_meta_value($filter_id, 'ymc_fg_filter_typography');
		$post_typo = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_typography');

		ob_start();

		// Custom fonts
		$font_links  = self::render_font_link($filter_typo);
		$font_links .= self::render_font_link($post_typo['title']);
		$font_links .= self::render_font_link($post_typo['link']);

		echo wp_kses(
			$font_links,
			[
				'link' => [
					'href' => [],
					'rel' => [],
				],
			]
		);

		// Font families
		$filter_font = self::get_custom_font($filter_typo);
		$post_title_font = self::get_custom_font($post_typo['title']);
		$post_link_font = self::get_custom_font($post_typo['link']);

		// Spacing
		$padding = self::get_spacing_vars('padding', $filter_typo);
		$margin = self::get_spacing_vars('margin', $filter_typo);

		$style = "
	    <style>
	    .ymc-filter-" . esc_attr($filter_id) . ", .ymc-extra-filter-" . esc_attr($filter_id) . " {
	        --ymc-filter-font-family: {$filter_font};
	        --ymc-filter-font-size: " . esc_attr($filter_typo['font_size'] . $filter_typo['font_size_unit']) . ";
	        --ymc-filter-font-weight: " . esc_attr($filter_typo['font_weight']) . ";
	        --ymc-filter-font-style: " . esc_attr($filter_typo['font_style']) . ";
	        --ymc-filter-line-height: " . esc_attr($filter_typo['line_height']) . ";
	        --ymc-filter-letter-spacing: " . esc_attr($filter_typo['letter_spacing']) . ";
	        --ymc-filter-text-transform: " . esc_attr($filter_typo['text_transform']) . ";
	        --ymc-filter-background-color: " . esc_attr($filter_typo['background_color']) . ";
	        --ymc-filter-color: " . esc_attr($filter_typo['color']) . ";
	        --ymc-filter-active-color: " . esc_attr($filter_typo['active_color']) . ";
	        --ymc-filter-active-background-color: " . esc_attr($filter_typo['active_background_color']) . ";
	        --ymc-filter-hover-text-color: " . esc_attr($filter_typo['hover_text_color']) . ";
	        --ymc-filter-hover-background-color: " . esc_attr($filter_typo['hover_background_color']) . ";
	        --ymc-filter-justify-content: " . esc_attr($filter_typo['justify_content']) . ";
	        {$padding}
	        {$margin}
	
	        --ymc-post-title-font-family: {$post_title_font};
	        --ymc-post-title-font-size: " . esc_attr($post_typo['title']['font_size'] . $post_typo['title']['font_size_unit']) . ";
	        --ymc-post-title-font-weight: " . esc_attr($post_typo['title']['font_weight']) . ";
	        --ymc-post-title-text-transform: " . esc_attr($post_typo['title']['text_transform']) . ";
	        --ymc-post-title-line-height: " . esc_attr($post_typo['title']['line_height']) . ";
	        --ymc-post-title-letter-spacing: " . esc_attr($post_typo['title']['letter_spacing']) . ";
	        --ymc-post-title-color: " . esc_attr($post_typo['title']['title_color']) . ";
	
	        --ymc-post-meta-font-family: " . esc_attr($post_typo['meta']['font_family']) . ";
	        --ymc-post-meta-font-size: " . esc_attr($post_typo['meta']['font_size'] . $post_typo['meta']['font_size_unit']) . ";
	        --ymc-post-meta-font-weight: " . esc_attr($post_typo['meta']['font_weight']) . ";
	        --ymc-post-meta-color: " . esc_attr($post_typo['meta']['meta_color']) . ";
	
	        --ymc-post-excerpt-font-family: " . esc_attr($post_typo['excerpt']['font_family']) . ";
	        --ymc-post-excerpt-font-size: " . esc_attr($post_typo['excerpt']['font_size'] . $post_typo['excerpt']['font_size_unit']) . ";
	        --ymc-post-excerpt-font-weight: " . esc_attr($post_typo['excerpt']['font_weight']) . ";
	        --ymc-post-excerpt-line-height: " . esc_attr($post_typo['excerpt']['line_height']) . ";
	        --ymc-post-excerpt-font-style: " . esc_attr($post_typo['excerpt']['font_style']) . ";
	        --ymc-post-excerpt-transform: " . esc_attr($post_typo['excerpt']['text_transform']) . ";
	        --ymc-post-excerpt-letter-spacing: " . esc_attr($post_typo['excerpt']['letter_spacing']) . ";
	        --ymc-post-excerpt-color: " . esc_attr($post_typo['excerpt']['excerpt_color']) . ";
	
	        --ymc-post-link-font-family: {$post_link_font};
	        --ymc-post-link-font-size: " . esc_attr($post_typo['link']['font_size'] . $post_typo['link']['font_size_unit']) . ";
	        --ymc-post-link-font-weight: " . esc_attr($post_typo['link']['font_weight']) . ";
	        --ymc-post-link-transform: " . esc_attr($post_typo['link']['text_transform']) . ";
	        --ymc-post-link-letter-spacing: " . esc_attr($post_typo['link']['letter_spacing']) . ";
	        --ymc-post-link-color: " . esc_attr($post_typo['link']['link_color']) . ";
	    }
	    </style>";

		echo wp_kses(
			$style,
			[
				'style' => [
					'type' => true,
				],
			]
		);

		return ob_get_clean();
	}


	/**
	 * Get custom font
	 * @param array $typography
	 *
	 * @return string
	 */
	private static function get_custom_font(array $typography): string {
		return ($typography['font_family'] === 'custom' && !empty($typography['custom_font_family']))
			? esc_attr($typography['custom_font_family'])
			: esc_attr($typography['font_family']);
	}


	/**
	 * Render font link
	 * @param array $typography
	 *
	 * @return string
	 */
	private static function render_font_link(array $typography): string {
		if ($typography['font_family'] === 'custom' && !empty($typography['custom_font_url'])) {
			return '<link href="' . esc_url($typography['custom_font_url']) . '" rel="stylesheet">';
		}
		return '';
	}

	/**
	 * Get spacing
	 * @param string $type
	 * @param array $data
	 *
	 * @return string
	 */
	private static function get_spacing_vars(string $type, array $data): string {
		$out = '';
		foreach (['top', 'right', 'bottom', 'left'] as $side) {
			$val = !empty($data[$type][$side]) ? esc_attr($data[$type][$side]) . 'px' : '0px';
			$out .= "--ymc-filter-{$type}-{$side}: {$val};\n";
		}
		return $out;
	}


	/**
	 * Display admin bar menu
	 * @param $wp_admin_bar
	 *
	 * @return void
	 */
	public static function add_admin_bar_menu($wp_admin_bar) : void {
		$icon = '<span class="ab-icon dashicons dashicons-layout"></span>';
		$title = $icon . '<span class="ab-label">' . esc_html__('Filter & Grids', 'ymc-smart-filters') . '</span>';

		$wp_admin_bar->add_menu(array(
			'id'    => 'ymc-filter-grids',
			'title' => $title,
			'href'  => admin_url('edit.php?post_type=ymc_filters'),
			'meta'  => array( 'target' => '_blank' )
		));
	}




}

