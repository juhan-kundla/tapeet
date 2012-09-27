<?php
namespace tapeet\util;


use \ArrayAccess;
use \ArrayIterator;
use \Countable;
use \IteratorAggregate;


class LazyCollection implements ArrayAccess, Countable, IteratorAggregate {


	private $loader;
	private $objects;


	function __construct($loader) {
		$this->loader = $loader;
	}


	function count() {
		return count($this->getObjects());
	}


	function getIterator() {
		$objects = $this->getObjects();
		if (is_array($objects)) {
			return new ArrayIterator($objects);
		} else {
			return $objects->getIterator();
		}
	}


	function & getObjects() {
		if ($this->objects === NULL) {
			$loader = $this->loader;
			$this->objects = $loader();
		}
		return $this->objects;
	}


	public function offsetExists($offset) {
		$this->getObjects();
		if (! is_array($this->objects)) {
			return $this->objects->offsetExists($offset);
		}

		return isset($this->objects[$offset]);
	}


	public function offsetGet($offset) {
		$this->getObjects();
		if (! is_array($this->objects)) {
			return $this->objects->offsetGet($offset);
		}

		return isset($this->objects[$offset]) ? $this->objects[$offset] : null;
	}


	public function offsetSet($offset, $value) {
		$this->getObjects();
		if (! is_array($this->objects)) {
			return $this->objects->offsetSet($offset, $value);
		}

		if ($offset === NULL) {
			$this->objects[] = $value;
		} else {
			$this->objects[$offset] = $value;
		}
	}


	public function offsetUnset($offset) {
		$this->getObjects();
		if (! is_array($this->objects)) {
			return $this->objects->offsetUnset($offset);
		}

		unset($this->objects[$offset]);
	}

}
