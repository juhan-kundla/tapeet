<?php
namespace tapeet\rest;


use \RuntimeException;

use \tapeet\http\annotation\Request;
use \tapeet\http\annotation\Response;
use \tapeet\http\response\NotFoundStatus;


class Method {


	private $method;
	private $paths = array();
	/** @Request */
	private $request;
	/** @Response */
	private $response;


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


	function getMethod() {
		return $this->method;
	}


	function getPath($path) {
		if ($this->existsPath($path)) {
			return $this->paths[$path];
		}

		return NULL;
	}


	function onRequest() {
		$path = $this->getPath($this->request->getPathInfo());

		if ($path === NULL) {
			$this->response->setStatus(new NotFoundStatus());
			return;
		}

		$version = $path->getVersion();
		$class = $version->getClass();
		$resource = new $class;

		$result = $resource->onPost();
		if ($result !== NULL) {
			$this->response->setContentType('application/json');
			$this->response->write(json_encode($result));
		}
	}

}
