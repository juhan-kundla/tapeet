<?php
namespace tapeet\queue\util;


use tapeet\web\ioc\IOCProxy;


class HandlerFactory {


	/** @Configuration('application_package') */
	public $applicationPackage;
	/** @Service */
	public $logger;


	function getHandler($type) {
		$class = $this->applicationPackage . '\\handler\\' . $type;
		if (class_exists($class)) {
			return new IOCProxy($class);
		}
		return null;
	}

}
?>