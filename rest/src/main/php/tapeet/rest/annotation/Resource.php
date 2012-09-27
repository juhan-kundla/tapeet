<?php
namespace tapeet\rest\annotation;


use \ReflectionClass;
use \RuntimeException;

use \tapeet\annotation\ClassAnnotation;
use \tapeet\annotation\ClassAnnotationChain;
use \tapeet\annotation\Service;
use \tapeet\rest\Method;
use \tapeet\rest\Path;
use \tapeet\rest\Version;


class Resource implements ClassAnnotation {


	public $method;
	public $path;
	/** @Service('_tapeet_rest_resources') */
	public $resources;
	public $version;


	function onLoad(ReflectionClass $class, ClassAnnotationChain $chain) {
		$method = $this->resources->getMethod($this->method);
		if ($method === NULL) {
			$method = new Method($this->method);
			$this->resources->addMethod($method);
		}

		$path = $method->getPath($this->path);
		if ($path === NULL) {
			$path = new Path($this->path);
			$method->addPath($path);
		}

		$path->addVersion(new Version($this->version, $class));

		return $chain->onLoad($class);
	}

}
