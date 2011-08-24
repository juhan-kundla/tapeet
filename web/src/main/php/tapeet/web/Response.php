<?php
namespace tapeet\web;


class Response {


	static $SC_FORBIDDEN = 403;
	static $SC_NOT_FOUND = 404;


	function encodeRedirectURL($url) {
		return $url;
	}


	function encodeURL($url) {
		return $url;
	}


	function sendError($errorCode) {
		header('x', true, $errorCode);
	}


	function sendRedirect($url) {
		header('Location: ' . $url);
	}


	function write($string) {
		echo($string);
	}

}
?>