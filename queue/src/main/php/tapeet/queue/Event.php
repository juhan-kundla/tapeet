<?php
namespace tapeet\queue;


class Event {


	public $id;
	public $payload;
	public $type;


	function __construct($type, $payload) {
		$this->payload = $payload;
		$this->type = $type;
	}


	function get($property) {
		$payload = json_decode($this->payload, true);
		return $payload[$property];
	}

}
