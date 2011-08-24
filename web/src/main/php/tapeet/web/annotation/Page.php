<?php


require_once 'addendum/annotations.php';

use tapeet\web\ioc\PropertyDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_Page extends Annotation implements PropertyDecorator {


	public $path;


	function onInit($object, $property, $chain) {
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
?>