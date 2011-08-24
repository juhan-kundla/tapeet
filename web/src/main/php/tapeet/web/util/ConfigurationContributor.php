<?php
namespace tapeet\web\util;


/** @Contributor('configuration') */
class ConfigurationContributor {


	function contribute() {
		$application = $_SERVER['MVC_APPLICATION'];

		$configuration = array_merge(
				parse_ini_file($application . '.default.ini'),
				parse_ini_file($application . '.ini')
			);
		$configuration['application'] = $application;

		return $configuration;
	}

}
?>