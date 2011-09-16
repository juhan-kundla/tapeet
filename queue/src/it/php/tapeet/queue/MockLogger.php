<?php
namespace tapeet\queue;


class MockLogger {


	function debug($msg) {
		echo 'DEBUG: ' . $msg . '\n';
	}


	function warn($msg) {
		echo 'WARN: ' . $msg . '\n';
	}

}
