<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\PropertyDecorator;
use tapeet\ioc\ServiceLocator;


class Annotation_ServiceLocator extends Annotation implements PropertyDecorator {


	function onInit($object, $property, $chain) {
		$object->$property = ServiceLocator::getServiceLocator();
		$chain->onInit($object, $property);
	}

}