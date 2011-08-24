<?php


require_once 'addendum/annotations.php';

use tapeet\web\ioc\PropertyDecorator;
use tapeet\web\validator\LengthValidator;


class Annotation_Length extends Annotation implements PropertyDecorator {


	public $max;
	public $min;
	public $msg;


	function onInit($object, $property, $chain) {
		$validator = new LengthValidator($this->min, $this->max);
		if (isset($this->msg)) {
			$validator->message = $this->msg;
		}
		$object->$property->addValidator($validator);
		$chain->onInit($object, $property);
	}

}
?>