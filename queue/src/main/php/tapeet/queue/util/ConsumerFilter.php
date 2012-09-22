<?php
namespace tapeet\queue\util;


use \tapeet\Filter;
use \tapeet\FilterChain;


class ConsumerFilter implements Filter {


	public $consumer;


	function execute(FilterChain $chain) {
		$this->consumer->run();
		return $chain->execute();
	}

}
