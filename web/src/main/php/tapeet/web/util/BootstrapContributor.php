<?php
namespace tapeet\web\util;


use tapeet\web\ioc\IOCProxy;


/** @Contributor('bootstrap') */
class BootstrapContributor {


	/** @Configuration('application_package') */
	public $applicationPackage;


	function contribute() {
		$class = $this->applicationPackage . '\Bootstrap';
		if (class_exists($class)) {
			return new IOCProxy($class);
		}
		return null;
	}

}
?>