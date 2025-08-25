<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Metadata Class
 * Save meta data
 *
 * @since 3.0.0
 */
class FG_Save_Meta_Boxes {

	/**
	 * Hook in methods.
	 */
	public static function init() : void {
		add_action( 'save_post_ymc_filters', array(__CLASS__, 'save_meta_boxes'), 10, 2);
	}


	/**
	 * @param int $post_id
	 * @param object $post
	 *
	 * @return void
	 */
	public static function save_meta_boxes(int $post_id, object $post) : void {

		if (! isset($_POST['ymc_admin_data_nonce']) ||
		     ! check_admin_referer('ymc_admin_data_save', 'ymc_admin_data_nonce')) return;

		if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) return;

		if (! current_user_can('edit_page', $post_id)) {
			wp_die( esc_html__('You do not have permission to edit post.', 'ymc-smart-filters'));
		}



		// CPT
		if(isset($_POST['ymc_fg_post_types'])) {
			$post_types = array_map( 'sanitize_text_field', wp_unslash($_POST['ymc_fg_post_types']));
			update_post_meta( $post_id, 'ymc_fg_post_types', $post_types );
		}

		// Taxonomies
		$taxonomies = isset($_POST['ymc_fg_taxonomies'])
			? array_map( 'sanitize_text_field', wp_unslash($_POST['ymc_fg_taxonomies'])) : [];
		update_post_meta($post_id, 'ymc_fg_taxonomies', $taxonomies);

		// Terms
		$terms = isset($_POST['ymc_fg_terms'])
			? array_map( 'sanitize_text_field', wp_unslash($_POST['ymc_fg_terms'])) : [];
		update_post_meta($post_id, 'ymc_fg_terms', $terms);

		// Hierarchical Tree of Terms
		// $hierarchy_terms = isset($_POST['ymc_fg_hierarchy_terms'])
		//	? sanitize_text_field(wp_unslash($_POST['ymc_fg_hierarchy_terms'])) : 'no';
		// update_post_meta($post_id, 'ymc_fg_hierarchy_terms', $hierarchy_terms);

		// Selected posts
		$selected_posts = isset($_POST['ymc_fg_selected_posts'])
			? array_map('sanitize_text_field', wp_unslash($_POST['ymc_fg_selected_posts'])) : [];
		update_post_meta($post_id, 'ymc_fg_selected_posts', $selected_posts);

		// Excluded posts
		$excluded_posts = isset($_POST['ymc_fg_excluded_posts'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_excluded_posts'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_excluded_posts', $excluded_posts);

		// Relation of Taxonomies
		$tax_relation = isset($_POST['ymc_fg_tax_relation'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_tax_relation'])) : 'AND';
		update_post_meta($post_id, 'ymc_fg_tax_relation', $tax_relation);

		// Filter State (yes/no)
		$filter_state = isset($_POST['ymc_fg_filter_hidden'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_filter_hidden'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_filter_hidden', $filter_state);

		// Filter Layout
		$filter_layout = isset($_POST['ymc_fg_filter_type'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_filter_type'])) : 'default';
		update_post_meta($post_id, 'ymc_fg_filter_type', $filter_layout);

		// Filter Options
		if(isset($_POST['ymc_fg_filter_type'])) {
			$filter_options = $_POST['ymc_fg_filter_options'] ?? [];
			$data_filter_options = ymc_build_filter_options_from_post($post_id, $_POST['ymc_fg_filter_type'], $filter_options);
			update_post_meta($post_id, 'ymc_fg_filter_options', $data_filter_options);
		}

		// Post Layout
		$post_layout = isset($_POST['ymc_fg_post_layout'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_layout'])) : 'layout_standard';
		update_post_meta($post_id, 'ymc_fg_post_layout', $post_layout);

		// Grid Settings: Column Layout
		$column_layout = isset($_POST['ymc_fg_post_columns_layout'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_post_columns_layout'])) : [];
		update_post_meta($post_id, 'ymc_fg_post_columns_layout', $column_layout);

		// Grid Settings: Column & Row Gap
		$post_grid_gap = isset($_POST['ymc_fg_post_grid_gap'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_post_grid_gap'])) : [];
		update_post_meta($post_id, 'ymc_fg_post_grid_gap', $post_grid_gap);

		// Grid Style & Enqueue script Masonry
		if(isset($_POST['ymc_fg_grid_style'])) {
			$grid_style = sanitize_text_field(wp_unslash($_POST['ymc_fg_grid_style']));
			update_post_meta($post_id, 'ymc_fg_grid_style', $grid_style);
			$enable_js_masonry = $grid_style === 'masonry' ? 'yes' : 'no';
			update_option( 'ymc_fg_enable_js_masonry', $enable_js_masonry, false);
		}

		// Multiple Taxonomy
		$use_selection_mode = isset($_POST['ymc_fg_selection_mode'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_selection_mode'])) : 'single';
		update_post_meta($post_id, 'ymc_fg_selection_mode', $use_selection_mode);

		// "All" Button Label for terms
		$filter_all_label = isset($_POST['ymc_fg_filter_all_button'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_filter_all_button'])) : [];
		update_post_meta($post_id, 'ymc_fg_filter_all_button', $filter_all_label);

		// Display Terms
		$display_terms_mode = isset($_POST['ymc_fg_display_terms_mode'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_display_terms_mode'])) : 'selected_terms';
		update_post_meta($post_id, 'ymc_fg_display_terms_mode', $display_terms_mode);

		// Sort Direction
		$term_sort_direction = isset($_POST['ymc_fg_term_sort_direction'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_term_sort_direction'])) : 'ASC';
		update_post_meta($post_id, 'ymc_fg_term_sort_direction', $term_sort_direction);

		// Field to Sort by Terms
		$term_sort_field = isset($_POST['ymc_fg_term_sort_field'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_term_sort_field'])) : 'name';
		update_post_meta($post_id, 'ymc_fg_term_sort_field', $term_sort_field);

		// Pagination Hidden (yes/no)
		$pagination_hidden = isset($_POST['ymc_fg_pagination_hidden'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_pagination_hidden'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_pagination_hidden', $pagination_hidden);

		// Pagination Type
		$pagination_type = isset($_POST['ymc_fg_pagination_type'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_pagination_type'])) : 'numeric';
		update_post_meta($post_id, 'ymc_fg_pagination_type', $pagination_type);

		// Posts Per Page
		$posts_per_page = isset($_POST['ymc_fg_per_page'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_per_page'])) : '5';
		update_post_meta($post_id, 'ymc_fg_per_page', $posts_per_page);

		// Prev Button Text
		$prev_button_text = isset($_POST['ymc_fg_prev_button_text'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_prev_button_text'])) : '';
		update_post_meta($post_id, 'ymc_fg_prev_button_text', $prev_button_text);

		// Next Button Text
		$next_button_text = isset($_POST['ymc_fg_next_button_text'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_next_button_text'])) : '';
		update_post_meta($post_id, 'ymc_fg_next_button_text', $next_button_text);

		// Load More Button Text
		$load_more_text = isset($_POST['ymc_fg_load_more_text'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_load_more_text'])) : '';
		update_post_meta($post_id, 'ymc_fg_load_more_text', $load_more_text);

		// Post Image Size
		$post_image_size = isset($_POST['ymc_fg_post_image_size'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_image_size'])) : 'medium';
		update_post_meta($post_id, 'ymc_fg_post_image_size', $post_image_size);

		// Post Image Clickable
		$image_clickable = isset($_POST['ymc_fg_image_clickable'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_image_clickable'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_image_clickable', $image_clickable);

		// Truncate Post Excerpt
		$truncate_post_excerpt = isset($_POST['ymc_fg_truncate_post_excerpt'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_truncate_post_excerpt'])) : 'excerpt_truncated_text';
		update_post_meta($post_id, 'ymc_fg_truncate_post_excerpt', $truncate_post_excerpt);

		// Post Button Text
		$post_button_text = isset($_POST['ymc_fg_post_button_text'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_button_text'])) : '';
		update_post_meta($post_id, 'ymc_fg_post_button_text', $post_button_text);

		// Target Option
		$target_option = isset($_POST['ymc_fg_target_option'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_target_option'])) : '_self';
		update_post_meta($post_id, 'ymc_fg_target_option', $target_option);

		// Post excerpt length
		$post_excerpt_length = isset($_POST['ymc_fg_post_excerpt_length'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_excerpt_length'])) : 30;
		update_post_meta($post_id, 'ymc_fg_post_excerpt_length', $post_excerpt_length);

		// Post Order
		$post_order = isset($_POST['ymc_fg_post_order'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_order'])) : 'ASC';
		update_post_meta($post_id, 'ymc_fg_post_order', $post_order);

		// Post Order By
		$post_order_by = isset($_POST['ymc_fg_post_order_by'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_order_by'])) : 'title';
		update_post_meta($post_id, 'ymc_fg_post_order_by', $post_order_by);

		// Order meta_key
		$post_order_meta_key = isset($_POST['ymc_fg_order_meta_key'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_order_meta_key'])) : '';
		update_post_meta($post_id, 'ymc_fg_order_meta_key', $post_order_meta_key);

		// Order meta_value
		$post_order_meta_value = isset($_POST['ymc_fg_order_meta_value'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_order_meta_value'])) : '';
		update_post_meta($post_id, 'ymc_fg_order_meta_value', $post_order_meta_value);

		// Order by multiple
		$post_order_by_multiple = isset($_POST['ymc_fg_post_order_by_multiple'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_post_order_by_multiple'])) : [];
		update_post_meta($post_id, 'ymc_fg_post_order_by_multiple', $post_order_by_multiple);

		// Post status
		$post_status = isset($_POST['ymc_fg_post_status'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_status'])) : 30;
		update_post_meta($post_id, 'ymc_fg_post_status', $post_status);

		// No results message
		$no_results_message = isset($_POST['ymc_fg_no_results_message'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_no_results_message'])) : '';
		update_post_meta($post_id, 'ymc_fg_no_results_message', $no_results_message);

		// Post Animation Effect
		$post_animation_effect = isset($_POST['ymc_fg_post_animation_effect'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_post_animation_effect'])) : '';
		update_post_meta($post_id, 'ymc_fg_post_animation_effect', $post_animation_effect);

		// Post display settings
		$post_display_settings = isset($_POST['ymc_fg_post_display_settings'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_post_display_settings'])) : [];
		update_post_meta($post_id, 'ymc_fg_post_display_settings', $post_display_settings);

		// Popup State (yes/no)
		$popup_state = isset($_POST['ymc_fg_popup_enable'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_popup_enable'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_popup_enable', $popup_state);

		// Popup Settings
		$popup_settings = isset($_POST['ymc_fg_popup_settings'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_popup_settings'])) : [];
		update_post_meta($post_id, 'ymc_fg_popup_settings', $popup_settings);

		// Search State (yes/no)
		$search_state = isset($_POST['ymc_fg_search_enable'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_search_enable'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_search_enable', $search_state);

		// Search Mode
		$search_mode = isset($_POST['ymc_fg_search_mode'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_search_mode'])) : 'global';
		update_post_meta($post_id, 'ymc_fg_search_mode', $search_mode);

		// Search Button Text
		$search_button_text = isset($_POST['ymc_fg_submit_button_text'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_submit_button_text'])) : '';
		update_post_meta($post_id, 'ymc_fg_submit_button_text', $search_button_text);

		// Search Placeholder
		$search_placeholder = isset($_POST['ymc_fg_search_placeholder'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_search_placeholder'])) : '';
		update_post_meta($post_id, 'ymc_fg_search_placeholder', $search_placeholder);

		// Enable Search Autocomplete
		$autocomplete_enabled = isset($_POST['ymc_fg_autocomplete_enabled'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_autocomplete_enabled'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_autocomplete_enabled', $autocomplete_enabled);

		// Results Found Text
		$results_found_text = isset($_POST['ymc_fg_results_found_text'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_results_found_text'])) : '';
		update_post_meta($post_id, 'ymc_fg_results_found_text', $results_found_text);

		// Enable Exact Phrase Search
		$exact_phrase = isset($_POST['ymc_fg_exact_phrase'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_exact_phrase'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_exact_phrase', $exact_phrase);

		// Enable Search Meta Fields
		$search_meta_fields = isset($_POST['ymc_fg_search_meta_fields'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_search_meta_fields'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_search_meta_fields', $search_meta_fields);

		// Max Autocomplete Suggestions
		$max_autocomplete_suggestions = isset($_POST['ymc_fg_max_autocomplete_suggestions'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_max_autocomplete_suggestions'])) : 10;
		update_post_meta($post_id, 'ymc_fg_max_autocomplete_suggestions', $max_autocomplete_suggestions);

		// Typography Filters Settings
		$filter_typography = isset($_POST['ymc_fg_filter_typography'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_filter_typography'])) : [];
		update_post_meta($post_id, 'ymc_fg_filter_typography', $filter_typography);

		// Typography Posts Settings
		$post_typography = isset($_POST['ymc_fg_post_typography'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_post_typography'])) : [];
		update_post_meta($post_id, 'ymc_fg_post_typography', $post_typography);

		// Advanced Settings
		$enable_advanced_query = isset($_POST['ymc_fg_enable_advanced_query'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_enable_advanced_query'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_enable_advanced_query', $enable_advanced_query);

		// Advanced Query Type
		$advanced_query_type = isset($_POST['ymc_fg_advanced_query_type'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_advanced_query_type'])) : 'advanced';
		update_post_meta($post_id, 'ymc_fg_advanced_query_type', $advanced_query_type);

		// Advanced Query: Allowed Callback
		$query_allowed_callback = isset($_POST['ymc_fg_query_allowed_callback'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_query_allowed_callback'])) : '';
		update_post_meta($post_id, 'ymc_fg_query_allowed_callback', $query_allowed_callback);

		// Advanced Query
		$advanced_query = isset($_POST['ymc_fg_advanced_query'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_advanced_query'])) : '';
		update_post_meta($post_id, 'ymc_fg_advanced_query', $advanced_query);

		// Advanced Query: Suppress Filters
		$suppress_filters = isset($_POST['ymc_fg_advanced_suppress_filters'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_advanced_suppress_filters'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_advanced_suppress_filters', $suppress_filters);

		// Advanced: Enable sort posts
		$enable_sort_posts = isset($_POST['ymc_fg_enable_sort_posts'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_enable_sort_posts'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_enable_sort_posts', $enable_sort_posts);

		// Advanced: Post Sortable Fields
		$post_sortable_fields = isset($_POST['ymc_fg_post_sortable_fields'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_post_sortable_fields'])) : [];
		update_post_meta($post_id, 'ymc_fg_post_sortable_fields', $post_sortable_fields);

		// Advanced: Sort Dropdown Label
		$sort_dropdown_label = isset($_POST['ymc_fg_sort_dropdown_label'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_sort_dropdown_label'])) : '';
		update_post_meta($post_id, 'ymc_fg_sort_dropdown_label', $sort_dropdown_label);

		// Advanced: Custom Container Class
		$custom_container_class = isset($_POST['ymc_fg_custom_container_class'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_custom_container_class'])) : '';
		update_post_meta($post_id, 'ymc_fg_custom_container_class', $custom_container_class);

		// Advanced: Extra Filter Layout
		$extra_filter_type = isset($_POST['ymc_fg_extra_filter_type'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_extra_filter_type'])) : '';
		update_post_meta($post_id, 'ymc_fg_extra_filter_type', $extra_filter_type);

		// Advanced: Extra Taxonomy
		$extra_taxonomy = isset($_POST['ymc_fg_extra_taxonomy'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_extra_taxonomy'])) : '';
		update_post_meta($post_id, 'ymc_fg_extra_taxonomy', $extra_taxonomy);

		// Advanced: Custom CSS
		$custom_css = isset($_POST['ymc_fg_custom_css'])
			? wp_kses_post(wp_unslash($_POST['ymc_fg_custom_css'])) : '';
		update_post_meta($post_id, 'ymc_fg_custom_css', $custom_css);

		// Advanced: Custom Action
		$custom_js = isset($_POST['ymc_fg_custom_js'])
			? wp_kses_post(wp_unslash($_POST['ymc_fg_custom_js'])) : '';
		update_post_meta($post_id, 'ymc_fg_custom_js', $custom_js);

		// Advanced: Preloader Icon
		$preloader_icon = isset($_POST['ymc_fg_preloader_settings'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_preloader_settings'])) : [];
		update_post_meta($post_id, 'ymc_fg_preloader_settings', $preloader_icon);

		// Advanced: Scroll to Filters Bar
		$scroll_to_filters_on_load = isset($_POST['ymc_fg_scroll_to_filters_on_load'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_scroll_to_filters_on_load'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_scroll_to_filters_on_load', $scroll_to_filters_on_load);

		// Advanced: Debug Mode
		$debug_mode = isset($_POST['ymc_fg_debug_mode'])
			? sanitize_text_field(wp_unslash($_POST['ymc_fg_debug_mode'])) : 'no';
		update_post_meta($post_id, 'ymc_fg_debug_mode', $debug_mode);

		// Swiper carousel
		$carousel_settings = isset($_POST['ymc_fg_carousel_settings'])
			? ymc_sanitize_array_recursive(wp_unslash($_POST['ymc_fg_carousel_settings'])) : [];
		update_post_meta($post_id, 'ymc_fg_carousel_settings', $carousel_settings);





	}

}