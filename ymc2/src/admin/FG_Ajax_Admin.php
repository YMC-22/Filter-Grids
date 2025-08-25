<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\admin\FG_Term as Term;
use YMCFilterGrids\admin\FG_UiLabels as UiLabels;

defined( 'ABSPATH' ) || exit;

/**
 * Class FG_Ajax_Admin
 * Handle ajax requests
 *
 * @since 3.0.0
 */

class FG_Ajax_Admin {

	public static function init() : void {
		add_action('wp_ajax_action_get_taxonomies', array( __CLASS__, 'ajax_get_taxonomies'));
		add_action('wp_ajax_action_get_terms', array( __CLASS__, 'ajax_get_terms'));
		add_action('wp_ajax_action_remove_terms', array( __CLASS__, 'ajax_remove_terms'));
		add_action('wp_ajax_action_updated_taxonomies', array( __CLASS__, 'ajax_updated_taxonomies'));
		add_action('wp_ajax_action_taxonomies_sort', array( __CLASS__, 'ajax_taxonomies_sort'));
		add_action('wp_ajax_action_terms_sort', array( __CLASS__, 'ajax_terms_sort'));
		add_action('wp_ajax_action_selected_posts', array( __CLASS__, 'ajax_selected_posts'));
		add_action('wp_ajax_action_search_feed_posts', array( __CLASS__, 'ajax_search_feed_posts'));
		add_action('wp_ajax_action_save_taxonomy_attrs', array( __CLASS__, 'ajax_save_taxonomy_attrs'));
		add_action('wp_ajax_action_save_term_attrs', array( __CLASS__, 'ajax_save_term_attrs'));
		add_action('wp_ajax_action_get_selected_taxonomies', array( __CLASS__, 'ajax_get_selected_taxonomies'));
		add_action('wp_ajax_action_upload_term_icon', array( __CLASS__, 'ajax_upload_term_icon'));
		add_action('wp_ajax_action_export_settings', array( __CLASS__, 'ajax_export_settings'));
		add_action('wp_ajax_action_import_settings', array( __CLASS__, 'ajax_import_settings'));
	}

	/**
	 * Get taxonomies
	 * @return void
	 */
	public static function ajax_get_taxonomies() : void {
		 check_ajax_referer('get-taxonomy-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$post_id = $payload->post_id;
		$post_types = $payload->post_types;
		$list_taxonomies = [];
		$list_posts = [];

		if (empty($post_id) || empty($post_types) || !is_array($post_types)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_types = array_map( 'sanitize_text_field', wp_unslash($post_types));

		update_post_meta( $post_id, 'ymc_fg_post_types', $post_types );
		update_post_meta( $post_id, 'ymc_fg_taxonomies', []);
		update_post_meta( $post_id, 'ymc_fg_terms', []);
		update_post_meta( $post_id, 'ymc_fg_tax_attrs', []);
		update_post_meta( $post_id, 'ymc_fg_term_attrs', []);
		update_post_meta( $post_id, 'ymc_fg_tax_sort', []);
		update_post_meta( $post_id, 'ymc_fg_term_sort', []);
		update_post_meta( $post_id, 'ymc_fg_selected_posts', []);
		update_post_meta( $post_id, 'ymc_fg_filter_options', []);

		// Get taxonomies
		$data_object = get_object_taxonomies($post_types, 'objects');
		if(!empty($data_object)) {
			foreach ($data_object as $value) {
				$list_taxonomies[$value->name] = $value->label;
			}
			asort($list_taxonomies);
		}

		// Get post types
		$query = new \WP_query([
			'post_type' => $post_types,
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => 20
		]);
		$found_posts = $query->found_posts;
		if ( $query->have_posts() ) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();
				$list_posts[] = [
					'id' => $post_id,
					'title' => get_the_title($post_id)
				];
			}
		}

		wp_send_json([
			'taxonomies' => $list_taxonomies,
			'posts' => $list_posts,
			'found_posts' => $found_posts
		]);
	}


	/**
	 * Get terms
	 * @return void
	 */
	public static function ajax_get_terms() : void {
		check_ajax_referer('get-term-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$slug = $payload->slug;
		$label = $payload->label;
		$post_id = (int) $payload->post_id;
		$item_term = [];
		$data = [];

		if (empty($slug) || empty($label) || empty($post_id)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_types = (array) Data_Store::get_meta_value($post_id,'ymc_fg_post_types');

		$args = [
			'taxonomy' => $slug,
			'hide_empty' => false
		];
		$terms = get_terms($args);

		if($terms && ! is_wp_error($terms)) {

			foreach( $terms as $term ) {
				Term::update_post_counts($slug, $term->term_id, Term::get_cache_key());
				$post_count = Term::get_post_counts($post_types, $term->term_id);

				$item_term[] = [
					'term_id' => $term->term_id,
					'name'    => $term->name,
					'slug'    => $term->slug,
					'count'   => $post_count
				];
			}
		}

		$data['tax_slug']   =  $slug;
		$data['tax_label']  =  $label;
		$data['terms']      =  $item_term;


		wp_send_json([
			'data_obj' => $data,
			'post_types' => $post_types
		]);

	}


	/**
	 * Clear taxonomies
	 * @return void
	 */
	public static function ajax_remove_terms() : void {
		check_ajax_referer('remove-terms-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));

		if (empty($payload->post_id)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_id = (int) $payload->post_id;

		update_post_meta($post_id, 'ymc_fg_taxonomies', []);
		update_post_meta($post_id, 'ymc_fg_terms', [] );
		update_post_meta($post_id, 'ymc_fg_tax_attrs', []);
		update_post_meta($post_id, 'ymc_fg_term_attrs', []);
		update_post_meta($post_id, 'ymc_fg_tax_sort', []);
		update_post_meta($post_id, 'ymc_fg_term_sort', []);

		wp_send_json([
			'response' => 'ok'
		]);
	}


	/**
	 * Updated taxonomies
	 * @return void
	 */
	public static function ajax_updated_taxonomies() : void {
		check_ajax_referer('updated-tax-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$post_types = $payload->post_types;
		$list_taxonomies = [];

		if (empty($post_types) || !is_array($post_types)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$data_object = get_object_taxonomies($post_types, 'objects');
		if(!empty($data_object)) {
			foreach ($data_object as $value) {
				$list_taxonomies[$value->name] = $value->label;
			}
			asort($list_taxonomies);
		}

		wp_send_json([
			'taxonomies' => $list_taxonomies
		]);
	}


	/**
	 * Custom Sort taxonomies
	 * @return void
	 */
	public static function ajax_taxonomies_sort() : void {
		check_ajax_referer('sort-tax-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$tax_sort = $payload->tax_sort;

		if (empty($payload->post_id) || empty($tax_sort) || !is_array($tax_sort)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_id = (int) $payload->post_id;
		$updated = update_post_meta( $post_id, 'ymc_fg_tax_sort', $tax_sort );

		wp_send_json([
			'updated' => $updated
		]);
	}


	/**
	 * Custom Sort terms
	 * @return void
	 */
	public static function ajax_terms_sort() : void {
		check_ajax_referer('sort-term-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$terms_sort = $payload->terms_sort;

		if (empty($payload->post_id) || empty($terms_sort) || !is_array($terms_sort)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_id = (int) $payload->post_id;
		$updated = update_post_meta( $post_id, 'ymc_fg_term_sort', $terms_sort );

		wp_send_json([
			'updated' => $updated
		]);
	}


	/**
	 * Selected posts
	 * @return void
	 */
	public static function ajax_selected_posts() : void {
		check_ajax_referer('select-posts-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$post_types = $payload->post_types;
		$paged = $payload->paged;
		$posts_per_page = $payload->posts_per_page;
		$post_id = (int) $payload->post_id;
		$posts = [];
		$is_disabled = '';

		$query = new \WP_query([
			'post_type' => $post_types,
			'orderby' => 'title',
			'order' => 'ASC',
			'paged' => $paged,
			'posts_per_page' => $posts_per_page
		]);

		if ($query->have_posts()) {
			$selected_posts = Data_Store::get_meta_value($post_id,'ymc_fg_selected_posts');
			while ($query->have_posts()) {
				$query->the_post();
				$pid = get_the_ID();
				$title = get_the_title($pid);
				if( in_array( $pid, $selected_posts ) ) {
					$is_disabled = 'is-disabled';
				}
				$posts[] = [
					'id' => $pid,
					'title' => $title,
					'is_disabled' => $is_disabled
				];
				$is_disabled = '';
			}
		}
		$data = array(
			'posts_loaded' => count($posts),
			'found_posts' => $query->found_posts,
			'posts' => $posts
		);
		wp_send_json($data);
	}


	/**
	 * Search feed posts
	 * @return void
	 */
	public static function ajax_search_feed_posts() : void {
		check_ajax_referer('search-feed-posts-ajax-nonce', 'nonce_code');

		$payload = json_decode(sanitize_text_field(wp_unslash($_POST['payload'])));
		$post_types = $payload->post_types;
		$phrase = trim($payload->phrase);
		$post_id = (int) $payload->post_id;
		$posts = [];
		$is_disabled = '';

		if (empty($post_id) || empty($post_types) || !is_array($post_types)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$args = [
			'post_type' => $post_types,
			'posts_per_page' => 60,
			'orderby' => 'title',
			'order' => 'asc',
			'sentence' => true,
			's' => $phrase
		];

		$query = new \WP_Query($args);

		if ( $query->have_posts() ) {
			$selected_posts = Data_Store::get_meta_value($post_id,'ymc_fg_selected_posts');

			while ($query->have_posts()) {
				$query->the_post();
				$pid = get_the_ID();
				$title = get_the_title($pid);
				if( in_array( $pid, $selected_posts ) ) {
					$is_disabled = 'is-disabled';
				}
				$posts[] = [
					'id' => $pid,
					'title' => $title,
					'is_disabled' => $is_disabled
				];
				$is_disabled = '';
			}
		}

		$data = array(
			'found_posts' => $query->found_posts,
			'posts' => $posts
		);
		wp_send_json($data);
	}


	/**
	 * Save taxonomy attributes
	 * @return void
	 */
	public static function ajax_save_taxonomy_attrs() : void {
		check_ajax_referer('save-taxonomy-attr-ajax-nonce', 'nonce_code');

		$payload_raw = wp_unslash($_POST['payload'] ?? '');
		$payload = json_decode(sanitize_text_field($payload_raw));

		if (empty($payload->post_id) || empty($payload->tax_attrs) || !is_array($payload->tax_attrs)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}
		$post_id = (int) $payload->post_id;
		$tax_attrs = array_map(function ($item) {
			return (array) $item;
		}, $payload->tax_attrs);

		$updated = update_post_meta( $post_id, 'ymc_fg_tax_attrs', $tax_attrs );

		$data = array(
			'response' => $updated,
			'message' => __('Taxonomy saved', 'ymc-smart-filters')
		);
		wp_send_json($data);
	}


	/**
	 * Save term attributes
	 * @return void
	 */
	public static function ajax_save_term_attrs() : void {
		check_ajax_referer('save-term-attr-ajax-nonce', 'nonce_code');

		$payload_raw = wp_unslash($_POST['payload'] ?? '');
		$payload = json_decode(sanitize_text_field($payload_raw));

		if (empty($payload->post_id) || empty($payload->term_attrs) || !is_array($payload->term_attrs)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_id = (int) $payload->post_id;
		$term_attrs = array_map(function ($item) {
			return (array) $item;
		}, $payload->term_attrs);

		$updated = update_post_meta( $post_id, 'ymc_fg_term_attrs', $term_attrs );

		$data = array(
			'response' => $updated,
			'message' => __('Term saved', 'ymc-smart-filters')
		);
		wp_send_json($data);
	}


	/**
	 * Get selected taxonomies
	 * @return void
	 */
	public static function ajax_get_selected_taxonomies() : void {
		check_ajax_referer('get-select-tax-ajax-nonce', 'nonce_code');

		$payload_raw = wp_unslash($_POST['payload'] ?? '');
		$payload = json_decode(sanitize_text_field($payload_raw));
		$placements   = [];
		$taxonomies   = [];
		$filter_types = [];

		if (empty($payload->post_id)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$post_id = (int) $payload->post_id;

		// Filter Types
		$filter_types_raw = UiLabels::all('filter_types');
		unset($filter_types_raw['composite']);
		if($filter_types_raw) {
			foreach( $filter_types_raw as $key => $value ) {
				$filter_types[] = [
					'slug'  => $key,
					'label' => $value
				];
			}
		}

		// Taxonomies selected
		$post_types = Data_Store::get_meta_value($post_id, 'ymc_fg_post_types');
		$tax_selected = Data_Store::get_meta_value($post_id, 'ymc_fg_taxonomies');
		$tax_selected_raw = array_intersect_key(ymc_get_taxonomies($post_types), array_flip($tax_selected));
		if($tax_selected_raw) {
			foreach ($tax_selected_raw as $key => $value) {
				$taxonomies[] = [
					'slug'  => $key,
					'label' => $value
				];
			}
		}

		 // Placements
		$placements_raw = UiLabels::all('placements');
		if($placements_raw) {
			foreach( $placements_raw as $key => $value ) {
				$placements[] = [
					'slug'  => $key,
					'label' => $value
				];
			}
		}

		$data['taxSelected'] =  $taxonomies;
		$data['filtersTypes'] =  $filter_types;
		$data['placements']  =  $placements;

		wp_send_json($data);

	}


	/**
	 * Upload term icon
	 * @return void
	 */
	public static function ajax_upload_term_icon() : void {
		check_ajax_referer('upload-term-icon-ajax-nonce', 'nonce_code');

		if (!current_user_can('upload_files')) {
			wp_send_json_error(['message' => 'Permission denied.']);
		}

		if (!isset($_FILES['icon'])) {
			wp_send_json_error(['message' => 'No file uploaded.']);
		}

		add_filter('upload_mimes', function($mimes) {
			$mimes['svg']  = 'image/svg+xml';
			$mimes['webp'] = 'image/webp';
			return $mimes;
		});

		$file = $_FILES['icon'];

		$allowed = ['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'];
		if (!in_array($file['type'], $allowed, true)) {
			wp_send_json_error([
				'message' => 'Invalid file type.'
			]);
		}

		// Handle upload
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$upload = wp_handle_upload($file, ['test_form' => false]);
		if (isset($upload['error'])) {
			wp_send_json_error(['message' => 'Upload failed: ' . $upload['error']]);
		}

		if (isset($upload['url'])) {
			$relative_url = str_replace(site_url(), '', $upload['url']);
			wp_send_json_success(
				[
					'url' => esc_url($relative_url),
					'message' => __('Icon uploaded', 'ymc-smart-filters')
				]
			);
		}

		wp_send_json_error(['message' => 'Upload failed.']);

	}


	/**
	 * Export settings
	 * @return void
	 */
	public static function ajax_export_settings(): void {
		check_ajax_referer('export-settings-ajax-nonce', 'nonce_code');

		$post_id = sanitize_text_field(wp_unslash($_POST["post_id"]));

		if (empty($post_id)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$output = [];
		$options = get_post_meta($post_id);

		if (!empty($options) && is_array($options)) {
			foreach ($options as $key => $value) {
				if (str_starts_with($key, 'ymc_fg_')) {
					if ($key !== 'ymc_fg_custom_css' && $key !== 'ymc_fg_custom_js') {
						foreach ($value as $item) {
							$output[$key] = maybe_unserialize($item);
						}
					} else {
						$output[$key] = '';
					}
				}
			}
		}

		$json_data = wp_json_encode($output);

		header('Content-Type: application/json');
		header('Content-Disposition: attachment; filename="ymc-fg-settings-export.json"');
		header('Content-Length: ' . strlen($json_data));
		// phpcs:ignore WordPress
		echo $json_data;
		wp_die();
	}


	/**
	 * Import settings
	 * @return void
	 */
	public static function ajax_import_settings(): void {
		check_ajax_referer('import-settings-ajax-nonce', 'nonce_code');

		$post_id = sanitize_text_field(wp_unslash($_POST["post_id"]));
		$params = sanitize_text_field(wp_unslash($_POST["params"]));
		$message = '';

		if (!current_user_can('upload_files')) {
			wp_send_json_error(['message' => 'Permission denied.']);
		}

		if (!isset($_FILES['file']) && empty($params)) {
			wp_send_json_error(['message' => 'No file uploaded.']);
		}

		if (empty($post_id)) {
			wp_send_json_error([
				'message' => __('Invalid data received.', 'ymc-smart-filters')
			], 400);
		}

		$clean_data = json_decode($params, true);

		if(is_array($clean_data) && !empty($clean_data)) {
			foreach ( $clean_data as $meta_key => $meta_value ) {
				update_post_meta($post_id, $meta_key, $meta_value);
			}
			$message = __('Imported settings successfully','ymc-smart-filters');
		} else {
			$message = __('Import of settings unsuccessful. Try again.','ymc-smart-filters');
		}

		wp_send_json_success(['message' => $message]);
	}

}