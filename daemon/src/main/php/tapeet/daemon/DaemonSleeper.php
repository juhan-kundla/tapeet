<?php
namespace tapeet\queue\util;


require_once 'System/Daemon.php';


class DaemonSleeper {


	public $sleepTime;


	function sleep() {
		System_Daemon::iterate($this->sleepTime);
	}

}
