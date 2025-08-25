<?php declare( strict_types = 1 );

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\frontend\FG_Pagination as Pagination;
use YMCFilterGrids\FG_Template as Template;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Ajax_Responder Class
 * Handle ajax requests
 *
 * @since 3.0.0
 */
class FG_Ajax_Responder {
	public static function init() : void {
		add_action('wp_ajax_get_filtered_posts',  array( __CLASS__, 'get_filtered_posts'));
		add_action('wp_ajax_nopriv_get_filtered_posts', array( __CLASS__, 'get_filtered_posts'));

		add_action('wp_ajax_get_post_to_popup',  array( __CLASS__, 'get_post_to_popup'));
		add_action('wp_ajax_nopriv_get_post_to_popup', array( __CLASS__, 'get_post_to_popup'));

		add_action('wp_ajax_get_autocomplete_suggestions',  array( __CLASS__, 'get_autocomplete_suggestions'));
		add_action('wp_ajax_nopriv_get_autocomplete_suggestions', array( __CLASS__, 'get_autocomplete_suggestions'));

	}

	/**
     * Get filtered posts
     *
     * @since 3.0.0
	 * @return void
	 */
	public static function get_filtered_posts() : void {
		check_ajax_referer('get_filtered_posts-ajax-nonce', 'nonce_code');

		$params = isset($_POST['params']) ? json_decode(stripslashes($_POST['params']), true) : [];

		if (empty($params) || !is_array($params) || empty($params['filter_id'])) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		// Данные которые отправляются в запросе ($params), но не будут изменяться
        $filter_id           = $params['filter_id'];
		$counter             = $params['counter'] ?? 1;
		$page_id             = $params['page_id'] ?? '';
		$paged               = $params['paged'] ?? 1;
		$data_response       = [];
		$pagination_rendered = '';
		$results_found_text  = '';

		// Post types
		$post_types          = $params['post_types'] ?? ['post'];

		// Taxonomies and terms
		$taxonomies          = $params['taxonomies'] ?? [];
		$terms               = $params['terms'] ?? [];

		// Per page
		$per_page            = $params['per_page'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_per_page');

		// Taxonomies relation
		$tax_relation        = $params['tax_relation'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_tax_relation');

		// Selected posts
		$selected_posts      = $params['selected_posts'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_selected_posts');
		$excluded_posts     =  $params['excluded_posts'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_excluded_posts');

		// Order posts
		$post_order          = $params['post_order'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_post_order');
		$post_order_by       = $params['post_order_by'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_post_order_by');
		$order_meta_key      = $params['order_meta_key'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_order_meta_key');
		$order_meta_value    = $params['order_meta_value'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_order_meta_value');
		$post_order_multiple = $params['post_order_by_multiple'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_post_order_by_multiple');

		// Post status
		$post_status         = $params['post_status'] ?? Data_Store::get_meta_value($filter_id, 'ymc_fg_post_status');

		// Meta query
		$meta_query_raw      = $params['meta_query'] ?? [];
		$meta_query_relation = strtoupper($params['meta_query_relation'] ?? 'AND');

		// Date query
		$date_query          = $params['date_query'] ?? null;

		// Search query
		$keyword_search      = $params['search'] ?? null;

		// Sort posts by ajax
		$ajax_orderby        = $params['orderby'] ?? null;
		$ajax_order          = $params['order'] ?? null;

		// Date filter
		$filter_date         = $params['date_filter'] ?? null;


		// Get data from DB
		$post_layout        = Data_Store::get_meta_value($filter_id, 'ymc_fg_post_layout');
		$no_results_message = Data_Store::get_meta_value($filter_id, 'ymc_fg_no_results_message');
		$pagination_hidden  = Data_Store::get_meta_value($filter_id, 'ymc_fg_pagination_hidden');
		$pagination_type    = Data_Store::get_meta_value($filter_id, 'ymc_fg_pagination_type');
		$search_mode        = Data_Store::get_meta_value($filter_id, 'ymc_fg_search_mode');
		$exact_phrase       = Data_Store::get_meta_value($filter_id, 'ymc_fg_exact_phrase');
		$search_meta_fields = Data_Store::get_meta_value($filter_id, 'ymc_fg_search_meta_fields');
		$advanced_query     = Data_Store::get_meta_value($filter_id, 'ymc_fg_enable_advanced_query');
		$advanced_query_type = Data_Store::get_meta_value( $filter_id, 'ymc_fg_advanced_query_type' );
		$suppress_filters   = Data_Store::get_meta_value($filter_id, 'ymc_fg_advanced_suppress_filters');
		$scroll_to_filters_on_load  = Data_Store::get_meta_value($filter_id, 'ymc_fg_scroll_to_filters_on_load');
		$debug_mode         = Data_Store::get_meta_value($filter_id, 'ymc_fg_debug_mode');

		// Arguments
		$args = [
			'post_type'      => $post_types,
			'posts_per_page' => $per_page,
			'paged'          => $paged,
			'post_status'    => $post_status,
			'order'          => $post_order,
			'orderby'        => $post_order_by
		];


		// Taxonomies and terms
		self::add_tax_query_args($args, $taxonomies, $terms, $tax_relation, $filter_id);

		// Selected Posts
		if ($selected_posts) {
			$key = ($excluded_posts === 'no') ? 'post__in' : 'post__not_in';
			$args[$key] = array_map('intval', (array) $selected_posts);
		}

		// Post Order
		if ($post_order_by) {
			switch ($post_order_by) {
				case 'meta_key':
					$args['meta_key'] = $order_meta_key;
					$args['orderby']  = $order_meta_value;
					break;

				case 'multiple_fields':
					if($post_order_multiple['fields']) {
						$result = array_combine(
							array_column($post_order_multiple['fields'], 'field_name'),
							array_column($post_order_multiple['fields'], 'order_type')
						);
						$args['orderby']  = $result;
						unset($args['order']);
					}
					break;

				default:
					$args['orderby'] = $post_order_by;
					break;
			}
		}

		// Meta query
		if (!empty($meta_query_raw) && is_array($meta_query_raw)) {
			$meta_query = [
				'relation' => in_array($meta_query_relation, ['AND', 'OR']) ? $meta_query_relation : 'AND'
			];

			foreach ($meta_query_raw as $meta_condition) {
				if (!isset($meta_condition['key'], $meta_condition['value'])) {
					continue;
				}

				$meta_query[] = [
					'key'     => sanitize_text_field($meta_condition['key']),
					'value'   => sanitize_text_field($meta_condition['value']),
					'compare' => isset($meta_condition['compare']) ? sanitize_text_field($meta_condition['compare']) : '=',
					'type'    => isset($meta_condition['type']) ? sanitize_text_field($meta_condition['type']) : 'CHAR'
				];
			}

			if (count($meta_query) > 1) {
				$args['meta_query'] = $meta_query;
			}
		}

		// Date query
		if (!empty($date_query) && is_array($date_query)) {
			$args['date_query'] = $date_query;
		}

		// Date filter
		if ( !empty( $filter_date ) && is_array( $filter_date ) ) {
			$type = $filter_date['type'] ?? '';
			$date_query = [];

			switch ( $type ) {
				case 'today':
				case 'yesterday':
					$offset = ( $type === 'yesterday' ) ? -1 : 0;
					$target = getdate( strtotime( "$offset day" ) );
					$date_query[] = [
						'year'     => $target['year'],
						'monthnum' => $target['mon'],
						'day'      => $target['mday'],
					];
					break;

				case '3_days':
					$date_query[] = [
						'after'     => '3 days ago',
						'inclusive' => true,
					];
					break;

				case 'last_week':
					$date_query[] = [
						'after'     => '7 days ago',
						'inclusive' => true,
					];
					break;

				case 'last_month':
					$date_query[] = [
						'after'     => '30 days ago',
						'inclusive' => true,
					];
					break;

				case 'last_year':
					$date_query[] = [
						'after'     => '1 year ago',
						'inclusive' => true,
					];
					break;

				case 'other_time':
					if ( isset( $filter_date['from'], $filter_date['to'] ) ) {
						$from_ts = (int) $filter_date['from'];
						$to_ts   = (int) $filter_date['to'];

						if ( $from_ts && $to_ts ) {
							$date_query[] = [
								'after'     => [
									'year'  => gmdate( 'Y', $from_ts ),
									'month' => gmdate( 'm', $from_ts ),
									'day'   => gmdate( 'd', $from_ts ),
								],
								'before'    => [
									'year'  => gmdate( 'Y', $to_ts ),
									'month' => gmdate( 'm', $to_ts ),
									'day'   => gmdate( 'd', $to_ts ),
								],
								'inclusive' => true,
							];
						}
					}
					break;

				default: break;
			}

			if ( !empty( $date_query ) ) {
				$args['date_query'] = $date_query;
			}
		}

		// Search query
		if (!empty($keyword_search)) {
			if($search_meta_fields === 'yes') {
				self::add_search_filters();
			}

			if($search_mode === 'global') {
				unset($args['tax_query']);
				unset($args['meta_query']);
				unset($args['date_query']);
			}
			if($exact_phrase === 'yes') {
				$args['exact'] = true;
			}
			$args['s'] = $keyword_search;
			$args['sentence'] = true;

			$results_text = Data_Store::get_meta_value($filter_id, 'ymc_fg_results_found_text');
			$results_text = apply_filters('ymc/search/results_found_text',  $results_text);
			$results_text = apply_filters('ymc/search/results_found_text_'. $filter_id, $results_text);
			$results_text = apply_filters('ymc/search/results_found_text_'. $filter_id .'_'. $counter, $results_text);
			$data_response['results_text'] = $results_text;
		}

		// Advanced query
		if ($advanced_query === 'yes') {
			if($suppress_filters === 'yes') {
				$args['suppress_filters'] = true;
			}

			if ( $advanced_query_type === 'callback' ) {
				$allowed_callback = Data_Store::get_meta_value( $filter_id, 'ymc_fg_query_allowed_callback' );
				$whitelist = apply_filters( 'ymc/filter/query/wp/allowed_callbacks', [] );
				$whitelist = apply_filters( 'ymc/filter/query/wp/allowed_callbacks_'.$filter_id, $whitelist );

				if (is_callable( $allowed_callback) && in_array($allowed_callback, $whitelist, true)) {
					$query_args = call_user_func( $allowed_callback, [
						'post_type' => $post_types,
						'taxonomy'  => $taxonomies,
						'terms'     => $terms,
						'page_id'   => $page_id
					]);

					if ( is_array( $query_args ) ) {
						$args = array_merge( $args, $query_args );
					}
				}
			}

			if ($advanced_query_type === 'advanced') {
				$user_input = Data_Store::get_meta_value( $filter_id, 'ymc_fg_advanced_query' );

				if ( is_string($user_input) && trim($user_input) !== '' ) {
					parse_str( $user_input, $parsed_args );

					if ( is_array($parsed_args) && ! empty($parsed_args) ) {
						//$allowed_keys = ['post_type', 'posts_per_page', 'post_status', 'orderby', 'order'];
						//$filtered_args = array_intersect_key( $parsed_args, array_flip($allowed_keys) );
						$args = array_merge( $args, $parsed_args );
					}
				}
			}
		}

		// Sort posts by ajax
		if (!empty($ajax_orderby)) {
			$args['orderby'] = sanitize_key($ajax_orderby);
		}
		if (!empty($ajax_order)) {
			$args['order'] = strtoupper($ajax_order) === 'ASC' ? 'ASC' : 'DESC';
		}



		$query = new \WP_Query($args);

		ob_start();

		if ($query->have_posts()) {
			$template_file = 'post-layout-' . str_replace( 'layout_', '', $post_layout );
			$template_path = __DIR__ . '/views/templates/posts/' . $template_file . '.php';

			// Requires swiper
			if ($template_file === 'post-layout-carousel') {
				$data_response['requires_swiper'] = true;
			}

			if (file_exists($template_path)) {
				Template::render($template_path, [
					'query'       => $query,
					'post_layout' => $post_layout,
					'filter_id'   => $filter_id,
					'counter'     => $counter,
					'per_page'    => $per_page,
					'paged'       => $paged
				]);
			} else {
				echo '<div class="text">'. esc_html("Template not found: ") . esc_url($template_path) .'</div>';
			}

			if ($pagination_hidden === 'no' && $template_file !== 'post-layout-carousel') {
				switch ($pagination_type) {
					case 'numeric' :
						$pagination_rendered = Pagination::create_numeric_pagination($query, $paged, $filter_id, $counter);
						break;
					case 'loadmore' :
						$pagination_rendered = Pagination::create_load_more_pagination($query, $filter_id, $counter);
						break;
				}
			}

		} else {
			echo '<div class="text">'. esc_html( $no_results_message ) .'</div>';
		}

		// Clear rendered posts and pagination
		$rendered_posts = ob_get_clean();
		$rendered_posts = preg_replace('/\s+/', ' ', $rendered_posts);
		$rendered_posts = preg_replace('/>\s+</', '><', $rendered_posts);
		$rendered_posts = trim($rendered_posts);
		$pagination_rendered = preg_replace('/[\r\n\t]+/', '', $pagination_rendered);

		wp_reset_postdata();

		$data_response['rendered_posts']      = $rendered_posts;
		$data_response['rendered_pagination'] = $pagination_rendered;
		$data_response['found_posts']         = $query->found_posts;
		$data_response['max_num_pages']       = $query->max_num_pages;
		$data_response['posts_count']         = $query->post_count;
		$data_response['paged']               = $paged;
		$data_response['pagination_type']     = $pagination_type;
		$data_response['scroll_filter_bar']   = $scroll_to_filters_on_load;

		// Debug Mode
		if($debug_mode === 'yes') {
			$data_response['debug_mode'] = [
				'args' => $args,
				'found_posts'       => $query->found_posts,
				'max_num_pages'     => $query->max_num_pages,
				'posts_count'       => $query->post_count,
				'sql_request'       => $query->request,
				'is_main_query'     => $query->is_main_query(),
				'query_vars'        => $query->query_vars,
				'queried_object'    => $query->get_queried_object(),
				'queried_object_id' => $query->get_queried_object_id()
			];
		}

		wp_send_json_success($data_response);

	}


	/**
	 * Get post to popup
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function get_post_to_popup() : void {
		check_ajax_referer('get_post_to_popup-ajax-nonce', 'nonce_code');

		$post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : '';
		$grid_id = isset($_POST['grid_id']) ? (int) $_POST['grid_id'] : '';
		$counter = isset($_POST['counter']) ? (int) $_POST['counter'] : '';

		if (empty($post_id) || empty($grid_id) || empty($counter)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		ob_start();

		$post = get_post($post_id);

		if(!empty($post)) {
			if( has_post_thumbnail($post_id) ) :
				echo '<figure class="post-image">' .get_the_post_thumbnail( $post_id, 'full' ). '</figure>';
			endif;
			echo '<h2 class="post-title">' . esc_html(get_the_title($post_id)) . '</h2>';
			// phpcs:ignore WordPress
			echo apply_filters('the_content', $post->post_content);
		}

		$content = ob_get_clean();

		$content = apply_filters('ymc/popup/custom_layout', $content, $post_id);
		$content = apply_filters('ymc/popup/custom_layout_'.$grid_id, $content, $post_id);
		$content = apply_filters('ymc/popup/custom_layout_'.$grid_id.'_'.$counter, $content, $post_id);

		wp_send_json_success([
			'body' => $content
		]);

	}


	/**
	 * Get autocomplete suggestions
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function get_autocomplete_suggestions() : void {
		global $self;
		check_ajax_referer('get_autocomplete_posts-ajax-nonce', 'nonce_code');

		$keyword = isset($_POST['keyword']) ? sanitize_text_field(stripslashes($_POST['keyword'])) : '';
		$grid_id = isset($_POST['grid_id']) ? (int) sanitize_text_field(stripslashes($_POST['grid_id'])) : '';
		$terms   = isset($_POST['terms']) ? json_decode(stripslashes($_POST['terms']), true) : [];
		$suggestions = [];

		if(empty($keyword) || empty($grid_id)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$tax_relation = Data_Store::get_meta_value($grid_id, 'ymc_fg_tax_relation');
		$taxonomies   = Data_Store::get_meta_value($grid_id, 'ymc_fg_taxonomies');
		$search_mode  = Data_Store::get_meta_value($grid_id, 'ymc_fg_search_mode');
		$exact_phrase = Data_Store::get_meta_value($grid_id, 'ymc_fg_exact_phrase');
		$search_meta_fields = Data_Store::get_meta_value($grid_id, 'ymc_fg_search_meta_fields');
		$max_autocomplete_suggestions = Data_Store::get_meta_value($grid_id, 'ymc_fg_max_autocomplete_suggestions');

		if($search_meta_fields === 'yes') {
			self::add_search_filters();
		}

		$args = [
			'post_status'    => 'publish',
			'posts_per_page' => $max_autocomplete_suggestions,
			'orderby'        => 'title',
			'order'          => 'asc',
			'sentence'       => true,
			's'              => $keyword
		];

		if($search_mode === 'filtered') {
			self::add_tax_query_args($args, $taxonomies, $terms, $tax_relation, $grid_id);
		}
		if($exact_phrase === 'yes') {
			$args['exact'] = true;
		}

		$query = new \WP_Query($args);

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				$post_id = get_the_ID();
				$title   = get_the_title();
				$content = get_the_content();
				$found   = false;

				// Search in title
				$fragment = self::extract_and_highlight_fragment($title, $keyword);
				if ($fragment) {
					$suggestions[] = [
						'id'      => $post_id,
						'context' => $fragment,
						'title'   => $title,
						'source'  => 'title'
					];
					$found = true;
				}

				// Search in content if not found yet
				if (!$found) {
					$fragment = self::extract_and_highlight_fragment($content, $keyword);
					if ($fragment) {
						$suggestions[] = [
							'id'      => $post_id,
							'context' => $fragment,
							'title'   => $title,
							'source'  => 'content'
						];
						$found = true;
					}
				}

				// Search in meta if not found yet
				if (!$found && $search_meta_fields === 'yes') {
					$meta = get_post_meta($post_id);
					foreach ($meta as $key => $meta_values) {
						foreach ($meta_values as $meta_value) {
							$fragment = self::extract_and_highlight_fragment($meta_value, $keyword);
							if ($fragment) {
								$suggestions[] = [
									'id'      => $post_id,
									'context' => $fragment,
									'title'   => $title,
									'source'  => 'meta ('. esc_html($key) .')'
								];
								break 2;
							}
						}
					}
				}
			}
			wp_reset_postdata();
		}

		wp_send_json_success([
			'results' => $suggestions
		]);
	}


	/**
	 * Gets text fragments containing a keyword and highlights them.
	 *
	 * @param string $text — text to search
	 * @param string $keyword — keyword
	 * @param int $padding — number of characters before/after the word
	 *
	 * @since 3.0.0
	 * @return string|null — fragment with backlight
	 */
	public static function extract_and_highlight_fragment(string $text, string $keyword, int $padding = 30) : ?string {
		$text = strip_tags($text);
		$text = preg_replace('/\[[\/]?[^\]]+\]/', '', $text);
		$text_lower = mb_strtolower($text);
		$keyword_lower = mb_strtolower($keyword);
		$pos = mb_strpos($text_lower, $keyword_lower);

		if ($pos === false) return null;

		//$start = max(0, $pos - $padding);
		$start = $pos;
		$length = mb_strlen($keyword);
		$end = $pos + $length + $padding;

		$fragment = mb_substr($text, $start, $end - $start);
		$fragment = preg_replace(
			'/' . preg_quote($keyword, '/') . '/iu',
			'<b>$0</b>',
			$fragment
		);

		//if ($start > 0) $fragment = '...' . $fragment;
		if ($end < mb_strlen($text)) $fragment .= '...';

		return $fragment;
	}



	/**
	 * Get terms IDs by taxonomy and selected terms
	 * For display terms mode: Selected Terms and Selected Terms (Hide Empty)
	 *
	 * @param string $taxonomies
	 * @param int $filter_id
	 *
	 * @return array
	 */
	private static function get_terms_by_taxonomy(string $taxonomies, int $filter_id): array {
		$display_terms_mode = (string) Data_Store::get_meta_value($filter_id, 'ymc_fg_display_terms_mode');

		$hide_empty = $display_terms_mode === 'all_terms_hide_empty';
		$term_selected = in_array($display_terms_mode, ['all_terms', 'all_terms_hide_empty'], true)
			? []
			: array_map('intval', (array) Data_Store::get_meta_value($filter_id, 'ymc_fg_terms'));

		$terms = get_terms([
			'taxonomy'   => $taxonomies,
			'include'    => $term_selected,
			'hide_empty' => $hide_empty
		]);

		if (is_wp_error($terms) || empty($terms)) {
			return [];
		}

		return array_map(fn($term) => $term->term_id, $terms);
	}


	/**
	 * Add tax query args
	 * @param array $args
	 * @param array $taxonomies
	 * @param array $terms
	 * @param string $tax_relation
	 * @param int $filter_id
	 *
	 * @since 3.0.0
	 * @return void
	 */
	private static function add_tax_query_args(array &$args, array $taxonomies,	array $terms, string $tax_relation = 'AND', int $filter_id = 0): void {
		if (!empty($taxonomies) && !empty($terms)) {
			$tax_query = ['relation' => $tax_relation];
			$term_tax_map = [];

			foreach ($terms as $term_id) {
				$term = get_term((int) $term_id);
				if ($term && !is_wp_error($term)) {
					$term_tax_map[$term->taxonomy][] = (int) $term->term_id;
				}
			}
			foreach ($taxonomies as $taxonomy) {
				if (!empty($term_tax_map[$taxonomy])) {
					$tax_query[] = [
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $term_tax_map[$taxonomy],
					];
				}
				else {
					$tax_query[] = [
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => self::get_terms_by_taxonomy($taxonomy, $filter_id),
					];
				}
			}
			if (count($tax_query) > 1) {
				$args['tax_query'] = $tax_query;
			}
		}
	}


	/**
	 * Modify the SQL JOIN statement to include the postmeta table for custom meta data retrieval.
	 *
	 * @param string $join The existing SQL JOIN statement.
	 * @return string The modified SQL JOIN statement.
	 */
	public static function search_join( string $join ) : string {
		global $wpdb;
		$join .= " LEFT JOIN $wpdb->postmeta AS pm ON ID = pm.post_id ";
		return $join;
	}

	/**
	 * Modify the WHERE clause of the SQL query to include postmeta table for custom meta data search.
	 *
	 * @param string $where The existing WHERE clause of the SQL query.
	 * @return string The modified WHERE clause including the postmeta table search condition.
	 */
	public static function search_where( string $where ) : string {
		global $wpdb;
		$where = preg_replace(
			"/\(\s*$wpdb->posts.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"($wpdb->posts.post_title LIKE $1) OR (pm.meta_value LIKE $1)", $where );
		return $where;
	}


	/**
	 * Returns the DISTINCT keyword used in SQL queries.
	 *
	 * @param string $where
	 * @return string The DISTINCT keyword
	 */
	public static function search_distinct( string $where ) : string {
		return  'DISTINCT';	}


	/**
	 * Grouped search filters
	 * @return void
	 */
	public static function add_search_filters() : void {
		add_filter('posts_join', [__CLASS__, 'search_join']);
		add_filter('posts_where', [__CLASS__, 'search_where']);
		add_filter('posts_distinct', [__CLASS__, 'search_distinct']);
	}



}



