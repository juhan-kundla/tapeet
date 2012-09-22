<?php
namespace tapeet\rest;


class Version {


	private $class;
	private $version;


	function __construct($version, $class) {
		$this->class = $class;
		$this->version = explode('.', $version);
	}


	function compareTo(Version $other) {
		$length = count($this->version) < count($other->version) ? count($this->version) : count($other->version);
		for ($i = 0; $i < $length; $i++) {
			if ($this->version[$i] != $other->version[$i]) {
				return $this->version[$i] - $other->version[$i];
			}
		}
		return count($this->version) - count($other->version);
	}


	function getClass() {
		return $this->class;
	}


	function getVersion() {
		return implode('.', $this->version);
	}

}
