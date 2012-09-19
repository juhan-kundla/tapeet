<?php
namespace tapeet\rest;


class Method {


	private $method;
	private $paths = array();


	function __construct($method) {
		$this->method = $method;
	}


	function addPath(Path $path) {
		if ($this->existsPath($path->getPath())) {
			throw new RuntimeException("The path already exists: {$this->getMethod()} {$path->getPath()}");
		}

		$this->paths[$path->getPath()] = $path;
	}


	function existsPath($path) {
		return array_key_exists($path, $this->paths);
	}


	public function getMethod() {
		return $this->method;
	}


	function getPath($path) {
		if ($this->existsPath($path)) {
			return $this->paths[$path];
		}

		return NULL;
	}

}
