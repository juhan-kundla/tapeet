<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\PropertyDecorator;
use tapeet\web\security\Group;


class Annotation_Group extends Annotation implements PropertyDecorator {


	public $id;


	function onInit($object, $property, $chain) {
		$id = null;
		if (isset($this->value)) {
			$id = $this->value;
		} else if (isset($this->id)) {
			$id = $this->id;
		} else {
			$id = $property;
		}

		if (! isset($object->$property)) {
			$object->$property = new Group($id);
		}

		$chain->onInit($object, $property);
	}

}
?>