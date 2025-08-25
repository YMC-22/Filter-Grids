<?php declare( strict_types = 1 );

namespace YMCFilterGrids\abstracts;
use YMCFilterGrids\interfaces\IFilter;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Abstract Filter
 *
 * Creates a new filter.
 *
 * @version  3.0.0
 * @package YMCFilterGrids\abstracts
 */

abstract class FG_Abstract_Filter {
	abstract public function factoryFilter() : IFilter;

	public function create_filter( int $filter_id, array $tax_name, array $filter_options ) : string {
		$filter = $this->factoryFilter();
		return $filter->render($filter_id, $tax_name, $filter_options);
	}

}