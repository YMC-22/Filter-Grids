<?php
/**
 * Template Container: Right
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="filter-posts-wrapper">
	<?php \YMCFilterGrids\frontend\FG_Components::render_sort_bar($filter_id); ?>
	<?php \YMCFilterGrids\frontend\FG_Components::render_search_bar($filter_id); ?>
	<?php $filter_hidden_class = ('yes' === $filter_hidden) ? ' filter-hidden' : ''; ?>
    <div class="filter-layout filter-layout--right<?php echo esc_attr($filter_hidden_class); ?>">
        <div class="filter-content">
	        <?php
                do_action("ymc/grid/before_post_layout");
                do_action("ymc/grid/before_post_layout_". $filter_id);
                do_action("ymc/grid/before_post_layout_". $filter_id.'_'. $counter);
	        ?>
            <div class="posts-grid js-ajax-content <?php echo esc_attr($grid_classes); ?> ymc-col-gap-<?php echo esc_attr($grid_gap['column_gap']); ?> ymc-row-gap-<?php echo esc_attr($grid_gap['row_gap']); ?>"></div>
	        <?php
                do_action("ymc/grid/after_post_layout");
                do_action("ymc/grid/after_post_layout_". $filter_id);
                do_action("ymc/grid/after_post_layout_". $filter_id.'_'. $counter);
	        ?>
        </div>
	    <?php if('yes' !== $filter_hidden) : ?>
        <div class="filter-section filter-section--right">
	        <?php
                do_action("ymc/filter/layout/right/before");
                do_action("ymc/filter/layout/right/before_". $filter_id);
                do_action("ymc/filter/layout/right/before_". $filter_id.'_'. $counter);
	        ?>
	        <?php \YMCFilterGrids\frontend\FG_Components::get_filter($filter_id,'right', $filter_options); ?>
	        <?php
                do_action("ymc/filter/layout/right/after");
                do_action("ymc/filter/layout/right/after_". $filter_id);
                do_action("ymc/filter/layout/right/after_". $filter_id.'_'. $counter);
	        ?>
        </div>
        <?php endif; ?>
    </div>
</div>


