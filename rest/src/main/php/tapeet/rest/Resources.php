<?php
namespace tapeet\rest;


use \RuntimeException;

use \tapeet\http\Response;
use \tapeet\http\Request;
use \tapeet\http\response\MethodNotAllowedStatus;


class Resources {


	private $methods = array();


	function addMethod(Method $method) {
		if ($this->existsMethod($method->getMethod())) {
			throw new RuntimeException("The method already exists: {$method->getMethod()}");
		}

		$this->methods[$method->getMethod()] = $method;
	}


	function existsMethod($method) {
		return array_key_exists($method, $this->methods);
	}


	function getMethod($method) {
		if ($this->existsMethod($method)) {
			return $this->methods[$method];
		}

		return NULL;
	}


	function onRequest(Request $request, Response $response) {
		$method = $this->getMethod($request->getMethod());

		if ($method === NULL) {
			$response->setStatus(new MethodNotAllowedStatus());
			return;
		}

		$method->onRequest($request, $response);
	}

}
