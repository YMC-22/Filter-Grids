<?php

namespace YMCFilterGrids\frontend;

use YMCFilterGrids\abstracts\FG_Abstract_Filter_Impl;
use YMCFilterGrids\FG_Data_Store as Data_Store;
use YMCFilterGrids\interfaces\IFilter;

/**
 * Class FG_Filter_Date_Picker
 *
 * @since 3.0.0
 */
class FG_Filter_Date_Picker extends FG_Abstract_Filter_Impl implements IFilter {

	public function render( int $filter_id, array $tax_name, array $filter_options ): string {

		$placement = $filter_options['placement'];
		$is_multiple_mode = Data_Store::get_meta_value($filter_id, 'ymc_fg_selection_mode');

		ob_start();	?>

			<div class="filter filter-date-picker filter-date-picker-<?php echo esc_attr($placement); ?> filter-<?php echo esc_attr($filter_id); ?>"
			     data-filter-type="date_picker"
                 data-selection-mode="<?php echo esc_attr( $is_multiple_mode ); ?>">
				<div class="filter-date-picker-inner">

                    <div class="date-picker-wrapper">
                        <div class="date-picker__selected js-dropdown-selected">
		                    <?php esc_html_e('All time', 'ymc-smart-filters'); ?>
                        </div>

                        <div class="date-picker__range js-date-range">
                            <div class="notification notification--warning is-hidden">
                                <?php esc_html_e('The date range is incorrect.', 'ymc-smart-filters'); ?></div>
                            <div class="date-picker__range-from">
                                <div class="datepicker-wrapper">
                                    <header class="headline"><?php esc_attr_e('From', 'ymc-smart-filters'); ?></header>
                                    <input class="datepicker" type="text" name="date_from"
                                           data-timestamp="<?php echo esc_attr(strtotime(gmdate("Y-m-d"))); ?>" value="<?php echo esc_attr(gmdate('M d, Y')); ?>">
                                </div>
                            </div>
                            <div class="date-picker__range-to">
                                <div class="datepicker-wrapper">
                                    <header class="headline"><?php esc_attr_e('To', 'ymc-smart-filters'); ?></header>
                                    <input class="datepicker" type="text" name="date_to"
                                           data-timestamp="<?php echo esc_attr(strtotime(gmdate("Y-m-d"))); ?>" value="<?php echo esc_attr(gmdate('M d, Y')); ?>">
                                </div>
                            </div>
                            <div class="buttons-action">
                                <button class="date-picker__btn date-picker__btn--apply js-btn-apply">
                                    <?php esc_attr_e('Apply', 'ymc-smart-filters'); ?></button>
                                <button class="date-picker__btn date-picker__btn--cancel js-btn-cancel">
                                    <?php esc_attr_e('Cancel', 'ymc-smart-filters'); ?></button>
                            </div>
                        </div>

                        <div class="date-picker__dropdown">
                            <div class="date-picker__close js-dropdown-close" title="Close">&#10006;</div>
                            <ul class="date-picker__list">
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="all">
                                        <input id="all" type="radio" name="date"  value="All time" data-value="all_time">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('All time', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="today">
                                        <input id="today" type="radio" name="date" value="Today" data-value="today">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Today', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="yesterday">
                                        <input id="yesterday" type="radio" name="date" value="Yesterday" data-value="yesterday">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Yesterday', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="3_days">
                                        <input id="3_days" type="radio" name="date" value="3 days" data-value="3_days">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Last 3 days', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="last_week">
                                        <input id="last_week" type="radio" name="date" value="Last week" data-value="last_week">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Last week', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="last_month">
                                        <input id="last_month" type="radio" name="date" value="Last month" data-value="last_month">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Last month', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="last_year">
                                        <input id="last_year" type="radio" name="date" value="Last year" data-value="last_year">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Last year', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                                <li class="date-picker__item js-dropdown-item">
                                    <label class="date-picker__label" for="other">
                                        <input id="other" type="radio" name="date" value="Other time" data-value="other_time">
                                        <span class="checkmark"></span>
                                        <span class="name"><?php esc_html_e('Other time', 'ymc-smart-filters'); ?></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>

				</div>
			</div>


		<?php return ob_get_clean();
	}

}