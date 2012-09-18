<?php
namespace tapeet\http;


class ResponseWrapper extends Response {


	private $response;


	function __construct($response) {
		$this->response = $response;
	}


	function encodeRedirectURL($url) {
		return $this->response->encodeRedirectURL($url);
	}


	function encodeURL($url) {
		return $this->response->encodeURL($url);
	}


	function sendError($errorCode) {
		$this->response->sendError($errorCode);
	}


	function sendRedirect($url) {
		$this->response->sendRedirect($url);
	}


	function write($string) {
		$this->response->write($string);
	}

}
