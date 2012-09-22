<?php
namespace tapeet\rest\util;


use \tapeet\Filter;
use \tapeet\FilterChain;


class ApplicationFilter implements Filter {


	public $application;


	function execute(FilterChain $chain) {
		$this->application->execute();
		return $chain->execute();
	}

}
