<?php
namespace tapeet\annotation;


use \ReflectionClass;


class ClassAnnotationChain {


	private $annotations;


	function __construct($annotations) {
		$this->annotations = $annotations;
	}


	public function onLoad(ReflectionClass $class) {
		$annotation = array_shift($this->annotations);
		if ($annotation !== null) {
			$annotation->onLoad($class, $this);
		}
	}

}
