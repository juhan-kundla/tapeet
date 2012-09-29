<?php
namespace tapeet\daemon;


use \tapeet\Filter;
use \tapeet\FilterChain;

require_once 'System/Daemon.php';


class DaemonFilter implements Filter {


	public $application;
	public $applicationDescription;
	public $directory;
	public $daemon;
	public $gid;
	public $logger;
	public $pidFile;
	public $uid;


	function execute(FilterChain $chain) {
		$options = array(
				 'appName' => $this->application
				,'appDir' => $this->directory
				,'appDescription' => $this->applicationDescription
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
			$chain->execute();
			System_Daemon::stop();
		} catch (Exception $e) {
			System_Daemon::stop();
			throw $e;
		}
	}

}
