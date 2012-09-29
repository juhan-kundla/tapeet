<?php
namespace tapeet\daemon;


use \System_Daemon;


class DaemonSleeper {


	public $sleepTime;


	function sleep() {
		System_Daemon::iterate($this->sleepTime);
	}

}
