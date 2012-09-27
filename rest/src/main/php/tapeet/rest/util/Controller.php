<?php
namespace tapeet\rest\util;


use \Exception;

use \tapeet\Filter;
use \tapeet\FilterChain;
use \tapeet\http\response\BadRequestStatus;
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
		$resource = $class->newInstance();

		$method = $class->getMethod('onCall');

		$args = array();
		foreach ($method->getParameters() as $parameter) {
			$arg = $this->request->getParameter($parameter->getName());
			if ($arg === NULL && $parameter->isDefaultValueAvailable()) {
				$arg = $parameter->getDefaultValue();
			}
			if ($arg === NULL && ! $parameter->isOptional()) {
				$this->response->setStatus(new BadRequestStatus());
				$this->response->write("Parameter `{$parameter->getName()}' is required");
				return $chain->execute();
			}
			if ($parameter->getClass() !== NULL) {
				// TODO: pluggable type coercion framework
				switch ($parameter->getClass()->getName()) {
					case 'DateTime':
						try {
							$arg = $parameter->getClass()->newInstance($arg);
						} catch (Exception $e) {
							$this->response->setStatus(new BadRequestStatus());
							$this->response->write("Parameter `{$parameter->getName()}' is not a properly formatted date-time: $arg");
							return $chain->execute();
						}
						break;
					default:
						throw new Exception("Could not find type coercion for parameter `{$parameter->getName()}'");
				}
			}
			$args[] = $arg;
		}

		$result = $method->invokeArgs($resource, $args);

		if ($result !== NULL) {
			$this->response->setContentType('application/json');
			$this->response->write(json_encode($result));
		}

		return $chain->execute();
	}

}
