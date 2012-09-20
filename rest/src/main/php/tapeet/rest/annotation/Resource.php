<?php
namespace tapeet\rest\annotation;


use \ReflectionClass;
use \RuntimeException;

use \tapeet\annotation\ClassAnnotation;
use \tapeet\annotation\ClassAnnotationChain;
use \tapeet\rest\Method;
use \tapeet\rest\Path;
use \tapeet\rest\Version;


class Resource implements ClassAnnotation {


	/** @Application */
	private $application;
	public $method;
	public $path;
	public $version;


	function onLoad(ReflectionClass $class, ClassAnnotationChain $chain) {
		$method = $this->application->getMethod($this->method);
		if ($method === NULL) {
			$method = new Method($this->method);
			$this->application->addMethod($method);
		}

		$path = $method->getPath($this->path);
		if ($path === NULL) {
			$path = new Path($this->path);
			$method->addPath($path);
		}

		$path->addVersion(new Version($this->version, $class->getName()));

		return $chain->onLoad($class);
	}

}
