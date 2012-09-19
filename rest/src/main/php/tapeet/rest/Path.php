<?php
namespace tapeet\rest;


class Path {


	private $methods = array();
	private $name;


	function __construct($name) {
		$this->name = $name;
	}


	function addMethod(Method $method) {
		if ($this->existsMethod($method->getName())) {
			throw new RuntimeException("The method already exists: {$method->getName()} {$this->getName()}");
		}

		$this->methods[$method->getName()] = $method;
	}


	function existsMethod($methodName) {
		return array_key_exists($methodName, $this->methods);
	}


	public function getMethod($methodName) {
		if ($this->existsMethod($methodName)) {
			return $this->methods[$methodName];
		}

		return NULL;
	}


	public function getName() {
		return $this->name;
	}

}
