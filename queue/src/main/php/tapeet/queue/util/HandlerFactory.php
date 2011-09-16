<?php
namespace tapeet\queue\util;


use tapeet\ioc\IOCProxy;


class HandlerFactory {


	public $applicationPackage;
	public $logger;


	function getHandler($type) {
		$class = $this->applicationPackage . '\\handler\\' . $type;
		if (class_exists($class)) {
			return new IOCProxy($class);
		}
		return null;
	}

}
