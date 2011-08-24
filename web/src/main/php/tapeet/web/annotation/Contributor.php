<?php


require_once 'addendum/annotations.php';

use tapeet\web\ioc\ClassDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_Contributor extends Annotation implements ClassDecorator {


	function afterConstruct($object, $type, $chain) {
		$serviceName = $this->value;
		if (empty($serviceName)) {
			$serviceName = strtolower(substr($type, 0, 1)) . substr($type, 1);
		}

		$serviceLocator = ServiceLocator::getServiceLocator();
		$serviceLocator->addService($serviceName, $object->contribute());

		$chain->afterConstruct($object, $type);
	}


	function onConstruct($type, $chain) {
		return $chain->onConstruct($type);
	}

}
?>