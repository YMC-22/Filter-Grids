<?php
use YMCFilterGrids\admin\FG_Taxonomy as Taxonomy;
use YMCFilterGrids\admin\FG_Term as Term;

if (!defined( 'ABSPATH')) exit;

?>
<div class="inner">
    <div class="header"><?php echo esc_html($section_name); ?></div>

    <div class="body">
        <div class="headline js-headline-accordion" data-hash="query_params">
            <span class="inner">
                <i class="fa-solid fa-sliders"></i>
                <span class="text"><?php echo esc_html__('Query Params', 'ymc-smart-filters'); ?></span>
            </span>
            <i class="fa-solid fa-chevron-down js-icon-accordion"></i>
        </div>
        <div class="form-wrap">

            <fieldset class="form-group cpt-wrapper">
                <div class="group-elements">
	                <?php ymc_render_field_header('Post Type(s)', 'Select one ore more posts. To select multiple posts,
	             hold down the key Ctrl. For a more complete display of posts in the grid, set the "Taxonomy Relation" 
	             option to OR.'); ?>
                    <select class="form-select form-select--multiple js-post-types" id="ymc-post-types"
                            data-previous-value="<?php echo esc_attr(implode(',',$ymc_fg_post_types)); ?>"
                            name="ymc_fg_post_types[]" multiple>
		                <?php
		                $post_types = ymc_get_post_types(['attachment', 'popup']);
		                foreach( $post_types as $cpt ) {
			                $cpt_sel = ( false !== array_search($cpt, $ymc_fg_post_types) ) ? 'selected' : '';
			                echo "<option value='" . esc_attr($cpt) ."' ". esc_attr($cpt_sel) .">" .
			                     esc_html( get_post_type_object( $cpt )->label ) . "</option>";
		                }
		                ?>
                    </select>
	                <?php wp_nonce_field( 'ymc_admin_data_save','ymc_admin_data_nonce' ); ?>
                </div>
            </fieldset>

            <fieldset class="form-group taxonomy-wrapper">
                <div class="group-elements">
	                <?php $is_hidden = empty(ymc_get_taxonomies($ymc_fg_post_types)) ? 'is-hidden' : ''; ?>
                    <div class="control-bar js-control-bar <?php echo esc_attr($is_hidden); ?>">
                        <button class="btn btn-reload js-tax-updated js-btn-tooltip" title="<?php esc_attr_e('Update taxonomies.','ymc-smart-filters'); ?>"></button>
                        <button class="btn btn-remove js-tax-clear js-btn-tooltip" title="<?php esc_attr_e('Remove terms of taxonomies.','ymc-smart-filters'); ?>"></button>
                    </div>
	                <?php ymc_render_field_header('Taxonomy(s)', 'Select taxonomy(s). Sortable with Drag & Drop feature. 
	                Taxonomy sorting does not apply to Combined filter type'); ?>
	                <?php
	                // phpcs:ignore WordPress
                    echo Taxonomy::output_html($post_id, $ymc_fg_post_types); ?>
                </div>
            </fieldset>

            <fieldset class="form-group terms-wrapper <?php echo (!$ymc_fg_taxonomies) ? 'is-hidden' : ''; ?>">
                <div class="group-elements">
	                <?php ymc_render_field_header('Term(s)',
                        'Select terms. Sortable with Drag and Drop feature.<hr> To manually sort terms, enable the 
                        "Manual (Custom Order)" option in the <b>Appearance -> Filter Settings -> Term Sort Direction</b> section.'); ?>
                </div>
                <div class="group-elements">
                    <div class="terms-wrapper ">
                        <div class="terms-grid js-term-insert">
			                <?php
			                // phpcs:ignore WordPress
                            echo Term::output_html($post_id, $ymc_fg_post_types); ?>
                        </div>
                    </div>
                </div>
                <!--<div class="group-elements">
                    <div class="hierarchical-tree-wrapper">
		                <?php /*ymc_render_field_header('Hierarchical Tree of Terms', 'Check to display the hierarchy tree of terms.'); */?>
                        <div class="group-elements">
                            <input class="form-checkbox" type="checkbox" value="yes" name="ymc_fg_hierarchy_terms"
                                   id="ymc_fg_hierarchy_terms" <?php /*checked( $ymc_fg_hierarchy_terms, 'yes' ); */?>>
                            <label class="field-label" for="ymc_fg_hierarchy_terms">
				                <?php /*esc_html_e('Enable hierarchical view', 'ymc-smart-filters'); */?></label>
                        </div>
                    </div>
                </div>-->
            </fieldset>

            <fieldset class="form-group posts-wrapper">
                <div class="group-elements">
	                <?php ymc_render_field_header('Add / Exclude Post(s)', 'Include / Exclude posts in the post grid on the frontend.'); ?>
                </div>
                <div class="group-elements">
                    <div class="search-posts">
                        <div class="search-posts__inner">
                            <div class="form-item">
                                <input class="form-input js-field-search" type="text" placeholder="<?php esc_html_e('Search...', 'ymc-smart-filters') ?>" />
                                <span class="button-field-clear is-hidden js-btn-clear">x</span>
                            </div>
                            <button class="button button--primary js-btn-search" aria-label="button search" type="button">
                                <i class="fa-solid fa-magnifying-glass"></i>
				                <?php esc_html_e('Search', 'ymc-smart-filters') ?></button>
                        </div>
                    </div>
                </div>
                <div class="group-elements">
                    <div class="select-posts-display">
                        <div class="button-expand-wrapper">
                            <button class="button button-expand js-button-expand" aria-label="button expand" type="button">
				                <?php esc_html_e('expand', 'ymc-smart-filters') ?></button>
                        </div>
                        <div class="select-posts-display__inner">
                            <div class="cel feed-posts">
				                <?php $list_posts = ymc_get_posts_ids($ymc_fg_post_types,20);
				                extract($list_posts); ?>
                                <span class="counter number-posts"><?php echo esc_html($found_posts); ?></span>
                                <ul class="list-posts js-scroll-posts">
					                <?php
					                if(!empty($posts_ids)) {
						                $is_disabled = '';
						                foreach ($posts_ids as $post_id) {
							                if( false !== array_search($post_id, $ymc_fg_selected_posts)) {
								                $is_disabled = ' is-disabled';
							                }
							                $post_title = get_the_title($post_id);
							                printf("<li class='list-posts__item add-post js-add-post".esc_attr($is_disabled)."' data-post-id='%u'>
                                            <span class='post-title'>%s</span> <span class='post-id'>(ID: %u)</span></li>", esc_attr($post_id), esc_attr($post_title), esc_attr($post_id));
							                $is_disabled = '';
						                }
					                } else {
						                printf("<p class='notification notification--warning'>%s</p>", esc_html__('No posts found', 'ymc-smart-filters'));
					                }
					                ?>
                                </ul>
                            </div>
                            <div class="cel selected-posts">
                                <span class="counter number-posts"><?php echo esc_html(count($ymc_fg_selected_posts)); ?></span>
				                <?php $class_excluded_is = ('yes' === $ymc_fg_excluded_posts) ? 'is-excluded' : ''; ?>
                                <ul class="list-posts js-post-sortable <?php echo esc_attr($class_excluded_is); ?>">
					                <?php
					                if(!empty($ymc_fg_selected_posts)) {
						                foreach ($ymc_fg_selected_posts as $post_id) {
							                $post_title = get_the_title($post_id);
							                printf("<li class='list-posts__item post-selected'>
                                        <div class='post-inner'>
                                            <div class='post-title'>%s</div>
                                            <span class='fa-solid fa-trash button-remove js-post-remove' data-post-id='%u'></span>
                                            <input type='hidden' name='ymc_fg_selected_posts[]' value='%u'>
                                        </div></li>", esc_attr($post_title), esc_attr($post_id), esc_attr($post_id));
						                }
					                }
					                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="group-elements">
                    <div class="excluded-posts-checkbox">
		                <?php ymc_render_field_header('Exclude Post(s)', 'Check to exclude the selected posts from the grid. Works on selected posts.'); ?>
                        <div class="group-elements">
                            <input class="form-checkbox js-excluded-checkbox" type="checkbox" value="yes" name="ymc_fg_excluded_posts"
                                   id="ymc_fg_excluded_posts" <?php checked( $ymc_fg_excluded_posts, 'yes' );  ?>>
                            <label class="field-label" for="ymc_fg_excluded_posts"><?php esc_html_e('Enable post exclusion', 'ymc-smart-filters'); ?></label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-group taxonomy-relation-wrapper">
                <div class="group-elements">
	                <?php ymc_render_field_header('Taxonomy Relation', 'Select relationship between taxonomies.'); ?>
                    <div class="taxonomy-relation">
                        <select class="form-select" name="ymc_fg_tax_relation" id="ymc_fg_tax_relation">
                            <option value="AND" <?php selected( $ymc_fg_tax_relation, 'AND' ); ?>>
				                <?php esc_html_e('AND', 'ymc-smart-filters'); ?></option>
                            <option value="OR" <?php selected( $ymc_fg_tax_relation, 'OR' ); ?>>
				                <?php esc_html_e('OR', 'ymc-smart-filters'); ?></option>
                        </select>
                    </div>
                </div>
            </fieldset>

        </div>
    </div>
</div>


