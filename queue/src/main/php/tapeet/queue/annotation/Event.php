<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\PropertyDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_Event extends Annotation implements PropertyDecorator {


	public $name;


	function onInit($object, $property, $chain) {
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
?>