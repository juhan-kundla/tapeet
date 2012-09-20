<?php
namespace tapeet\rest;


use \RuntimeException;

use \tapeet\http\annotation\Request;
use \tapeet\http\annotation\Response;
use \tapeet\http\response\MethodNotAllowedStatus;


class Resources {


	private $methods = array();
	/** @Request */
	private $request;
	/** @Response */
	private $response;


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


	function onRequest() {
		$method = $this->getMethod($this->request->getMethod());

		if ($method === NULL) {
			$this->response->setStatus(new MethodNotAllowedStatus());
			return;
		}

		$method->onRequest();
	}

}
