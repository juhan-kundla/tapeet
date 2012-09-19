<?php
namespace tapeet\rest;


class Method {


	private $name;
	private $versions = array();


	function __construct($name) {
		$this->name = $name;
	}


	function addVersion(Version $version) {
		if ($this->existsVersion($version->getVersion())) {
			throw new RuntimeException("The version already exists: {$this->getName()} {$version->getVersion()}");
		}

		$this->versions[$version->getVersion()] = $version;
		uksort($this->versions, function (Version $a, Version $b) { return $a->compareTo($b); });
	}


	function existsVersion($version) {
		return array_key_exists($version, $this->versions);
	}


	public function getVersion($version = NULL) {
		if ($version !== NULL && $this->existsVersion($version)) {
			return $this->versions[$version];
		}

		if ($version === NULL && count($this->versions) > 0) {
			return $this->versions[end(array_keys($this->versions))];
		}

		return NULL;
	}


	public function getName() {
		return $this->name;
	}

}
