<?php
namespace tapeet\annotation;


class ClassLoader implements PropertyAnnotation {


	/** @Context */
	private $context;

	function onGet($object, $property, PropertyAnnotationChain $chain) {
		return $this->context->get('_tapeet_core_classLoader');
	}

}
