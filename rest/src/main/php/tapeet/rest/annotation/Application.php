<?php
namespace tapeet\rest\annotation;


use \tapeet\annotation\Context;
use \tapeet\annotation\MethodAnnotation;
use \tapeet\annotation\MethodAnnotationChain;
use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;
use \tapeet\ioc\ContextUtils;


class Application implements MethodAnnotation, PropertyAnnotation {


	const CONTEXT_KEY = '_tapeet_rest_application';


	/** @Context */
	private $context;


	function getApplication() {
		$application = $this->context->get(self::CONTEXT_KEY);
		if ($application === NULL) {
			$application = new \tapeet\rest\Application();
			$this->context->add(self::CONTEXT_KEY, $application);
		}
		return $application;
	}


	function onCall($object, $method, $args, MethodAnnotationChain $chain) {
		$value = $chain->onCall($object, $method, $args);
		if ($value !== NULL) {
			return $value;
		}

		return $this->getApplication();
	}


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$value = $chain->onGet($object, $property);
		if ($value !== NULL) {
			return $value;
		}

		return $this->getApplication();
	}

}
