<?php
namespace tapeet\annotation;


class PropertyAnnotationChain {


	private $annotations;


	function __construct($annotations) {
		$this->annotations = $annotations;
	}


	public function onGet($object, $property) {
		$annotation = array_shift($this->annotations);
		if ($annotation !== null) {
			return $annotation->onGet($object, $property, $this);
		}
		$property = '___' . $property;
		return $object->$property;
	}

}
