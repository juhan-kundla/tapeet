<?php
namespace tapeet\http;


class Request {


	function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}


	function getParameter($name) {
		if (array_key_exists($name, $_REQUEST)) {
			return $_REQUEST[$name];
		}
		return null;
	}



	function getParameterValues($name) {
		# TODO: The PHP does not support multiple parameter values
		# without parsing the query string directly
		# Implement the parsing in the request, if required
		# $query  = explode('&', $_SERVER['QUERY_STRING']); etc...
		$value = $this->getParameter($name);
		if ($value == null) {
			return array();
		}
		return array($value);
	}


	function getPathInfo() {
		if (array_key_exists('PATH_INFO', $_SERVER)) {
			return $_SERVER['PATH_INFO'];
		}
		return null;
	}


	function getRemoteUserId() {
		if (array_key_exists('PHP_AUTH_USER', $_SERVER)) {
			return $_SERVER['PHP_AUTH_USER'];
		}
		return null;
	}


	function getRemotePassword() {
		if (array_key_exists('PHP_AUTH_PW', $_SERVER)) {
			return $_SERVER['PHP_AUTH_PW'];
		}
		return null;
	}

}
