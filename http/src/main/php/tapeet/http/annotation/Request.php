<?php
namespace tapeet\http\annotation;


use \tapeet\annotation\MethodAnnotation;
use \tapeet\annotation\MethodAnnotationChain;
use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;


class Request implements MethodAnnotation, PropertyAnnotation {


	function onCall($object, $method, $args, MethodAnnotationChain $chain) {
		$value = $chain->onCall($object, $method, $args);
		if ($value !== NULL) {
			return $value;
		}

		return new \tapeet\http\Request();
	}


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$value = $chain->onGet($object, $property);
		if ($value !== NULL) {
			return $value;
		}

		return new \tapeet\http\Request();
	}

}
