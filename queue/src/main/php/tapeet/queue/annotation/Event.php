<?php
namespace tapeet\queue\annotation;


use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;
use \tapeet\ioc\ServiceLocator;


class Event implements PropertyAnnotation {


	public $name;


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$name = null;
		if (isset($this->value)) {
			$name = $this->value;
		} else if (isset($this->name)) {
			$name = $this->name;
		} else {
			$name = $property;
		}

		$serviceLocator = ServiceLocator::getServiceLocator();
		$event = $serviceLocator->getService('event');
		$object->$property = $event->get($name);

		$chain->onInit($object, $property);
	}

}
