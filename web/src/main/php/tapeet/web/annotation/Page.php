<?php
namespace tapeet\web\annotation;


use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;
use \tapeet\ioc\ServiceLocator;


class Page implements PropertyAnnotation {


	public $path;


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$path = null;
		if (isset($this->value)) {
			$path = $this->value;
		} else if (isset($this->path)) {
			$path = $this->path;
		} else {
			$path = ucfirst($property);
		}

		$serviceLocator = ServiceLocator::getServiceLocator();
		$componentFactory = $serviceLocator->getService('componentFactory');
		$object->$property = $componentFactory->getPage($path);

		$chain->onInit($object, $property);
	}

}
