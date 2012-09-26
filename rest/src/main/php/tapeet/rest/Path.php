<?php
namespace tapeet\rest;


use \RuntimeException;


class Path {


	private $path;
	private $versions = array();


	function __construct($path) {
		$this->path = $path;
	}


	function addVersion(Version $version) {
		if ($this->existsVersion($version->getVersion())) {
			throw new RuntimeException("The version already exists: {$this->getPath()} {$version->getVersion()}");
		}

		$this->versions[$version->getVersion()] = $version;
	}


	function existsVersion($version) {
		return array_key_exists($version, $this->versions);
	}


	public function getPath() {
		return $this->path;
	}


	public function getVersion($version = NULL) {
		if ($version !== NULL) {
			return $this->versions[$version];
		}

		if (empty($this->versions)) {
			return NULL;
		}

		$versions = array_values($this->versions);
		uksort($versions, function (Version $a, Version $b) { return $a->compareTo($b); });
		return array_shift($versions);
	}

}
