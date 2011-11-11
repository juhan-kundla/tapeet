<?php
namespace tapeet\web\annotation;


use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;
use \tapeet\ioc\ServiceLocator;


class Component implements PropertyAnnotation {


	public $name;
	public $type;


	function onGet($object, $property, PropertyAnnotationChain $chain) {
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
