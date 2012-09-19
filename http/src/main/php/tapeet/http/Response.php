<?php
namespace tapeet\http;


use \tapeet\http\response\Status;


class Response {


	function encodeRedirectURL($url) {
		return $url;
	}


	function encodeURL($url) {
		return $url;
	}


	function sendRedirect($url) {
		header('Location: ' . $url);
	}


	function setStatus(Status $status) {
		header('x', true, $status->getCode());
	}


	function write($string) {
		echo($string);
	}

}
