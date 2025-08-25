<?php global $post_new_file, $post_type_object, $post; ?>
<div class='ymc-admin-toolbar'>
    <div class="admin-toolbar-inner">
        <div class='logo'><img src='<?php echo esc_url(YMC_PLUGIN_URL) ?>assets/images/ymc-logo.png' alt='YNC Logo'></div>
        <div class="info-bar">
            <a class="information" href="<?php echo esc_url('https://github.com/YMC-22/Filter-Grids') ?>"
               target="_blank">
                <i class="fa-solid fa-circle-info"></i>
                <?php esc_html_e('Documentation', 'ymc-smart-filters'); ?></a>
        </div>
    </div>
</div>
<div class="ymc-header-bar">
    <div class="header-bar-inner">
        <div class="title-wrap">
            <div class="page-title page-title--groups"><?php esc_html_e('All Filters & Grids', 'ymc-smart-filters'); ?></div>
        </div>
        <div class="header-bar-actions" id="submitpost">
		    <?php
		    printf(
		 '<a href="%1$s" class="%2$s"><i class="fa-solid fa-plus"></i>%3$s</a>',
			    esc_url( admin_url( $post_new_file ) ),
			    esc_attr( 'button button--secondary' ),
			    esc_html( $post_type_object->labels->add_new )
		    );
		    ?>
        </div>
    </div>
</div>