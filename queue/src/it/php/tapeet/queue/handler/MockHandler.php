<?php
namespace tapeet\queue\handler;


class MockHandler {


	public $eventTriggered = false;
	/** @Event */
	public $test;

	function onEvent() {
		$this->eventTriggered = true;
	}

}
