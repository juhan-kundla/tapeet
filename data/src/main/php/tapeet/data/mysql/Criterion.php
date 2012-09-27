<?php
namespace tapeet\data\mysql;


class Criterion {


	public $parameters;
	public $criterion;


	function __construct($criterion, $parameters) {
		$this->criterion = $criterion;
		$this->parameters = $parameters;
	}

}
