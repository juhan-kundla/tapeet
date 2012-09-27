<?php
namespace tapeet\data\mysql;


class Join {


	const INNER = 'JOIN';
	const LEFT = 'LEFT JOIN';


	public $condition;
	public $table;
	public $type;


	function __construct($type, $table, $condition) {
		$this->condition = $condition;
		$this->table = $table;
		$this->type = $type;
	}

}
