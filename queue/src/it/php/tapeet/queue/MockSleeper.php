<?php
namespace tapeet\queue;


class MockSleeper {


	private $count = 0;


	function sleep() {
		$this->count++;
		if ($this->count > 1) {
			throw new InterruptedException();
		}
	}

}
