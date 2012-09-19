<?php
namespace tapeet\rest;


use \RuntimeException;


class Resources {


	private $paths = array();


	function addPath(Path $path) {
		if ($this->existsPath($path->getName())) {
			throw new RuntimeException("The path already exists: {$path->getName()}");
		}

		$this->paths[$path->getName()] = $path;
	}


	function existsPath($pathName) {
		return array_key_exists($pathName, $this->paths);
	}


	function getPath($pathName) {
		if ($this->existsPath($pathName)) {
			return $this->paths[$pathName];
		}

		return NULL;
	}

}
