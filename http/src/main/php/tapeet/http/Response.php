<?php
namespace tapeet\http;


class Response {


	const SC_OK = 200;
	const SC_CREATED = 201;
	const SC_ACCEPTED = 202;
	const SC_BAD_REQUEST = 400;
	const SC_FORBIDDEN = 403;
	const SC_NOT_FOUND = 404;
	const SC_METHOD_NOT_ALLOWED = 405;


	function encodeRedirectURL($url) {
		return $url;
	}


	function encodeURL($url) {
		return $url;
	}


	function sendRedirect($url) {
		header('Location: ' . $url);
	}


	function setStatus($status) {
		header('x', true, $status);
	}


	function write($string) {
		echo($string);
	}

}
