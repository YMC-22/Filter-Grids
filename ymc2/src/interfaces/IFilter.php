<?php declare( strict_types = 1 );

namespace YMCFilterGrids\interfaces;

/**
 * Interface IFilter
 *
 * @package YMCFilterGrids\interfaces
 * @version  3.0.0
 */
interface IFilter {
	public function render(int $filter_id, array $tax_name, array $filter_options) : string;
}