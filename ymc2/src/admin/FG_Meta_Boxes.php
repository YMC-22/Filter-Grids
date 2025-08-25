<?php declare( strict_types = 1 );

namespace YMCFilterGrids\admin;

use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\FG_Template as Template;

defined( 'ABSPATH' ) || exit;

/**
 * FG_Meta_Boxes Class
 * Add Meta Boxes
 *
 * @since 3.0.0
 */

class FG_Meta_Boxes {

	/**
	 * Hook in methods.
	 */
	public static function init() : void {
		add_action( 'add_meta_boxes', array(__CLASS__, 'add_metabox'));
		add_action( 'add_meta_boxes', array(__CLASS__, 'attached_filters'));
		add_action( 'current_screen', array(__CLASS__, 'current_screen' ));
		add_action( 'admin_bar_menu', array(__CLASS__, 'admin_bar_menu'), 120);
		add_action( 'wp_dashboard_setup', array(__CLASS__, 'filter_grids_widget'));
		add_filter( 'post_updated_messages', array(__CLASS__, 'custom_post_updated_messages') );
	}

    public static function custom_post_updated_messages( $messages ) : array {
		$messages['ymc_filters'][1]  = __('Filter updated.', 'ymc-smart-filters');
		$messages['ymc_filters'][6]  = __('Filter published.', 'ymc-smart-filters');
		$messages['ymc_filters'][7]  = __('Filter published.', 'ymc-smart-filters');
		$messages['ymc_filters'][10] = __('Filter draft updated.', 'ymc-smart-filters');

		return $messages;
	}

	public static function admin_body_class( $classes ) : string {
		$classes .= ' ymc-admin-page js-ymc-admin';
		return $classes;
	}

    public static function hidden_meta_boxes( $hidden, $screen ) : array {
	    if (in_array( $screen->id, array('ymc_filters'), true )) {
	        $hidden = [ 'slugdiv' ];
	    }
        return $hidden;
    }

	public static function in_admin_header() : void {
		Template::render(dirname( __FILE__ ) . '/php-templates/tmpl-admin-header.php');
	}

	public static function in_admin_header_groups() : void {
		Template::render(dirname( __FILE__ ) . '/php-templates/tmpl-admin-header-groups.php');
	}

	public static function in_admin_header_license_updates() : void {
		Template::render(dirname( __FILE__ ) . '/php-templates/tmpl-admin-header-license-updates.php');
	}

	public static function current_screen( $screen ) : void {

        // Filter Grids List
		if ( 'ymc_filters' === $screen->post_type && in_array( $screen->id, array('edit-ymc_filters'), true )) {
			add_action( 'admin_body_class', array(__CLASS__, 'admin_body_class'));
			add_action('in_admin_header', array(__CLASS__, 'in_admin_header_groups'));
		}
        // New or Edit Filter Grid
		if ( 'ymc_filters' === $screen->post_type && in_array( $screen->id, array('ymc_filters'), true )) {
			add_action('admin_body_class', array(__CLASS__, 'admin_body_class'));
			add_action('in_admin_header', array(__CLASS__, 'in_admin_header'));
			add_filter('hidden_meta_boxes', array(__CLASS__, 'hidden_meta_boxes'), 10, 2 );

			remove_post_type_support( 'ymc_filters', 'title' );
			remove_meta_box('submitdiv', 'ymc_filters', 'normal');
		}
        // License & Updates Page
		if ( 'ymc_filters' === $screen->post_type &&
		     ( in_array( $screen->id, array('ymc_filters_page_ymc-license'), true ) ||
               in_array( $screen->id, array('ymc_filters_page_ymc-settings'), true )) ) {

			add_action( 'admin_body_class', array(__CLASS__, 'admin_body_class'));
			add_action('in_admin_header', array(__CLASS__, 'in_admin_header_license_updates'));
		}
	}

	public static function add_metabox() : void {
		add_meta_box( 'ymc_main_meta_box' , __('Settings', 'ymc-smart-filters'), array(__CLASS__,'top_meta_box'), 'ymc_filters', 'normal', 'core');
		add_meta_box( 'ymc_side_meta_box' , __('Filter & Grids Features', 'ymc-smart-filters'), array(__CLASS__,'side_meta_box'), 'ymc_filters', 'side', 'core');
    }

	public static function top_meta_box() : void {
        global $post;
        $post_id = $post->ID; ?>
        <div class="ymc-main" data-post-id="<?php echo esc_attr($post_id); ?>">
            <div class="tabs-sidebar">
                <nav class="nav">
                    <ul class="nav__list">
                        <li class="nav__item is-current" data-hash="general">
                            <button class="button" type="button" aria-label="button">
	                            <span class="button__text"><?php echo esc_html__('General','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Post type, categories','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-admin-tools"></span>
                        </li>
                        <li class="nav__item" data-hash="layouts">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Layouts','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Post layout, filter layout','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-editor-table"></span>
                        </li>
                        <li class="nav__item" data-hash="appearance">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Appearance','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Post, filter, popup, pagination settings','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-visibility"></span>
                        </li>
                        <li class="nav__item" data-hash="search">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Search','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Posts search','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-search"></span>
                        </li>
                        <li class="nav__item" data-hash="typography">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Typography','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Title, description fonts','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-editor-spellcheck"></span>
                        </li>
                        <li class="nav__item" data-hash="advanced">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Advanced','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Add extra classes to post','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-tag"></span>
                        </li>
                        <li class="nav__item" data-hash="shortcode">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Shortcode','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Get your shortcode','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-shortcode"></span>
                        </li>
                        <li class="nav__item" data-hash="tools">
                            <button class="button" type="button" aria-label="button">
                                <span class="button__text"><?php echo esc_html__('Tools','ymc-smart-filters'); ?></span>
                                <span class="button__description">
                                    <?php echo esc_html__('Export / Import settings','ymc-smart-filters'); ?>
                                </span>
                            </button>
                            <span class="dashicons dashicons-controls-repeat"></span>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="tabs-content">
                <?php
                    $data = Data_Store::get_all_meta_values($post_id);

                    $tabs = [
                        'general'    => 'General',
                        'layouts'    => 'Layouts',
                        'appearance' => 'Appearance',
                        'search'     => 'Search',
                        'typography' => 'Typography',
                        'advanced'   => 'Advanced',
                        'shortcode'  => 'Shortcode',
                        'tools'      => 'Tools'
                    ];

                    foreach ($tabs as $key => $value) {
	                    $data['section_name'] = $value;
	                    $data['post_id'] = $post_id;
                        $is_active = ('general' === $key)  ? 'is-active' : '';

                        echo '<div id="'. esc_attr($key) . '" class="section section-'. esc_attr($key) . ' '. esc_attr($is_active) .'">';
	                    Template::render(dirname( __FILE__ ) . '/meta-boxes/'. $key .'.php', $data);
                        echo '</div>';
                    }
                ?>
            </div>
            <?php
                Template::render(dirname( __FILE__ ) . '/php-templates/tmpl-thickbox-taxonomy.php');
                Template::render(dirname( __FILE__ ) . '/php-templates/tmpl-thickbox-term.php');
            ?>
        </div>
	<?php }

    public static function side_meta_box() : void {
	    Template::render(dirname( __FILE__ ) . '/php-templates/tmpl-side-meta-box.php');
    }

	public static function admin_bar_menu($wp_admin_bar) : void {
		$icon = '<span class="ab-icon dashicons dashicons-layout"></span>';
		$title = $icon . '<span class="ab-label">' . esc_html__('Filter & Grids', 'ymc-smart-filters') . '</span>';

		$wp_admin_bar->add_menu(array(
			'id'    => 'ymc-filter-grids',
			'title' => $title,
			'href'  => admin_url('edit.php?post_type=ymc_filters'),
			'meta'  => array( 'target' => '_blank' )
		));
	}

	public static function attached_filters() {
		global $post;

		if ( ! $post || ! isset($post->post_content) ) {
			return;
		}
		preg_match_all('/\[ymc_filter\s+id=[\'"](\d+)[\'"]\]/', $post->post_content, $matches);

		if (empty($matches[1])) {
			return;
		}

		add_meta_box(
			'fg-attached-filters',
			__('Filter & Grids', 'ymc-smart-filters'),
			function() use ($matches) {
				echo '<ul>';
				foreach ($matches[1] as $filter_id) {
					$title = get_the_title($filter_id);
					$link  = get_edit_post_link($filter_id);
					echo '<li>';
                    echo '<span class="dashicons dashicons-sticky"></span>';
					echo '<a href="' . esc_url($link) . '" target="_blank" style="color: #222;text-decoration: none;font-size: 14px;">'
					     . esc_html($title ? $title : 'Filter')
					     . ' (ID: ' . intval($filter_id) . ')</a>';
					echo '</li>';
				}
				echo '</ul>';
			},
			null,
			'side',
			'high'
		);
	}

	public static function filter_grids_widget() {
		wp_add_dashboard_widget(
			'ymc_filter_grids_display',
			__( 'Filter & Grids', 'ymc-smart-filters' ),
			array( __CLASS__, 'filter_grids_callback' )
		);
	}

	public static function filter_grids_callback() {
		$create_url  = admin_url( 'post-new.php?post_type=ymc_filters' );
		$manage_url  = admin_url( 'edit.php?post_type=ymc_filters' );
		$doc_url     = 'https://github.com/YMC-22/Filter-Grids';
		$filters_count = wp_count_posts( 'ymc_filters' )->publish ?? 0;
		?>
        <div class="ymc-dashboard-widget" style="font-size: 13px; line-height: 1.5;">
            <p><strong><?php esc_html_e( 'Welcome to Filter & Grids.', 'ymc-smart-filters' ); ?></strong></p>
            <p><?php esc_html_e( 'This plugin allows you to easily and quickly create all kinds of post grids with their filters.', 'ymc-smart-filters' ); ?></p>

            <p>
                <strong><?php esc_html_e( 'Total Filters:', 'ymc-smart-filters' ); ?></strong>
				<?php echo intval( $filters_count ); ?>
            </p>

            <p>
                <a href="<?php echo esc_url( $create_url ); ?>" class="button button-primary">
					<?php esc_html_e( 'Create New Filter', 'ymc-smart-filters' ); ?>
                </a>
                <a href="<?php echo esc_url( $manage_url ); ?>" class="button">
					<?php esc_html_e( 'Manage Filters', 'ymc-smart-filters' ); ?>
                </a>
            </p>

            <p>
				<?php esc_html_e( 'For more detailed information, see the', 'ymc-smart-filters' ); ?>
                <a target="_blank" href="<?php echo esc_url( $doc_url ); ?>">
					<?php esc_html_e( 'documentation', 'ymc-smart-filters' ); ?>
                    <span class="dashicons dashicons-external" style="text-decoration: none; vertical-align: middle;"></span>
                </a>
            </p>
        </div>
		<?php
	}



}