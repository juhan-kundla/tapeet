<?php
namespace tapeet;


class FilterChain {


	public $chain;


	public function execute() {
		$filter = array_shift($this->chain);
		if ($filter !== null) {
			$filter->execute($this);
		}
	}

}
