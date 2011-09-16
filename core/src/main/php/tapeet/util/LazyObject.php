<?php
namespace tapeet\util;


class LazyObject {


	private $loader;
	private $object;


	function __construct($loader) {
		$this->loader = $loader;
	}


	private function getObject() {
		if ($this->object === null) {
			$loader = $this->loader;
			$this->object = $loader();
		}
		return $this->object;
	}


	public function __call($method, $args) {
		return call_user_func_array(array($this->getObject(), $method), $args);
	}


	public function & __get($property) {
		$object = $this->getObject();
		return $object->$property;
	}


	public function __set($property, $value) {
		$object = $this->getObject();
		$object->$property = $value;
	}


	public function __toString() {
		$object = $this->getObject();
		return $object->__toString();
	}

}
