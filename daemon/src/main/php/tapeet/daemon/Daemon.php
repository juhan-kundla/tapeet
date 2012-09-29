<?php
namespace tapeet\daemon;


use \System_Daemon;

use \tapeet\Filter;
use \tapeet\FilterChain;


class Daemon implements Filter {


	public $chain;
	public $description;
	public $directory;
	public $daemon;
	public $gid;
	public $logger;
	public $name;
	public $pidFile;
	public $sleepTime;
	public $uid;


	function execute(FilterChain $chain) {
		$options = array(
				 'appName' => $this->name
				,'appDir' => $this->directory
				,'appDescription' => $this->description
				,'appPidLocation' => $this->pidFile
				,'appRunAsGID' => $this->gid
				,'appRunAsUID' => $this->uid
				,'usePEAR' => false
				,'usePEARLogInstance' => $this->logger
			);
		System_Daemon::setOptions($options);

		if ($this->daemon) {
			System_Daemon::start();
		}

		try {
			while (true) {
				$subChain = new FilterChain();
				$subChain->chain = $this->chain;
				$subChain->execute();
				System_Daemon::iterate($this->sleepTime);
			}
			System_Daemon::stop();
		} catch (Exception $e) {
			System_Daemon::stop();
			throw $e;
		}
	}

}
