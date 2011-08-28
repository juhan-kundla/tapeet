<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\PropertyDecorator;
use tapeet\web\Parameter;


class Annotation_Parameter extends Annotation implements PropertyDecorator {


	function onInit($object, $property, $chain) {
		$parameterName = null;
		if (isset($this->value)) {
			$parameterName = $this->value;
		} else {
			$parameterName = $property;
		}
		$parameter = new Parameter($parameterName);

		if (! isset($object->_parameters)) {
			$object->_parameters = array();
		}
		array_push($object->_parameters, $parameter);

		$object->$property = $parameter;
		$chain->onInit($object, $property);
	}

}
?>