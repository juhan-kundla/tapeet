<?php
namespace tapeet\rest;


use \tapeet\http\Response;
use \tapeet\http\Request;
use \tapeet\http\response\NotFoundStatus;


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


	function getMethod() {
		return $this->method;
	}


	function getPath($path) {
		if ($this->existsPath($path)) {
			return $this->paths[$path];
		}

		return NULL;
	}


	function onRequest(Request $request, Response $response) {
		$path = $this->getPath($request->getPathInfo());

		if ($path === NULL) {
			$response->setStatus(new NotFoundStatus());
			return;
		}

		$version = $path->getVersion();
		$class = $version->getClass();
		$resource = new $class;

		$result = $resource->onPost();
		if ($result !== NULL) {
			$response->setContentType('application/json');
			$response->write(json_encode($result));
		}
	}

}
