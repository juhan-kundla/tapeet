<?php


use tapeet\web\ioc\IOCProxy;


class PageURL {


	/** @Service */
	public $componentFactory;
	/** @Service */
	public $response;
	/** @Service */
	public $url;


	function render($params, $smarty) {
		$page = null;
		if (is_object($params['page'])) {
			$page = $params['page'];
		} else {
			$page = $this->componentFactory->getPage($params['page']);
			foreach ($params as $property => $value) {
				if ($property == 'page') {
					continue;
				}
				if (method_exists($page->object, 'set' . ucfirst($property))) {
					call_user_func(array($page, 'set' . ucfirst($property)), $value);
				} else {
					$page->$property = $value;
				}
			}
		}
		return htmlspecialchars(
				$this->response->encodeURL($this->url->getURL($page)),
				ENT_QUOTES,
				'UTF-8'
			);
	}



}

function smarty_function_PageURL($params, $smarty) {
	$pageURL = new IOCProxy('PageURL');
	return $pageURL->render($params, $smarty);
}
?>