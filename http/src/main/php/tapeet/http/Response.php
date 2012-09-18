<?php
namespace tapeet\http;


class Response {


	const SC_BAD_REQUEST = 400;
	const SC_FORBIDDEN = 403;
	const SC_NOT_FOUND = 404;


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
