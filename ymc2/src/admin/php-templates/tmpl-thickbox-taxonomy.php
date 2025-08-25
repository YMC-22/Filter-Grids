<div class="thickbox-tax-modal" id="thickbox-tax-modal" style="display:none;">
    <div class="thickbox-inner">
        <div class="toolbar">
            <div class="toolbar-inner">
                <div class="info-bar">
                    <p>
                        <?php esc_html_e('Customize the display and name of the taxonomy used to group content. 
                        Background and color settings will apply to the following filter types: Dropdown.', 'ymc-smart-filters' ); ?>
                    </p>
                </div>
                <div class="actions">
                    <button class="button button--secondary js-btn-tax-reset" type="button">
		                <?php esc_attr_e('Reset', 'ymc-smart-filters' ); ?></button>
                    <button class="button button--primary js-btn-tax-save" type="button">
		                <?php esc_attr_e('Save', 'ymc-smart-filters' ); ?></button>
                </div>
            </div>
        </div>
        <div class="form-taxonomy">
            <div class="form-item">
                <header class="form-label">
                    <span class="heading-text"><?php esc_attr_e('Taxonomy Background', 'ymc-smart-filters' ); ?></span>
                </header>
                <span class="description"><?php esc_attr_e('Set taxonomy background.', 'ymc-smart-filters' ); ?></span>
                <input class="js-picker-color-alpha js-tax-bg" data-alpha-enabled="true" type="text" name='tax_bg' value="" />
            </div>
            <div class="form-item">
                <header class="form-label">
                    <span class="heading-text"><?php esc_attr_e('Taxonomy Color', 'ymc-smart-filters' ); ?></span>
                </header>
                <span class="description"><?php esc_attr_e('Set taxonomy color', 'ymc-smart-filters' ); ?></span>
                <input class="js-picker-color-alpha js-tax-color" data-alpha-enabled="true" type="text" name='tax_color' value="" />
            </div>
            <div class="form-item">
                <header class="form-label">
                    <span class="heading-text"><?php esc_attr_e('Taxonomy Name', 'ymc-smart-filters' ); ?></span>
                </header>
                <span class="description"><?php esc_attr_e('Set taxonomy name', 'ymc-smart-filters' ); ?></span>
                <input class="form-input js-tax-name" type="text" name='tax_name' />
            </div>
        </div>
    </div>
</div>
