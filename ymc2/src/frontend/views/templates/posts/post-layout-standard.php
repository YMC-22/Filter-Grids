<?php

use YMCFilterGrids\FG_Data_Store as Data_Store;

defined( 'ABSPATH' ) || exit;

$post_number = $paged === 1 ? 1 : ($per_page * ( $paged - 1)) + 1;

$length_excerpt        = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_excerpt_length');
$post_image_size       = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_image_size');
$is_image_clickable    = Data_Store::get_meta_value($filter_id, 'ymc_fg_image_clickable');
$mode_excerpt          = Data_Store::get_meta_value($filter_id, 'ymc_fg_truncate_post_excerpt');
$button_text           = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_button_text');
$target_option         = Data_Store::get_meta_value($filter_id, 'ymc_fg_target_option');
$post_display_settings = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_display_settings');
$animation_class       = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_animation_effect');
$animation_class       = $animation_class ? ' ' . esc_attr($animation_class) : '';
$popup_enable          = Data_Store::get_meta_value($filter_id, 'ymc_fg_popup_enable');
$popup_class_trigger   = $popup_enable === 'no' ? '' : ' js-ymc-popup-trigger';

while ($query->have_posts()) : $query->the_post();

	$post_id               = get_the_ID();
	$post_title            = get_the_title($post_id);
	$post_link             = get_the_permalink($post_id);

	$post_date_format      = 'd, M Y';
	$post_date_format      = apply_filters('ymc/post/layout/date_format',  $post_date_format);
	$post_date_format      = apply_filters('ymc/post/layout/date_format_'. $filter_id, $post_date_format);
	$post_date_format      = apply_filters('ymc/post/layout/date_format_'. $filter_id .'_'. $counter, $post_date_format);

    $post_date             = get_the_date($post_date_format, $post_id);
    $post_author           = get_the_author($post_id);
    $post_excerpt          = get_the_excerpt($post_id);
	$all_terms             = ymc_get_all_post_terms($post_id);
	$tag_list              = '';
	$post_image            = ymc_post_image_size($post_id, $post_image_size);

	foreach ($all_terms as $term) {
		$tag_list .= '<a class="tag tag-' . esc_attr($term['slug']) . '" href="'. esc_url($term['link']) .'" 
		    target="'. esc_attr($target_option) .'" aria-label="'. esc_attr($term['name']) .'">';
		$tag_list .= esc_html($term['name']);
		$tag_list .= '</a>';
	}

	$post_content = ymc_truncate_post_content($post_id, $mode_excerpt, $length_excerpt);

	do_action( "ymc/post/layout/before/post_item", $post_number, $post_id, $paged, $per_page );
	do_action( "ymc/post/layout/before/post_item_". esc_attr($filter_id), $post_number, $post_id, $paged, $per_page );
	do_action( "ymc/post/layout/before/post_item_". esc_attr($filter_id).'_'. esc_attr($counter), $post_number, $post_id, $paged, $per_page );

	?>

    <article class="post-card post-<?php echo esc_attr($post_layout); ?> post-<?php echo esc_attr($post_id); ?><?php echo esc_attr($animation_class); ?>">
        <div class="post-card__inner js-grid-item">

	        <?php if( $post_display_settings['image'] === 'show') : ?>
                <div class="post-card__image">
			        <?php echo ( $is_image_clickable === 'yes' )
				        ? '<a class="post-card__title-link'. esc_attr($popup_class_trigger) .'" 
                        href="'. esc_url($post_link) .'" 
                        target="'. esc_attr($target_option) .'"
                        data-grid-id="'. esc_attr($filter_id).'"
                        data-post-id="'. esc_attr($post_id) .'"
                        data-counter="'. esc_attr($counter) .'"
                        aria-label="'. esc_attr($post_title) .'">'.
				        // phpcs:ignore WordPress
                        $post_image .'</a>'
				        :
				        // phpcs:ignore WordPress
                        $post_image;
			        ?>
                </div>
	        <?php endif; ?>

	        <?php if( $post_display_settings['tags'] === 'show') : ?>
                <div class="post-card__tags">
			        <?php echo wp_kses_post($tag_list); ?>
                </div>
	        <?php endif; ?>

	        <?php if( $post_display_settings['title'] === 'show') : ?>
                <h2 class="post-card__title">
                    <a class="post-card__title-link<?php echo esc_attr($popup_class_trigger); ?>"
                       href="<?php echo esc_url($post_link); ?>"
                       target="<?php echo esc_attr($target_option); ?>"
                       data-grid-id="<?php echo esc_attr($filter_id); ?>"
                       data-post-id="<?php echo esc_attr($post_id); ?>"
                       data-counter="<?php echo esc_attr($counter); ?>">
				        <?php echo esc_html($post_title); ?>
                    </a>
                </h2>
	        <?php endif; ?>

            <div class="post-card__meta">
		        <?php if( $post_display_settings['date'] === 'show') : ?>
                    <div class="post-date">
                        <span class="far fa-calendar-alt"></span>
                        <span class="data-text"><?php echo wp_kses_post($post_date); ?></span>
                    </div>
		        <?php endif; ?>
		        <?php if( $post_display_settings['author'] === 'show') : ?>
                    <div class="post-author">
                        <span class="far fa-user"></span>
                        <span class="author-text"><?php echo wp_kses_post($post_author); ?></span>
                    </div>
		        <?php endif; ?>
            </div>

	        <?php if( $post_display_settings['excerpt'] === 'show') : ?>
                <div class="post-card__excerpt">
			        <?php echo wp_kses_post($post_content); ?>
                </div>
	        <?php endif; ?>

	        <?php if( $post_display_settings['button'] === 'show') : ?>
                <a class="post-card__read-more<?php echo esc_attr($popup_class_trigger); ?>"
                   href="<?php echo esc_url($post_link); ?>"
                   target="<?php echo esc_attr($target_option); ?>"
                   data-grid-id="<?php echo esc_attr($filter_id); ?>"
                   data-post-id="<?php echo esc_attr($post_id); ?>"
                   data-counter="<?php echo esc_attr($counter); ?>">
			        <?php echo esc_html( $button_text); ?></a>
	        <?php endif; ?>

        </div>
    </article>

<?php

	do_action( "ymc/post/layout/after/post_item", $post_number, $post_id, $paged, $per_page );
	do_action( "ymc/post/layout/after/post_item_". esc_attr($filter_id), $post_number, $post_id, $paged, $per_page );
	do_action( "ymc/post/layout/after/post_item_". esc_attr($filter_id).'_'. esc_attr($counter), $post_number, $post_id, $paged, $per_page );

	$post_number++;

endwhile;

?>
