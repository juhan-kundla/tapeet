<?php
namespace tapeet\queue\util;


class HandlerFactory {


	public $applicationPackage;
	public $logger;


	function getHandler($type) {
		$class = $this->applicationPackage . '\\handler\\' . $type;
		if (class_exists($class)) {
			return new $class();
		}
		return null;
	}

}
