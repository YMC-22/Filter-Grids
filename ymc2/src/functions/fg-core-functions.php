<?php

use YMCFilterGrids\FG_Data_Store as Data_Store;

/**
 * FG Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get all post types
 * @param array $exclude_posts Array of post types to exclude
 *
 * @return array
 */
if (! function_exists( 'ymc_get_post_types' )) {
	function ymc_get_post_types($exclude_posts = []) {
		$post_types = get_post_types( [ 'public' => true ], 'names' );
		if( count($exclude_posts) > 0 ) {
			foreach ( $exclude_posts as $value ) {
				$pos = array_search( $value, $post_types );
				unset($post_types[$pos]);
			}
		}
		ksort( $post_types, SORT_ASC );
		return $post_types;
	}
}

/**
 * Get all taxonomies
 * @param $post_types
 *
 * @return array
 */
if (! function_exists( 'ymc_get_taxonomies')) {
	function ymc_get_taxonomies($post_types = []) {
		$result = [];
		$taxonomies = get_object_taxonomies($post_types, 'objects');
		if( !empty($taxonomies) ) {
			foreach ( $taxonomies as $tax ) {
				$result[$tax->name] = $tax->label;
			}
		}
		asort($result);
		return $result;
	}
}

/**
 * Get posts ids
 * @param $post_types
 * @param $posts_per_page
 *
 * @return array
 */
if(! function_exists( 'ymc_get_posts_ids')) {
	function ymc_get_posts_ids($post_types = [], $posts_per_page = 20) {
		$found_posts = 0;
		$posts_ids = [];
		if(!empty($post_types)) {
			$arg = [
				'post_type' => $post_types,
				'posts_per_page' => $posts_per_page,
				'orderby' => 'title',
				'order' => 'ASC'
			];
			$query = new \WP_query($arg);
			$found_posts = $query->found_posts;
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$posts_ids[] = get_the_ID();
				}
			}
		}
		$data['found_posts'] = $found_posts;
		$data['posts_ids'] = $posts_ids;

		return $data;
	}
}

/**
 * Render field header
 * @param $label
 * @param $tooltip
 *
 * @return void
 */
if(! function_exists( 'ymc_render_field_header')) {
	function ymc_render_field_header($label, $tooltip) {
		$tooltip = preg_replace('/\s+/', ' ', trim($tooltip))
        ?>
		<header class="form-label">
			<span class="heading-text"><?php echo esc_html($label); ?></span>
			<button type="button" class="btn-tooltip js-btn-tooltip"
               data-tooltip-html="<?php echo esc_attr($tooltip); ?>"
               title="<?php echo esc_attr($tooltip); ?>">
			   <i class="fa-solid fa-question"></i>
			</button>
		</header>
		<?php
	}
}


/**
 * Debug in Console
 */

if (! function_exists( 'ymc_js_console_log')) {
	function ymc_js_console_log( $x, $as_text = true ) {
		$str = '<div class="php-to-js-console-log" style="display: none !important;" data-as-text="' . esc_attr( (bool) $as_text ) .
		       '" data-variable="' . htmlspecialchars( wp_json_encode( $x ) ) . '">' . htmlspecialchars( var_export( $x, true ) ) . '</div>';
		echo wp_kses($str, ['div' => ['class' => true, 'style' => true, 'data-as-text' => true, 'data-variable' => true]]);
	}

	if ( function_exists( 'ymc_js_console_log' ) ) {
		add_action( 'wp_footer', function () {
			echo '<script type="text/javascript">jQuery(document).ready(function ($) { 
    		$(".php-to-js-console-log").each(function (i, el) { let $e = $(el); console.log("PHP debug is below:"); 
            (!$e.attr("data-as-text")) ? console.log(JSON.parse($e.attr("data-variable"))) : console.log($e.text()); }); });</script>';
		}, 99999 );
	}
}


/**
 * Assembles the filter options structure from POST data..
 *
 * @param int $post_id
 * @param string $filter_type
 * @param array $filter_options
 * @return array
 */
if (! function_exists( 'ymc_build_filter_options_from_post')) {
	function ymc_build_filter_options_from_post(int $post_id, string $filter_type, array $filter_options): array {
		$options = [];

		if (!empty($filter_type)) {
			if('composite' !== $filter_type) {
				$tax_name = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
				$tax_name = !empty($tax_name) ? array_map('sanitize_text_field', $tax_name) : [];

				$options[] = [
					'tax_name'    => $tax_name,
					'filter_type' => $filter_type,
					'placement'   => 'top',
				];
			}
			else {
				if($filter_options) {
					foreach ($filter_options as $option) {
						$tax_name = !empty( $option['tax_name'] ) && is_array($option['tax_name'])
                            ? array_map( 'sanitize_text_field', $option['tax_name'] )
                            : ( $option['filter_type'] === 'date_picker' ? ['date_picker'] : [] );

						$filter_type = !empty($option['filter_type'])
							? sanitize_text_field($option['filter_type'])
							: 'default';

						$placement = !empty($option['placement'])
							? sanitize_text_field($option['placement'])
							: 'top';

						$options[] = [
							'tax_name'    => $tax_name,
							'filter_type' => $filter_type,
							'placement'   => $placement
						];
					}
				}
			}
		}

		return $options;
	}
}


/**
 * Sanitize array recursively
 * @param $array
 *
 * @return mixed
 */
if (! function_exists( 'ymc_sanitize_array_recursive')) {
	function ymc_sanitize_array_recursive($array) {
		foreach ($array as $key => &$value) {
			if (is_array($value)) {
				$value = ymc_sanitize_array_recursive($value);
			} else {
				$value = sanitize_text_field($value);
			}
		}
		return $array;
	}
}


/**
 * Gets all terms (tags, categories, etc.) of all taxonomies to which the specified post is attached.
 *
 * @param int $post_id ID поста.
 *
 * @return array An array of terms. Each element contains:
 *               - name (string)  Name of the term
 *               - slug (string)  Term slug
 *               - taxonomy (string) Taxonomy name
 *               - term_id (int)  ID term
 *               - link (string)  URL of the link to the term archive
 */
if (! function_exists( 'ymc_get_all_post_terms')) {
	function ymc_get_all_post_terms( $post_id ) {
		if ( ! $post_id || ! get_post( $post_id ) ) {
			return [];
		}

		$post_type  = get_post_type( $post_id );
		$taxonomies = get_object_taxonomies( $post_type );
		$all_terms  = [];

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post_id, $taxonomy );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$all_terms[] = [
						'name'     => $term->name,
						'slug'     => $term->slug,
						'taxonomy' => $term->taxonomy,
						'term_id'  => $term->term_id,
						'link'     => get_term_link( $term )
					];
				}
			}
		}

		return $all_terms;
	}
}


/**
 * Truncates post content.
 *
 * @param int $post_id ID поста.
 * @param string $mode_excerpt Mode excerpt.
 * @param int $length Length excerpt.
 */

if (! function_exists( 'ymc_truncate_post_content')) {
    function ymc_truncate_post_content( $post_id, $mode_excerpt, $length = 30 ) {

	    $post_content = has_excerpt($post_id)
		    ? get_the_excerpt($post_id)
		    : apply_filters('the_content', get_the_content($post_id));

	    $post_content = preg_replace('/\[[^\]]+\]/', '', $post_content);

	    switch( $mode_excerpt ) {

		    case 'excerpt_first_block':
			    if ( preg_match('/<(p|h[1-6])[^>]*>(.*?)<\/\1>/is', $post_content, $matches) ) {
				    $first_block = wp_strip_all_tags( $matches[0] );
				    $length_excerpt = strlen($first_block);
				    $post_content = wp_trim_words( $first_block, $length_excerpt );
			    } else {
				    // fallback: use trimmed full content
				    $post_content = wp_trim_words( wp_strip_all_tags( $post_content ), $length );
			    }
			    break;

		    case 'excerpt_line_break':
			    if ( preg_match('/^(.+?)(<br\s*\/?>|\n)/i', $post_content, $matches) ) {
				    $post_content = wp_strip_all_tags( $matches[1] );
			    } else {
				    $post_content = wp_trim_words( wp_strip_all_tags( $post_content ), $length );
			    }
			    break;

		    default:
			    $post_content = wp_trim_words( wp_strip_all_tags( $post_content ), $length );
			    break;
	    }

        return $post_content;

    }
}


/**
 * Gets post image.
 *
 * @param int $post_id ID поста.
 * @param string $post_image_size Post image size.
 */

if (! function_exists( 'ymc_post_image_size')) {
	function ymc_post_image_size($post_id, $post_image_size) {
		if ( !has_post_thumbnail($post_id) ) {
			return '';
		}
		$sizes = [
			'thumbnail' => ['thumbnail', 'is-thumbnail'],
			'medium'    => ['medium', 'is-medium'],
			'full'      => ['large', 'is-full'],
		];

		[$size, $class] = $sizes[$post_image_size] ?? ['full', 'is-large'];

		return get_the_post_thumbnail($post_id, $size, ['class' => $class, 'alt' => get_the_title($post_id)]);
	}

}

/**
 * Get column classes
 *
 * @param $columns
 */
if (! function_exists( 'ymc_get_column_classes')) {
	function ymc_get_column_classes( $columns ): string {
		$output = [];
		foreach ( $columns as $breakpoint => $count ) {
			if ( $count ) {
				$output[] = "ymc-cols-{$breakpoint}-{$count}";
			}
		}
		$output = array_reverse($output);

		return implode( ' ', $output );
	}
}


/**
 * Render single popup
 *
 * @param $post_id
 */
if (! function_exists( 'ymc_render_single_popup')) {
	function ymc_render_single_popup($filter_id) {
		$settings = Data_Store::get_meta_value($filter_id, 'ymc_fg_popup_settings');
		if (empty($settings)) return;

		$width            = esc_attr($settings['width']['default'] ?? '600');
		$height           = esc_attr($settings['height']['default'] ?? '600');
        $width_unit       = esc_attr($settings['width']['unit'] ?? 'px');
        $height_unit      = esc_attr($settings['height']['unit'] ?? 'px');
        $transform_origin = esc_attr($settings['animation_origin'] ?? 'center center');
        $position         = esc_attr($settings['position'] ?? 'center center');
        $animation_type   = esc_attr($settings['animation_type'] ?? 'none');
        $background_overlay = !empty($settings['background_overlay']) ?
	        esc_attr($settings['background_overlay']) : 'rgba(20, 21, 24, 0.6)';

        $css = '';
        $css .= 'width:'. $width . $width_unit . ';';
        $css .= 'height:' . $height . $height_unit .';';
        $css .= 'transform-origin:'. $transform_origin .';';

		$class_popup_position = '';

        if( $position === 'center_right' ) {
	        $class_popup_position = 'ymc-popup-right ymc-animation-' . $animation_type;
        }
        if( $position === 'center_left' ) {
	        $class_popup_position = 'ymc-popup-left ymc-animation-' . $animation_type;
        }
		if( $position === 'center' ) {
			$class_popup_position = 'ymc-animation-' . $animation_type;
		}

		echo '<div id="ymc-popup-' . esc_attr($filter_id) . '" class="ymc-popup ymc-popup-overlay js-ymc-popup-overlay" style="background-color:'. esc_attr($background_overlay) .'">';
		echo '<div class="ymc-popup__wrapper '. esc_attr($class_popup_position).' js-ymc-popup-wrapper" style="'. esc_attr($css).'">
                <button class="ymc-popup__close js-ymc-btn-popup-close">close</button>
                <div class="ymc-popup__container">                    
                    <div class="ymc-popup__body js-ymc-popup-body"></div>
                </div>';
		echo '</div>';
		echo '</div>';
	}
}


/**
 * Minify CSS
 * @param $css
 */
if (! function_exists( 'ymc_minify_css')) {
	function ymc_minify_css($css) {
		$css = str_replace(["\t", "\n", "\r"], '', $css);
		$css = preg_replace('/\s+/', ' ', $css);
		$css = preg_replace('/\s*([{};:,])\s*/', '$1', $css);
		$css = preg_replace('/;}/', '}', $css);

		return trim($css);
	}
}


/**
 * Get term settings for the current post.
 *
 * @param int $post_id
 * @param array $terms_attr
 * @return array
 */
if (! function_exists( 'ymc_get_post_terms_settings')) {
	function ymc_get_post_terms_settings(int $post_id, array $terms_attr): array {
		$post_terms_settings = [];
		$taxonomies = get_taxonomies(['public' => true], 'names');
		$post_term_ids = [];

		foreach ($taxonomies as $taxonomy) {
			$terms = get_the_terms($post_id, $taxonomy);

			if (!is_wp_error($terms) && !empty($terms)) {
				foreach ($terms as $term) {
					$post_term_ids[] = (int) $term->term_id;
				}
			}
		}

		$post_term_ids = array_unique($post_term_ids);

		foreach ($terms_attr as $term_setting) {
			if (in_array((int)$term_setting['term_id'], $post_term_ids, true)) {
				$post_terms_settings[(int)$term_setting['term_id']] = $term_setting;
			}
		}

		return $post_terms_settings;
	}
}
















