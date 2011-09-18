<?php
namespace tapeet\annotation;


class MethodAnnotationChain {


	private $annotations;


	function __construct($annotations) {
		$this->annotations = $annotations;
	}


	public function onCall($object, $method, $args) {
		$annotation = array_shift($this->annotations);
		if ($annotation !== null) {
			return $annotation->onCall($object, $method, $args, $this);
		} else {
			return call_user_func_array(array($object, '___' . $method), $args);
		}
	}

}
