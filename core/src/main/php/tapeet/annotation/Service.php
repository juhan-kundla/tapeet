<?php
namespace tapeet\annotation;


class Service implements PropertyAnnotation {


	/** @Context */
	private $context;
	public $value;


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$value = $chain->onGet($object, $property);
		if ($value !== NULL) {
			return $value;
		}

		$key = $this->value;
		if ($key === NULL) {
			$key = $property;
		}

		return $this->context->get($key);
	}

}
