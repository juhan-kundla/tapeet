<?php
namespace tapeet\web\annotation;


use tapeet\web\ioc\ServiceLocator;


class ServiceProxy {


	public $service;
	public $serviceName;


	function __construct($serviceName) {
		$this->serviceName = $serviceName;
	}


	function getService() {
		if ($this->service == null) {
			$serviceLocator = ServiceLocator::getServiceLocator();
			$this->service = $serviceLocator->getService($this->serviceName);
		}
		return $this->service;
	}


	public function __call($method, $args) {
		return call_user_func_array(array($this->getService(), $method), $args);
	}


	public function & __get($property) {
		$object = $this->getService();
		return $object->$property;
	}


	public function __set($property, $value) {
		$object = $this->getService();
		$object->$property = $value;
	}


	function __toString() {
		$object = $this->getService();
		return $object->__toString();
	}

}
?>