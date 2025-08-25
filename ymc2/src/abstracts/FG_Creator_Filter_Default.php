<?php declare( strict_types = 1 );

namespace YMCFilterGrids\abstracts;

use YMCFilterGrids\frontend\FG_Filter_Default;
use YMCFilterGrids\interfaces\IFilter;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class FG_Creator_Default
 *
 * Creates a new Default Filter.
 *
 * @version  3.0.0
 * @package YMCFilterGrids\abstracts
 */
class FG_Creator_Filter_Default extends FG_Abstract_Filter {
	public function factoryFilter() : IFilter {
		return new FG_Filter_Default();
	}
}


