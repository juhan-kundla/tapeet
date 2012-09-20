<?php
namespace tapeet\annotation;


use \ReflectionClass;
use \tapeet\ClassLoaderListener;


class AnnotationProcessor implements ClassLoaderListener {


	function onLoad($className) {
		$class = new ReflectionClass($className);
		$annotations = ReflectionUtils::getAnnotations($class);

		$chain = new ClassAnnotationChain($annotations->getClassAnnotations());
		$chain->onLoad($class);

		if ($annotations->getMethodAnnotations()) {
			runkit_method_add(
					$className
					, '__call'
					, '$method, $args'
					, '
							$class = new \ReflectionClass(\''.$className.'\');
							$annotations = \tapeet\annotation\ReflectionUtils::getAnnotations($class);
							$chain = new \tapeet\annotation\MethodAnnotationChain(
									$annotations->getMethodAnnotations($method)
								);
							return $chain->onCall($this, $method, $args);
						'
				);
		}
		foreach ($annotations->getMethodAnnotations() as $methodName => $methodAnnotations) {
			runkit_method_rename($className, $methodName, '___' . $methodName);
		}

		if ($annotations->getPropertyAnnotations()) {
			runkit_method_add(
					$className
					, '__get'
					, '$property'
					, '
							$class = new \ReflectionClass(\''.$className.'\');
							$annotations = \tapeet\annotation\ReflectionUtils::getAnnotations($class);
							$chain = new \tapeet\annotation\PropertyAnnotationChain(
									$annotations->getPropertyAnnotations($property)
								);
							return $chain->onGet($this, $property);
						'
				);
		}
		$defaultProperties = $class->getDefaultProperties();
		foreach ($annotations->getPropertyAnnotations() as $propertyName => $propertyAnnotations) {
			runkit_default_property_add($className, '___' . $propertyName, $defaultProperties[$propertyName]);
			runkit_default_property_remove($className, $propertyName);
		}
	}

}
