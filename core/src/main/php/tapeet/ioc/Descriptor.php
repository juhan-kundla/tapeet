<?php
namespace tapeet\ioc;


use \ReflectionClass;

use \tapeet\util\LazyObject;


class Descriptor {


	public $class;
	public $constructorArgs;
	public $context;
	public $factory;
	public $factoryMethod;
	public $name;
	public $properties;


	function create() {
		$args = NULL;
		if ($this->constructorArgs !== NULL) {
			$args = $this->resolve($this->constructorArgs);
		} else {
			$args = array();
		}

		$factory = NULL;
		if ($this->factory !== NULL) {
			$factory = $this->context->get($this->factory);
		}

		$object = NULL;

		if ($factory !== NULL) {
			$object = call_user_method_array($this->factoryMethod, $factory, $args);
		} else {
			$class = new ReflectionClass($this->class);
			if ($this->factoryMethod !== NULL) {
				$factoryMethod = $class->getMethod($this->factoryMethod);
				$object = $factoryMethod->invokeArgs(NULL, $args);
			} else {
				if (empty($args)) {
					$object = $class->newInstance();
				} else {
					$object = $class->newInstanceArgs($args);
				}
			}
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
