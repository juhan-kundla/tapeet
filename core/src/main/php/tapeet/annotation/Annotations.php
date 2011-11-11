<?php
namespace tapeet\annotation;


use \ReflectionClass;


class Annotations {


	private $class;
	private $classAnnotations;
	private $methodAnnotations = array();
	private $propertyAnnotations = array();
	

	function __construct($class) {
		$this->class = $class;
	}


	function getClassAnnotations() {
		return $this->classAnnotations;
	}


	function getMethodAnnotations($methodName = NULL) {
		if ($methodName === NULL) {
			return $this->methodAnnotations;
		} else {
			return $this->methodAnnotations[$methodName];
		}
	}


	function getPropertyAnnotations($propertyName = NULL) {
		if ($propertyName === NULL) {
			return $this->propertyAnnotations;
		} else {
			return $this->propertyAnnotations[$propertyName];
		}
	}


	function setClassAnnotations(array $classAnnotations) {
		$this->classAnnotations = $classAnnotations;
	}


	function setMethodAnnotations($methodName, array $methodAnnotations) {
		$this->methodAnnotations[$methodName] = $methodAnnotations;
	}


	function setPropertyAnnotations($propertyName, array $propertyAnnotations) {
		$this->propertyAnnotations[$propertyName] = $propertyAnnotations;
	}

}
