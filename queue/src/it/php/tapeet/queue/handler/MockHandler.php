<?php
namespace tapeet\queue\handler;


use \tapeet\queue\annotation\Event;


class MockHandler {


	public $eventTriggered = false;
	/** @Event */
	public $test;

	function onEvent() {
		$this->eventTriggered = true;
	}

}
