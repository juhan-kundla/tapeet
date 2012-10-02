<?php
namespace tapeet\util;


use \tapeet\Filter;
use \tapeet\FilterChain;


class SideChainFilter implements Filter {


	public $sideChain;


	function execute(FilterChain $chain) {
		$this->sideChain->execute();
		return $chain->execute();
	}

}
