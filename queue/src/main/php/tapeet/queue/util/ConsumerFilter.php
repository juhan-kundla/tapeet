<?php
namespace tapeet\queue\util;


use \tapeet\Filter;
use \tapeet\FilterChain;


class ConsumerFilter implements Filter {


	public $consumer;


	function doFilter(FilterChain $chain) {
		$this->consumer->run();
		$chain->doFilter();
	}

}
