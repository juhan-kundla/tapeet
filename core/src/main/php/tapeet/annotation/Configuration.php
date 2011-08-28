<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\PropertyDecorator;
use tapeet\ioc\ServiceLocator;


class Annotation_Configuration extends Annotation implements PropertyDecorator {


	function onInit($object, $property, $chain) {
		$confKey = null;
		if (isset($this->value)) {
			$confKey = $this->value;
		} else {
			$confKey = $property;
		}

		$configuration = ServiceLocator::getServiceLocator()->getService('configuration');
		$object->$property = $configuration[$confKey];

		$chain->onInit($object, $property);
	}

}
