<?php declare( strict_types = 1 );

namespace YMCFilterGrids\abstracts;

use YMCFilterGrids\frontend\FG_Filter_Date_Picker;
use YMCFilterGrids\interfaces\IFilter;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class FG_Creator_Filter_Range_Slider
 *
 * Creates a new Range Slider.
 *
 * @version  3.0.0
 * @package YMCFilterGrids\abstracts
 */
class FG_Creator_Filter_Date_Picker extends FG_Abstract_Filter {
	public function factoryFilter() : IFilter {
		return new FG_Filter_Date_Picker();
	}
}


