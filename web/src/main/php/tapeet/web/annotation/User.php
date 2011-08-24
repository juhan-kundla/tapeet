<?php


require_once 'addendum/annotations.php';

use tapeet\web\ioc\ClassDecorator;
use tapeet\web\ioc\PropertyDecorator;
use tapeet\web\ioc\ServiceLocator;


class Annotation_User extends Annotation implements PropertyDecorator {


	function onInit($object, $property, $chain) {
		if (! isset($object->$property)) {
			$serviceLocator = ServiceLocator::getServiceLocator();
			$request = $serviceLocator->getService('request');
			$object->$property = $request->remoteUser;
		}
		$chain->onInit($object, $property);
	}

}
?>