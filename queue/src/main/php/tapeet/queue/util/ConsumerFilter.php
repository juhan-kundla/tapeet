<?php
namespace tapeet\queue\util;


use \tapeet\Filter;


class ConsumerFilter implements Filter {


	public $consumer;


	function doFilter($chain) {
		$this->consumer->run();
		$chain->doFilter();
	}

}
