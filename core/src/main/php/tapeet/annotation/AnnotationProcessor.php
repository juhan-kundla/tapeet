<?php
namespace tapeet\annotation;


require_once 'addendum/annotations.php';


use \ReflectionAnnotatedClass;

use \tapeet\ClassLoader;
use \tapeet\ClassLoaderListener;


class AnnotationProcessor implements ClassLoaderListener {


	private static $INSTANCE = null;


	private $methodAnnotations;


	function __construct() {
		$this->methodAnnotations = array();
	}


	static function get() {
		return self::$INSTANCE;
	}


	function getMethodAnnotations($class, $method) {
		return $this->methodAnnotations[$class][$method];
	}


	static function init() {
		if (self::$INSTANCE !== null) {
			return;
		}
		$annotationProcessor = new AnnotationProcessor();
		self::$INSTANCE = $annotationProcessor;

		$classLoader = ClassLoader::get();
		$classLoader->addListener($annotationProcessor);
	}


	function onLoad($className) {
		$class = new ReflectionAnnotatedClass($className);
		$chain = new ClassAnnotationChain($class->getAllAnnotations());
		$chain->onLoad($class);

		$methodAnnotationsFound = FALSE;
		foreach ($class->getMethods() as $method) {
			$annotations = $method->getAnnotations();
			if (! $annotations) {
				continue;
			}
			$this->setMethodAnnotations($className, $method->getName(), $annotations);
			runkit_method_rename($className, $method->getName(), '___' . $method->getName());
			$methodAnnotationsFound = true;
		}
		if ($methodAnnotationsFound) {
			runkit_method_add(
					$className
					, '__call'
					, '$method, $args'
					, '
							$annotationProcessor = \tapeet\annotation\AnnotationProcessor::get();
							$chain = new \tapeet\annotation\MethodAnnotationChain(
									$annotationProcessor->getMethodAnnotations("'.$className.'", $method)
								);
							return $chain->onCall($this, $method, $args);
						'
				);
		}
	}


	function setMethodAnnotations($class, $method, $annotations) {
		if (! array_key_exists($class, $this->methodAnnotations)) {
			$this->methodAnnotations[$class] = array();
		}
		$this->methodAnnotations[$class][$method] = $annotations;
	}

}
