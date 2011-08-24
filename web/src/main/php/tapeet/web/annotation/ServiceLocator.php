<?php


require_once 'addendum/annotations.php';

use tapeet\web\ioc\PropertyDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_ServiceLocator extends Annotation implements PropertyDecorator {


	function onInit($object, $property, $chain) {
		$object->$property = ServiceLocator::getServiceLocator();
		$chain->onInit($object, $property);
	}

}
?>