<?php
namespace tapeet\ioc;


class ServiceLocator {


	static $ATTR_SERVICE_LOCATOR = "serviceLocator";


	private $serviceClasses;
	private $services;


	function __construct() {
		$this->serviceClasses = array();
		$this->services = array();
	}


	function addService($name, $service) {
		$this->services[$name] = $service;
	}


	function addServiceClass($name, $serviceClass) {
		$this->serviceClasses[$name] = $serviceClass;
	}


	function getService($name) {
		if (array_key_exists($name, $this->services)) {
			return $this->services[$name];
		}

		if (array_key_exists($name, $this->serviceClasses)) {
			$service = new IOCProxy($this->serviceClasses[$name]);
			$this->addService($name, $service);
			return $service;
		}

		return null;
	}


	static function getServiceLocator() {
		if (! array_key_exists(self::$ATTR_SERVICE_LOCATOR, $_ENV)) {
			$_ENV[self::$ATTR_SERVICE_LOCATOR] = new ServiceLocator();
		}
		return $_ENV[self::$ATTR_SERVICE_LOCATOR];
	}

}
