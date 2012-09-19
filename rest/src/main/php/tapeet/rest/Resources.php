<?php
namespace tapeet\rest;


use \RuntimeException;


class Resources {


	private $methods = array();


	function addMethod(Method $method) {
		if ($this->existsMethod($method->getMethod())) {
			throw new RuntimeException("The method already exists: {$method->getMethod()}");
		}

		$this->methods[$method->getMethod()] = $method;
	}


	function existsMethod($method) {
		return array_key_exists($method, $this->methods);
	}


	public function getMethod($method) {
		if ($this->existsMethod($method)) {
			return $this->methods[$method];
		}

		return NULL;
	}

}
