<?php
namespace tapeet\web\service;


use tapeet\web\Filter;


class Activator implements Filter {


	/** @Service */
	public $controllerState;
	/** @Service */
	public $request;


	function doFilter($chain) {
		$this->activate($this->controllerState->page);
		$chain->doFilter();
	}


	function activate($component) {
		foreach ($component->_parameters as $parameter) {
			$parameter->onActivate($this->request);
		}

		foreach ($component->_components as $subComponent) {
			$this->activate($subComponent);
		}

		if (method_exists($component->object, 'onActivate')) {
			$component->onActivate();
		}
	}

}
?>