<?php
namespace tapeet\web;


use tapeet\web\ioc\IOCProxy;


class FilterChain {


	/** @Service */
	public $filters;


	public function doFilter() {
		$filterClass = array_shift($this->filters);
		if ($filterClass != null) {
			$filter = new IOCProxy($filterClass);
			$filter->doFilter($this);
		}
	}


}
?>