<?php
namespace tapeet\ioc;


class PropertyDecoratorChain {


	private $decorators;


	function __construct($decorators) {
		$this->decorators = $decorators;
	}


	public function onInit($object, $property) {
		$decorator = array_shift($this->decorators);
		if ($decorator != null) {
			$decorator->onInit($object, $property, $this);
		}
	}

}
