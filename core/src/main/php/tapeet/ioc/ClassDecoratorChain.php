<?php
namespace tapeet\ioc;


class ClassDecoratorChain {


	private $decorators;


	function __construct($decorators) {
		$this->decorators = $decorators;
	}


	public function afterConstruct($object, $type) {
		$decorator = array_shift($this->decorators);
		if ($decorator != null) {
			return $decorator->afterConstruct($object, $type, $this);
		}
	}


	public function onConstruct($type) {
		$decorator = array_shift($this->decorators);
		if ($decorator != null) {
			return $decorator->onConstruct($type, $this);
		} else {
			return new $type;
		}
	}


}
