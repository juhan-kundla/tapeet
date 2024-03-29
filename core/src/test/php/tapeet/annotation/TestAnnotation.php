<?php
namespace tapeet\annotation;


use \tapeet\annotation\MethodAnnotation;
use \tapeet\annotation\MethodAnnotationChain;
use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;


class TestAnnotation implements MethodAnnotation, PropertyAnnotation {


	function onCall($object, $method, $args, MethodAnnotationChain $chain) {
		return 'Annotated method: ' . $chain->onCall($object, $method, $args);
	}


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$value = $chain->onGet($object, $property);
		if ($value !== null) {
			return 'Annotation: ' . $value;
		}
		return 'Annotation';
	}

}
