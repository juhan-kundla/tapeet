<?php
namespace tapeet;


class FilterChain {


	public $filters;


	public function doFilter() {
		$filter = array_shift($this->filters);
		if ($filter !== null) {
			$filter->doFilter($this);
		}
	}

}
