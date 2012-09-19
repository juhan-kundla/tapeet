<?php
namespace tapeet\http;


use \tapeet\http\response\Status;


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


	function sendRedirect($url) {
		$this->response->sendRedirect($url);
	}


	function setStatus(Status $status) {
		$this->response->setStatus($status);
	}


	function write($string) {
		$this->response->write($string);
	}

}
