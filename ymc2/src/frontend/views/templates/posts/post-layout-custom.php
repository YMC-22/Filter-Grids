<?php

use YMCFilterGrids\FG_Data_Store as Data_Store;

defined( 'ABSPATH' ) || exit;

$post_number         = $paged === 1 ? 1 : ($per_page * ( $paged - 1)) + 1;
$terms_attr          = Data_Store::get_meta_value($filter_id, 'ymc_fg_term_attrs');
$animation_class     = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_animation_effect');
$animation_class     = $animation_class ? ' ' . esc_attr($animation_class) : '';
$popup_enable        = Data_Store::get_meta_value($filter_id, 'ymc_fg_popup_enable');
$popup_class_trigger = $popup_enable === 'no' ? '' : ' js-ymc-popup-trigger';
$popup_class         = $popup_class_trigger;

while ($query->have_posts()) : $query->the_post();

	$post_id = get_the_ID();
	$post_term_settings = ymc_get_post_terms_settings($post_id, $terms_attr);

	// Generate HTML block guide
	$guide_html  = '<div class="filter-custom-guide">';
	$guide_html .= '<div class="filter-usage">';
	$guide_html .= '<div class="filter-usage-inner">';
	$guide_html .= '<span class="headline">'. esc_html__("Use a filter:", "ymc-smart-filters") .'</span>';
	$guide_html .= '<span class="description">add_filter("ymc/post/layout/custom", "callback_function", 10, 5);</span>';
	$guide_html .= '<span class="description">add_filter("ymc/post/layout/custom_'. esc_html($filter_id) .'", "callback_function", 10, 5);</span>';
	$guide_html .= '<span class="description">add_filter("ymc/post/layout/custom_'. esc_html($filter_id) .'_'. esc_html($counter) .'", "callback_function", 10, 5);</span>';
	$guide_html .= '</div>';
	$guide_html .= '<div class="filter-usage-inner">';
	$guide_html .= '<a class="link" target="_blank" href="https://github.com/YMC-22/Filter-Grids/tree/main?tab=readme-ov-file#custom-post-layout">'. esc_html__("See documentation", "ymc-smart-filters") .'</a>';
	$guide_html .= '</div>';
	$guide_html .= '</div>';
	$guide_html .= '</div>';

	// Apply filters to this part
	$filter_keys = [
		"ymc/post/layout/custom",
		"ymc/post/layout/custom_{$filter_id}",
		"ymc/post/layout/custom_{$filter_id}_" . $counter
	];

	/**
	 * Custom filter block HTML filter.
	 *
	 * @param string $guide_html
     * @param int $post_id
	 * @param int $filter_id
	 * @param array $settings
	 *
	 * @return string Modified HTML of the filter block.
	 */
	foreach ($filter_keys as $hook_name) {
		$guide_html = apply_filters($hook_name, $guide_html, $post_id, $filter_id, $popup_class, $post_term_settings);
	}

	do_action("ymc/post/layout/before/post_item", $post_number, $post_id, $paged, $per_page );
	do_action("ymc/post/layout/before/post_item_". esc_attr($filter_id), $post_number, $post_id, $paged, $per_page );
	do_action("ymc/post/layout/before/post_item_". esc_attr($filter_id).'_'. esc_attr($counter), $post_number, $post_id, $paged, $per_page );

?>
    <article class="post-card post-<?php echo esc_attr($post_layout); ?> post-<?php echo esc_attr($post_id); ?><?php echo esc_attr($animation_class); ?>">
        <?php
        // phpcs:ignore WordPress
        echo $guide_html; ?>
	</article>

<?php

	do_action("ymc/post/layout/after/post_item", $post_number, $post_id, $paged, $per_page);
	do_action("ymc/post/layout/after/post_item_". esc_attr($filter_id), $post_number, $post_id, $paged, $per_page);
	do_action("ymc/post/layout/after/post_item_". esc_attr($filter_id).'_'. esc_attr($counter), $post_number, $post_id, $paged, $per_page);

$post_number++;

endwhile;

?>
