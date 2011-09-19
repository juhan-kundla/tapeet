<?php
namespace tapeet\annotation;


require_once 'addendum/annotations.php';


use \ReflectionAnnotatedClass;
use \ReflectionProperty;

use \tapeet\ClassLoader;
use \tapeet\ClassLoaderListener;


class AnnotationProcessor implements ClassLoaderListener {


	private static $INSTANCE = null;


	private $methodAnnotations;
	private $propertyAnnotations;


	function __construct() {
		$this->methodAnnotations = array();
		$this->propertyAnnotations = array();
	}


	static function get() {
		return self::$INSTANCE;
	}


	function getMethodAnnotations($class, $method) {
		return $this->methodAnnotations[$class][$method];
	}


	function getPropertyAnnotations($class, $property) {
		return $this->propertyAnnotations[$class][$property];
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

		$methodAnnotationsFound = false;
		foreach ($class->getMethods() as $method) {
			$annotations = $method->getAnnotations();
			if (! $annotations) {
				continue;
			}
			$methodAnnotationsFound = true;
			$this->setMethodAnnotations($className, $method->getName(), $annotations);
			runkit_method_rename($className, $method->getName(), '___' . $method->getName());
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

		$propertyAnnotationsFound = false;
		$defaultProperties = $class->getDefaultProperties();
		foreach ($class->getProperties() as $property) {
			$annotations = $property->getAnnotations();
			if (! $annotations) {
				continue;
			}
			$propertyAnnotationsFound = true;
			$this->setPropertyAnnotations($className, $property->getName(), $annotations);
			runkit_default_property_add($className, '___' . $property->getName(), $defaultProperties[$property->getName()]);
			runkit_default_property_remove($className, $property->getName());
		}
		if ($propertyAnnotationsFound) {
			runkit_method_add(
					$className
					, '__get'
					, '$property'
					, '
							$annotationProcessor = \tapeet\annotation\AnnotationProcessor::get();
							$chain = new \tapeet\annotation\PropertyAnnotationChain(
									$annotationProcessor->getPropertyAnnotations("'.$className.'", $property)
								);
							return $chain->onGet($this, $property);
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


	function setPropertyAnnotations($class, $property, $annotations) {
		if (! array_key_exists($class, $this->propertyAnnotations)) {
			$this->propertyAnnotations[$class] = array();
		}
		$this->propertyAnnotations[$class][$property] = $annotations;
	}

}
