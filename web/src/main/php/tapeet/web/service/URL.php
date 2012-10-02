<?php
namespace tapeet\web\service;


class URL {


	function getURL($page, $parameters = array()) {
		$query = array();

		if (method_exists($page, 'onPassivate')) {
			$parameters = array_merge($page->onPassivate(), $parameters);
		}

		foreach ($parameters as $parameter) {
			if ($parameter->__toString() != null) {
				array_push($query, $parameter->__toString());
			}
		}

		$url = '/' . $page->_path;
		if (! empty($query)) {
			$url .= '?' . implode('&', $query);
		}

		return $url;
	}

}
