<?php
namespace tapeet\ioc;


require_once 'addendum/annotations.php';


use ReflectionAnnotatedClass;


class IOCProxy {


	private $methodDecorators;
	public $object;
	private $reflection;


	public function __construct($type) {
		$this->methodDecorators = array();
		$this->reflection = new ReflectionAnnotatedClass($type);

		$classDecorators = $this->reflection->getAllAnnotations();
		$chain = new ClassDecoratorChain($classDecorators);
		$this->object = $chain->onConstruct($type);

		foreach ($this->reflection->getProperties() as $reflectionProperty) {
			$chain = new PropertyDecoratorChain($reflectionProperty->getAnnotations());
			$chain->onInit($this->object, $reflectionProperty->getName());
		}

		$chain = new ClassDecoratorChain($classDecorators);
		$chain->afterConstruct($this->object, $type);
	}


	public function __call($method, $args) {
		$chain = new MethodDecoratorChain($this->getMethodDecorators($method));
		return $chain->onInvoke($this->object, $method, $args);
	}


	public function & __get($property) {
		return $this->object->$property;
	}


	public function __set($property, $value) {
		$this->object->$property = $value;
	}


	function __toString() {
		return $this->object->__toString();
	}


	function getMethodDecorators($method) {
		if (! isset($this->methodDecorators[$method])) {
			$reflectionMethod = $this->reflection->getMethod($method);
			$this->methodDecorators[$method] = $reflectionMethod->getAnnotations();
		}
		return $this->methodDecorators[$method];
	}

}
