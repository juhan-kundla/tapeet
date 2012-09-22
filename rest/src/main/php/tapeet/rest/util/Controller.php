<?php
namespace tapeet\rest\util;


use \tapeet\Filter;
use \tapeet\FilterChain;
use \tapeet\http\response\MethodNotAllowedStatus;
use \tapeet\http\response\NotFoundStatus;


class Controller implements Filter {


	public $request;
	public $resources;
	public $response;


	function execute(FilterChain $chain) {
		$method = $this->resources->getMethod($this->request->getMethod());

		if ($method === NULL) {
			$this->response->setStatus(new MethodNotAllowedStatus());
			return $chain->execute();
		}

		$path = $method->getPath($this->request->getPathInfo());

		if ($path === NULL) {
			$this->response->setStatus(new NotFoundStatus());
			return $chain->execute();
		}

		$version = $path->getVersion();
		$class = $version->getClass();
		$resource = new $class;

		$result = $resource->onCall();
		if ($result !== NULL) {
			$this->response->setContentType('application/json');
			$this->response->write(json_encode($result));
		}

		return $chain->execute();
	}

}
