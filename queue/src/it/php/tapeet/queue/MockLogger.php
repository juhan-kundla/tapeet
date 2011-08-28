<?php
namespace tapeet\queue;


class MockLogger {


	function debug($msg) {
		echo 'DEBUG: ' . $msg;
	}


	function warn($msg) {
		echo 'WARN: ' . $msg;
	}

}
