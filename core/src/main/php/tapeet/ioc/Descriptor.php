<?php
namespace tapeet\ioc;


use \ReflectionClass;

use \tapeet\util\LazyObject;


class Descriptor {


	public $class;
	public $constructorArgs;
	public $context;
	public $name;
	public $properties;


	function create() {
		$class = new ReflectionClass($this->class);
		if ($this->constructorArgs !== null) {
			$object = $class->newInstanceArgs($this->resolve($this->constructorArgs));
		} else {
			$object = $class->newInstance();
		}

		if ($this->properties !== null) {
			foreach ($this->properties as $property => $value) {
				$object->$property = $this->resolve($value);
			}
		}

		return $object;
	}


	function resolve($value) {
		if ($value === null) {
			return null;
		}

		if (is_array($value)) {
			$result = array();
			foreach ($value as $subValue) {
				$result[] = $this->resolve($subValue);
			}
			return $result;
		}

		if (substr($value, 0, 1) != '$') {
			return $value;
		}

		$context = $this->context;
		$name = substr($value, 1);
		if ($context->isAvailable($name)) {
			return $context->get($name);
		}
		return new LazyObject(
				function () use ($context, $name) {
					return $context->get($name);
				}
			);
	}

}
