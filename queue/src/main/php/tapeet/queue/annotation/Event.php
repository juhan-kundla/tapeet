<?php
namespace tapeet\queue\annotation;


use \tapeet\annotation\Context;
use \tapeet\annotation\PropertyAnnotation;
use \tapeet\annotation\PropertyAnnotationChain;


class Event implements PropertyAnnotation {


	/** @Context */
	public $context;
	public $name;


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		$event = $this->context->get('event');

		$name = null;
		if (isset($this->value)) {
			$name = $this->value;
		} else if (isset($this->name)) {
			$name = $this->name;
		} else {
			$name = $property;
		}

		return $event->get($name);
	}

}
