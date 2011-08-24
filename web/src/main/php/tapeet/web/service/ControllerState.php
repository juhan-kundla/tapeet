<?php
namespace tapeet\web\service;


class ControllerState {


	static $REQUEST_TYPE_SUBMIT = 'submit';
	static $REQUEST_TYPE_RENDER = 'render';


	public $components;
	public $page;
	public $requestType;


	function isRenderRequest() {
		return $this->requestType == self::$REQUEST_TYPE_RENDER;
	}


	function isSubmitRequest() {
		return $this->requestType == self::$REQUEST_TYPE_SUBMIT;
	}

}
?>