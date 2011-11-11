<?php
namespace tapeet\web\annotation;


use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;


class Parameter implements PropertyAnnotation {


	function onGet($object, $property, PropertyAnnotationChain $chain) {
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
