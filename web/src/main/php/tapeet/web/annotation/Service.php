<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\PropertyDecorator;
use tapeet\web\annotation\ServiceProxy;
use tapeet\web\ioc\ClassDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_Service extends Annotation implements ClassDecorator, PropertyDecorator {


	public $lazy;


	function afterConstruct($object, $type, $chain) {
		$serviceName = $this->value;
		if (empty($serviceName)) {
			$serviceName = strtolower(substr($type, 0, 1)) . substr($type, 1);
		}

		$serviceLocator = ServiceLocator::getServiceLocator();
		$serviceLocator->addService($serviceName, $object);

		$chain->afterConstruct($object, $type);
	}


	function onConstruct($type, $chain) {
		return $chain->onConstruct($type);
	}


	function onInit($object, $property, $chain) {
		if (! isset($object->$property)) {
			$serviceName = $this->value;
			if (empty($serviceName)) {
				$serviceName = $property;
			}

			if (isset($this->lazy)) {
				$object->$property = new ServiceProxy($serviceName);
			} else {
				$serviceLocator = ServiceLocator::getServiceLocator();
				$service = $serviceLocator->getService($serviceName);
				$object->$property = $service;
			}
		}
		$chain->onInit($object, $property);
	}

}
?>