<?php
namespace tapeet\ioc;


class Context {


	private $descriptors;
	private $objects;


	function __construct() {
		$this->descriptors = array();
		$this->objects = array();
	}


	function add($name, $object) {
		$this->objects[$name] = $object;
	}


	function addDescriptor(Descriptor $descriptor) {
		$this->descriptors[$descriptor->name] = $descriptor;
		$descriptor->context = $this;
	}


	function get($name) {
		if ($this->isAvailable($name)) {
			return $this->objects[$name];
		}

		if (array_key_exists($name, $this->descriptors)) {
			$descriptor = $this->descriptors[$name];
			$object = $descriptor->create();
			$this->add($name, $object);
			return $object;
		}

		return NULL;
	}


	function isAvailable($name) {
		return array_key_exists($name, $this->objects);
	}

}
