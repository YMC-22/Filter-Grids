<?php declare( strict_types = 1 );
/**
 * FG Data Store.
 *
 * @since  3.0.0
 */

namespace YMCFilterGrids;

defined( 'ABSPATH' ) || exit;

/**
 * Data store class.
 */
class FG_Data_Store {

	/**
	 * Stores default values
	 * @var string[] Stores
	 */
	private static array $stores = [

		'ymc_fg_post_types'            => ['post'],

		'ymc_fg_taxonomies'            => [],

		'ymc_fg_terms'                 => [],

		'ymc_fg_tax_attrs'             => [],

		'ymc_fg_term_attrs'            => [],

		'ymc_fg_tax_sort'              => [],

		'ymc_fg_term_sort'             => [],

		'ymc_fg_selected_posts'        => [],

		'ymc_fg_excluded_posts'        => 'no',

		'ymc_fg_tax_relation'          => 'AND',

		'ymc_fg_filter_hidden'         => 'no',

		'ymc_fg_filter_type'           => 'default',

		'ymc_fg_post_layout'           => 'layout_standard',

		'ymc_fg_selection_mode'        => 'single',

		'ymc_fg_filter_options'        => [
			[
				'tax_name'    => ['category'],
				'filter_type' => 'default',
				'placement'   => 'top'
			]
		],

		'ymc_fg_display_terms_mode'    => 'selected_terms',

		'ymc_fg_term_sort_direction'   => 'ASC',

		'ymc_fg_term_sort_field'       => 'name',

		'ymc_fg_pagination_hidden'     => 'no',

		'ymc_fg_pagination_type'       => 'numeric',

		'ymc_fg_per_page'              => '4',

		'ymc_fg_filter_all_button'     => [
			'category' => [
				'all_label'  => 'All',
				'is_visible' => 'yes'
			]
		],

		'ymc_fg_prev_button_text'      => '',

		'ymc_fg_next_button_text'      => '',

		'ymc_fg_load_more_text'        => '',

		'ymc_fg_post_image_size'       => 'medium',

		'ymc_fg_image_clickable'       => 'no',

		'ymc_fg_truncate_post_excerpt' => 'excerpt_truncated_text',

		'ymc_fg_post_button_text'      => 'Read More',

		'ymc_fg_target_option'         => '_self',

		'ymc_fg_post_excerpt_length'   => 30,

		'ymc_fg_post_order'            => 'ASC',

		'ymc_fg_post_order_by'         => 'title',

		'ymc_fg_post_status'           => 'publish',

		'ymc_fg_no_results_message'    => 'No results found',

		'ymc_fg_post_animation_effect' => '',

		'ymc_fg_post_display_settings' => [
			'author'  => 'show',
			'date'    => 'show',
			'tags'    => 'show',
			'title'   => 'show',
			'image'   => 'show',
			'excerpt' => 'show',
			'button'  => 'show'
		],

		'ymc_fg_order_meta_key'        => '',

		'ymc_fg_order_meta_value'      => 'meta_value',

		'ymc_fg_post_order_by_multiple' => [],

		'ymc_fg_post_columns_layout'   => [
			'xl'  => 4,
			'lg'  => 4,
			'md'  => 3,
			'sm'  => 2,
			'xs'  => 1,
			'xxs' => 1
		],

		'ymc_fg_post_grid_gap'         => [
			'column_gap' => 30,
			'row_gap'    => 30
		],

		'ymc_fg_popup_enable'          => 'no',

		'ymc_fg_popup_settings'         => [
			'animation_type'     => 'fade_in',
			'position'           => 'center',
			'animation_origin'   => 'center center',
			'width'              => [
				'default' => 600,
				'unit' => 'px'
			],
			'height'             => [
				'default' => 600,
				'unit' => 'px'
			],
			'background_overlay' => 'rgba(20, 21, 24, 0.6)'
		],

		'ymc_fg_search_enable'          => 'no',

		'ymc_fg_search_placeholder'     => 'Search...',

		'ymc_fg_search_mode'            => 'global',

		'ymc_fg_submit_button_text'     => 'Search',

		'ymc_fg_results_found_text'     => 'results found',

		'ymc_fg_autocomplete_enabled'   => 'yes',

		'ymc_fg_search_meta_fields'     => 'no',

		'ymc_fg_exact_phrase'           => 'no',

		'ymc_fg_max_autocomplete_suggestions' => 10,

		'ymc_fg_filter_typography'      => [
			'font_family'    =>  'Montserrat',
			'custom_font_family' => '',
			'custom_font_url' => '',
			'font_weight'    =>  '400',
			'font_size'      =>  '16',
			'font_size_unit' =>  'px',
			'line_height'    =>  '1.4',
			'letter_spacing' =>  '1',
			'text_transform' =>  'none',
			'font_style'     =>  'normal',
			'background_color' =>  '#095c81',
			'color'          =>  '#ffffff',
			'active_color'   =>  '#ffffff',
			'active_background_color'  =>  '#1f1f1f',
			'hover_text_color'  =>  '#ffffff',
			'hover_background_color'  =>  '#095c81',
			'padding'  =>  [
				'top'    =>  '10',
				'right'  =>  '20',
				'bottom' =>  '10',
				'left'   =>  '20'
			],
			'margin'  =>  [
				'top'    =>  '0',
				'right'  =>  '10',
				'bottom' =>  '10',
				'left'   =>  '0'
			],
			'justify_content' => 'flex-start'
		],

		'ymc_fg_post_typography'        => [
			'title' => [
				'font_family'    =>  'Montserrat',
				'custom_font_family' => '',
				'custom_font_url' => '',
				'font_size'      =>  '24',
				'font_size_unit' =>  'px',
				'font_weight'    =>  '600',
				'text_transform' =>  'none',
				'line_height'    =>  '1.4',
				'letter_spacing' =>  '1',
				'title_color'    =>  '#1f1f1f',
			],
			'excerpt' => [
				'font_family'    =>  'Montserrat',
				'font_size'      =>  '16',
				'font_size_unit' =>  'px',
				'font_weight'    =>  '400',
				'line_height'    =>  '1.4',
				'font_style'     =>  'normal',
				'text_transform' =>  'none',
				'letter_spacing' =>  '1',
				'excerpt_color'  =>  '#1f1f1f',
			],
			'meta'    => [
				'font_family'    =>  'Montserrat',
				'font_size'      =>  '14',
				'font_size_unit' =>  'px',
				'font_weight'    =>  '400',
				'meta_color'     =>  '#1f1f1f',
			],
			'link'    => [
				'font_family'    =>  'Montserrat',
				'custom_font_family' => '',
				'custom_font_url' => '',
				'font_size'      =>  '16',
				'font_size_unit' =>  'px',
				'font_weight'    =>  '400',
				'text_transform' =>  'none',
				'line_height'    =>  '1.4',
				'letter_spacing' =>  '1',
				'link_color'    =>  '#1f1f1f',
			]
		],

		'ymc_fg_enable_advanced_query'  => 'no',

		'ymc_fg_advanced_query_type'    => 'advanced',

		'ymc_fg_advanced_query'         => '',

		'ymc_fg_query_allowed_callback' => '',

		'ymc_fg_advanced_suppress_filters'  => 'no',

		'ymc_fg_enable_sort_posts'      => 'no',

		'ymc_fg_post_sortable_fields' => ['name', 'date'],

		'ymc_fg_sort_dropdown_label' => 'Sort Posts By',

		'ymc_fg_custom_container_class' => '',

		'ymc_fg_extra_filter_type'      => 'default',

		'ymc_fg_extra_taxonomy'         => 'category',

		'ymc_fg_custom_css'             => '',

		'ymc_fg_custom_js'              => '',

		'ymc_fg_preloader_settings'     => [
			'icon'               => 'dual_arc',
			'filter_preloader'   => 'none',
			'custom_filters_css' => ''
		],

		'ymc_fg_scroll_to_filters_on_load' => 'no',

		'ymc_fg_debug_mode'             => 'no',

		'ymc_fg_grid_style'             => 'grid',

		'ymc_fg_carousel_settings'     => [
			'use_custom_settings'  => 'false',
			'general'    => [
				'auto_height'      => 'false',
				'autoplay'         => 'false',
				'autoplay_delay'   => 1000,
				'loop'             => 'true',
				'centered_slides'  => 'false',
				'slides_per_view'  => 1,
				'space_between'    => 20,
				'mousewheel'       => 'false',
				'speed'            => 500,
				'effect'           => 'slide',
			],
			'pagination' => [
				'visible'          => 'true',
				'dynamic_bullets'  => 'true',
				'type'             => 'bullets',
			],
			'navigation' => [
				'visible'          => 'true',
			],
			'scrollbar'  => [
				'visible'          => 'true',
			]
		]





	];


	/**
	 * Get meta value by key
	 * @param int $post_id
	 * @param string $key
	 *
	 * @return mixed|false
	 */
	public static function get_meta_value(int $post_id, string $key) {
		if($value = get_post_meta($post_id, $key, true )) {
			return $value;
		}
		return array_key_exists($key, self::$stores) ? self::$stores[$key] : false;
	}

	/**
	 * Get meta all values
	 * @param int $post_id
	 *
	 * @return array
	 */
	public static function get_all_meta_values(int $post_id) : array {
		$values = [];
		foreach(self::$stores as $key => $value) {
			$values[$key] = self::get_meta_value($post_id, $key);
		}
		$values['ymc_fg_post_id'] = $post_id;
		return $values;
	}



}