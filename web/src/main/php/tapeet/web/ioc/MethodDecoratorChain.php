<?php
namespace tapeet\web\ioc;


class MethodDecoratorChain {


	private $decorators;


	function __construct($decorators) {
		$this->decorators = $decorators;
	}


	public function onInvoke($object, $method, $args) {
		$decorator = array_shift($this->decorators);
		if ($decorator != null) {
			return $decorator->onInvoke($object, $method, $args, $this);
		} else {
			return call_user_func_array(array($object, $method), $args);
		}
	}


}
?>