<?php
namespace tapeet\queue\util;


use tapeet\web\Filter;

require_once 'System/Daemon.php';


class DaemonFilter implements Filter {


	/** @Configuration('application') */
	public $application;
	/** @Configuration('application_description') */
	public $applicationDescription;
	/** @Configuration('daemon_dir') */
	public $directory;
	/** @Configuration('daemon') */
	public $daemon;
	/** @Configuration('daemon_gid') */
	public $gid;
	/** @Service */
	public $logger;
	/** @Configuration('daemon_pid_file') */
	public $pidFile;
	/** @Configuration('daemon_uid') */
	public $uid;


	function doFilter($chain) {
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
			$chain->doFilter();
			System_Daemon::stop();
		} catch (Exception $e) {
			System_Daemon::stop();
			throw $e;
		}
	}

}
?>