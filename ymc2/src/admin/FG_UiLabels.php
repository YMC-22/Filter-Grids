<?php

namespace YMCFilterGrids\admin;

/**
 * Class FG_UiLabels
 * UI labels
 * @package YMCFilterGrids
 * @since 3.0.0
 */
class FG_UiLabels {

	/**
	 * UI labels
	 * @since 3.0.0
	 * @var array|array[]
	 */
	protected static array $labels = [
		'filter_types' => [
			'default'         => 'Default',
			'dropdown'        => 'Dropdown',
			'range_slider'    => 'Range Slider',
			'date_picker'     => 'Date Picker',
			'custom'          => 'Custom filter',
			'composite'       => 'Combined filter'
		],
		'placements' => [
			'top'    => 'Top',
			'left'   => 'Left',
			'right'  => 'Right'
		],
		'post_layouts' => [
			'layout_standard' => 'Standard post layout',
			'layout_carousel' => 'Carousel post layout',
			'layout_custom'   => 'Custom post layout'
		],
		'display_terms_mode' => [
			'selected_terms'             => 'Selected terms',
			'selected_terms_hide_empty'  => 'Selected terms (Hide Empty)',
			'all_terms'                  => 'All terms (Auto Populate)',
			'all_terms_hide_empty'       => 'All terms (Hide Empty)'
		],
		'term_sort_direction' => [
			'asc'    => 'Ascending (A → Z)',
			'desc'   => 'Descending (Z → A)',
			'manual' => 'Manual (Custom Order)'
		],
		'term_sort_field' => [
			'name'           => 'Name',
			'id'             => 'ID',
			'count'          => 'Count',
			'slug'           => 'Slug',
			'description'    => 'Description',
			'term_group'     => 'Term group',
			'parent'         => 'Parent',
			'include'        => 'Include',
			'slug__in'       => 'Slug in',
			'meta_value'     => 'Meta value',
			'meta_value_num' => 'Meta value number',
			'none'           => 'None'
		],
		'pagination_type' => [
			'numeric'     => 'Numeric',
			'loadmore'    => 'Load more',
			'infinite'    => 'Infinite scroll',
		],
		'post_image_size' => [
			'thumbnail' => 'Thumbnail',
			'medium'    => 'Medium',
			'large'     => 'Large',
			'full'      => 'Full'
		],
		'truncate_post_excerpt' => [
			'excerpt_truncated_text' => 'Truncated text',
			'excerpt_first_block'    => 'The first block of content',
			'excerpt_line_break'     => 'At the first line break'
		],
		'target_option' => [
			'_self'  => 'Same tab',
			'_blank' => 'New tab'
		],
		'post_order' => [
			'ASC'  => 'Ascending',
			'DESC' => 'Descending'
		],
		'post_order_by' => [
			'title'      => 'Title',
			'name'       => 'Name',
			'date'       => 'Date',
			'ID'         => 'ID',
			'author'     => 'Author',
			'modified'   => 'Modified',
			'type'       => 'Type',
			'parent'     => 'Parent',
			'rand'       => 'Random',
			'menu_order' => 'Menu order',
			'meta_key'   => 'Meta key',
			'multiple_fields' => 'Multiple sort'
		],
		'post_status' => [
			'publish'    => 'Publish',
			'pending'    => 'Pending',
			'draft'      => 'Draft',
			'future'     => 'Future',
			'private'    => 'Private',
			'inherit'    => 'Inherit',
			'trash'      => 'Trash',
			'any'        => 'Any',
			'auto-draft' => 'Auto Draft'
		],
		'post_animation_effect' => [
			''                 => 'None',
			'ymc-anim--bounce'       => 'Bounce',
			'ymc-anim--bounce-in'    => 'Bounce in',
			'ymc-anim--fade-in'      => 'Fade in',
			'ymc-anim--fade-in-down' => 'Fade in down',
			'ymc-anim--grow'         => 'Grow',
			'ymc-anim--hit-here'     => 'Hit here',
			'ymc-anim--swing'        => 'Swing',
			'ymc-anim--shake'        => 'Shake',
			'ymc-anim--wobble'       => 'Wobble',
			'ymc-anim--zoom-in-out'  => 'Zoom in out'
		],
		'post_display_settings' => [
			'author'  => ['label' => 'Author', 'value' => 'show', 'tooltip' => 'Show or hide the author'],
			'date'    => ['label' => 'Date',   'value' => 'show', 'tooltip' => 'Show or hide the date'],
			'tags'    => ['label' => 'Tags',   'value' => 'show', 'tooltip' => 'Show or hide the tags'],
			'title'   => ['label' => 'Title',  'value' => 'show', 'tooltip' => 'Show or hide the title'],
			'image'   => ['label' => 'Image',  'value' => 'show', 'tooltip' => 'Show or hide the image'],
			'excerpt' => ['label' => 'Excerpt','value' => 'show', 'tooltip' => 'Show or hide the excerpt'],
			'button'  => ['label' => 'Button', 'value' => 'show', 'tooltip' => 'Show or hide the button']
		],
		'post_columns_layout' => [
			'xl'  => '≥ 1200px',
			'lg'  => '≥ 992px',
			'md'  => '≥ 768px',
			'sm'  => '≥ 576px',
			'xs'  => '< 576px',
			'xxs' => '< 400px'
		],
		'popup_fields' => [
			'animation_type' => [
				'none'     => 'None',
				'fade_in'  => 'Fade In',
				'rotate'   => 'Rotate',
				'zoom_in'  => 'Zoom In',
				'slide'    => 'Slide',
			],
			'position' => [
				'center'        => 'Center',
				'center_left'   => 'Center Left',
				'center_right'  => 'Center Right',
			],
			'animation_origin' => [
				'top'           => 'Top',
				'left'          => 'Left',
				'bottom'        => 'Bottom',
				'right'         => 'Right',
				'left top'      => 'Left Top',
				'center top'    => 'Center Top',
				'right top'     => 'Right Top',
				'left center'   => 'Left Center',
				'center center' => 'Center Center',
				'right center'  => 'Right Center',
				'left bottom'   => 'Left Bottom',
				'center bottom' => 'Center Bottom',
				'right bottom'  => 'Right Bottom'
			],
			'width' => [
				'default' => 600,
				'unit'    => [
					'px'  => 'px',
					'%'   => '%',
					'rem' => 'rem',
					'em'  => 'em',
					'vw'  => 'vw'
				]
			],
			'height' => [
				'default' => 600,
				'unit'    => [
					'px'  => 'px',
					'%'   => '%',
					'rem' => 'rem',
					'em'  => 'em',
					'vh'  => 'vh'
				]
			],
			'background_overlay' => 'rgba(20, 21, 24, 0.6)'
		],
		'search_mode' => [
			'global'   => 'Global search',
			'filtered' => 'Search by filtered posts'
		],
		'filter_typography' => [
			'font_family'     => [
				'inherit'    => 'Inherit',
				'OpenSans'   => 'OpenSans',
				'Montserrat' => 'Montserrat',
				'Poppins'    => 'Poppins',
				'Roboto'     => 'Roboto',
				'custom'     => 'Custom'
			],
			'font_weight'     => [
				'200' => 'Extra Light (200)',
				'300' => 'Light (300)',
				'400' => 'Normal (400)',
				'500' => 'Medium (500)',
				'600' => 'Semi Bold (600)',
				'700' => 'Bold (700)',
				'800' => 'Extra Bold (800)'
			],
			'font_size_unit'  => [
				'px'  => 'px',
				'em'  => 'em',
				'rem' => 'rem',
				'%'   => '%'
			],
			'text_transform' =>  [
				'none'       => 'none',
				'capitalize' => 'capitalize',
				'uppercase'  => 'uppercase',
				'lowercase'  => 'lowercase'
			],
			'font_style' => [
				'normal' => 'Normal',
				'italic' => 'Italic'
			],
			'justify_content' => [
				'flex-start'    => 'Left',
				'center'        => 'Center',
				'flex-end'      => 'Right',
				'space-between' => 'Space Between',
				'space-around'  => 'Space Around',
				'space-evenly'  => 'Space Evenly',
			]
		],
		'post_typography' => [
			'font_family'     => [
				'inherit'    => 'Inherit',
				'OpenSans'   => 'OpenSans',
				'Montserrat' => 'Montserrat',
				'Poppins'    => 'Poppins',
				'Roboto'     => 'Roboto',
				'custom'     => 'Custom'
			],
			'font_weight'     => [
				'200' => 'Extra Light (200)',
				'300' => 'Light (300)',
				'400' => 'Normal (400)',
				'500' => 'Medium (500)',
				'600' => 'Semi Bold (600)',
				'700' => 'Bold (700)',
				'800' => 'Extra Bold (800)'
			],
			'font_size_unit'  => [
				'px'  => 'px',
				'em'  => 'em',
				'rem' => 'rem',
				'%'   => '%'
			],
			'text_transform'  =>  [
				'none'       => 'none',
				'capitalize' => 'capitalize',
				'uppercase'  => 'uppercase',
				'lowercase'  => 'lowercase'
			],
			'font_style'      => [
				'normal' => 'Normal',
				'italic' => 'Italic'
			]
		],
		'query_type' => [
			'advanced' => 'Advanced',
			'callback'    => 'Callback',
		],
		'post_sortable_fields' => [
			'ID' => 'ID',
			'author' => 'Author',
			'title'  => 'Title',
			'name'   => 'Name',
			'date'   => 'Date',
			'modified'   => 'Modified',
			'type'   => 'Type',
			'parent' => 'Parent',
			'rand' => 'Rand',
			'comment_count' => 'Comment count',
			'menu_order' => 'Menu order',
			'meta_value' => 'Meta value',
			'meta_value_num' => 'Meta value num',
		],
		'preloader_icons' => [
			'dual_arc'       => 'Dual Arc Spinner',
			'orbit_spinner'  => 'Orbit Spinner',
			'pulsing_dots'   => 'Pulsing Dots',
			'filling_square' => 'Filling Square',
			'rotating_paths' => 'Rotating Paths Loader',
			'bouncing_bars'  => 'Bouncing Bars Loader',
			'jumping_bars'   => 'Jumping Bars',
			'rotating_lines' => 'Rotating Lines Spinner',
			'wave_curve'     => 'Wave Curve Spinner',
			'gear_rotate'  => 'Gear Rotate Spinner',
			'ripple_pulse' => 'Ripple Pulse Loader',
			'sliding_dots' => 'Sliding Dots Loader',
			'segment_ring' => 'Segment Ring Spinner',
			'bouncing_squares' => 'Bouncing Squares Loader',
			'fading_squares' => 'Fading Squares Loader',
			'gear_spinner' => 'Gear Spinner',
			'none'           => 'None'
		],
		'filter_preloader' => [
			'none'           => 'None',
			'brightness'     => 'Brightness',
			'contrast'       => 'Contrast',
			'grayscale'      => 'Grayscale',
			'invert'         => 'Invert',
			'opacity'        => 'Opacity',
			'saturate'       => 'Saturate',
			'sepia'          => 'Sepia',
			'custom_filter'  => 'Custom Filter'
		],
		'grid_style' => [
			'grid'    => 'Standard Grid',
			'masonry' => 'Masonry'
		],
		'ymc_fg_carousel_settings'     => [
			'general' => [
				'auto_height'      => [ 'true', 'false'],
				'autoplay'         => [ 'true', 'false'],
				'autoplay_delay'   => [ 300, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000 ],
				'loop'             => [ 'true', 'false'],
				'centered_slides'  => [ 'true', 'false'],
				'slides_per_view'  => [ 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5 ],
				'space_between'    => [ 0, 20, 40, 60, 80, 100 ],
				'mousewheel'       => [ 'true', 'false'],
				'speed'            => [ 200, 300, 400, 500, 600, 700, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000 ],
				'effect'           => [ 'slide' => 'Slide', 'fade' => 'Fade', 'cube' => 'Cube', 'coverflow' => 'Coverflow', 'flip' => 'Flip', 'cards' => 'Cards', 'creative' => 'Creative'],
			],
			'pagination' => [
				'visible'          => [ 'true', 'false'],
				'dynamic_bullets'  => [ 'true', 'false'],
				'type'             => [ 'bullets' => 'Bullets', 'fraction' => 'Fraction', 'progressbar' => 'Progressbar' ],
			],
			'navigation' => [
				'visible'          => [ 'true', 'false'],
			],
			'scrollbar' => [
				'visible'          => [ 'true', 'false'],
			]
		]




	];

	/**
	 * Get label by category and key
	 */
	public static function get(string $category, string $key, string $default = ''): string
	{
		$value = self::$labels[$category][$key] ?? $default;
		// phpcs:ignore WordPress
		return __($value, 'ymc-smart-filters');
	}

	/**
	 * Get all labels of a specific category
	 */
	public static function all(string $category): array
	{
		$items = self::$labels[$category] ?? [];
		// phpcs:ignore WordPress
		return array_map(fn($label) => __($label, 'ymc-smart-filters'), $items);
	}

}