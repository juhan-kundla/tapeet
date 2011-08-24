<?php

require_once 'addendum/annotations.php';

use tapeet\web\ioc\PropertyDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_Component extends Annotation implements PropertyDecorator {


	public $name;
	public $type;


	function onInit($object, $property, $chain) {
		$type = null;
		if (isset($this->value)) {
			$type = $this->value;
		} else if (isset($this->type)) {
			$type = $this->type;
		} else {
			$type = ucfirst($property);
		}

		$name = null;
		if (isset($this->name)) {
			$name = $this->name;
		} else {
			$name = $type;
		}

		if (! isset($object->_components)) {
			$object->_components = array();
		}

		$component = null;
		if (array_key_exists($name, $object->_components)) {
			$component = $object->_components[$name];
		} else {
			$serviceLocator = ServiceLocator::getServiceLocator();
			$componentFactory = $serviceLocator->getService('componentFactory');
			$component = $componentFactory->getComponent($type);
			$object->_components[$name] = $component;
			$component->_name = $name;
			$component->_parent = $object;
		}

		$object->$property = $component;

		$chain->onInit($object, $property);
	}

}
?>