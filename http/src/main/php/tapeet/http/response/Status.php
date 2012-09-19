<?php
namespace tapeet\http\response;


abstract class Status {


	private $code;
	private $text;


	function __construct($code, $text) {
		$this->code = $code;
		$this->text = $text;
	}


	function getCode() {
		return $this->code;
	}


	function getText() {
		return $this->text;
	}

}
