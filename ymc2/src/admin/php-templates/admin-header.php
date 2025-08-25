<?php global $post_new_file, $post_type_object, $post; ?>
<div class='ymc-admin-toolbar'>
	<div class='logo'><img src='<?php echo esc_url(YMC_PLUGIN_URL) ?>assets/images/YMC-logos.svg' alt='Logo'></div>
</div>
<div class="ymc-headerbar">
    <div class="headerbar-inner">

        <div class="title-wrap">
            <div class="page-title"><?php esc_html_e('Edit Filter & Grids', 'ymc-smart-filters'); ?></div>
            <input form="post" type="text" name="post_title" size="30" value="<?php echo esc_html($post->post_title); ?>"
                   id="title"
                   class="headerbar-title-field"
                   spellcheck="true"
                   autocomplete="off"
                   placeholder="<?php esc_attr_e('Filter & Grids Title', 'ymc-smart-filters'); ?>" />
        </div>


        <div class="headerbar-actions" id="submitpost">
		    <?php
		    printf(
			    '<a href="%1$s" class="%2$s"><i class="fa-solid fa-plus"></i>%3$s</a>',
			    esc_url( admin_url( $post_new_file ) ),
			    esc_attr( 'button button--secondary' ),
			    esc_html( $post_type_object->labels->add_new )
		    );
		    ?>
            <button form="post" class="button button--primary" name="save" type="submit">
			    <?php esc_html_e( 'Save Changes', 'ymc-smart-filters' ); ?>
            </button>
        </div>
    </div>
</div>