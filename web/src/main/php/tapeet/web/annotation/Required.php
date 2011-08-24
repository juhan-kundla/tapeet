<?php


require_once 'addendum/annotations.php';

use tapeet\web\ioc\PropertyDecorator;
use tapeet\web\validator\RequiredValidator;


class Annotation_Required extends Annotation implements PropertyDecorator {


	function onInit($object, $property, $chain) {
		$validator = new RequiredValidator();
		if (isset($this->value)) {
			$validator->message = $this->value;
		}
		$object->$property->addValidator($validator);
		$chain->onInit($object, $property);
	}

}
?>