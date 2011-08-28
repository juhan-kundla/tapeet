<?php
namespace tapeet\queue\util;


require_once 'System/Daemon.php';


/** @Service('sleeper') */
class DaemonSleeper {


	/** @Configuration('daemon_sleep_time') */
	public $sleepTime;


	function sleep() {
		System_Daemon::iterate($this->sleepTime);
	}

}
?>