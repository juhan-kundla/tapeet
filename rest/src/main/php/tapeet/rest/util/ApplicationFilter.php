<?php
namespace tapeet\rest\util;


use \tapeet\Filter;
use \tapeet\FilterChain;


class ApplicationFilter implements Filter {


	public $application;


	function doFilter(FilterChain $chain) {
		$this->application->doFilter();
		return $chain->doFilter();
	}

}
