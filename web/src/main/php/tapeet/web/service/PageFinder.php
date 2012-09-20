<?php
namespace tapeet\web\service;


use \tapeet\Filter;
use \tapeet\FilterChain;
use \tapeet\web\NotFoundException;


class PageFinder implements Filter {


	static $PARAM_COMPONENTS = '_component';


	/** @Service */
	public $componentFactory;
	/** @Service */
	public $controllerState;
	/** @Service */
	public $request;


	function doFilter(FilterChain $chain) {
		$this->controllerState->requestType = ControllerState::$REQUEST_TYPE_RENDER;

		$componentNames = null;
		$componentNamesString = $this->request->getParameter(self::$PARAM_COMPONENTS);
		if ($componentNamesString !== null) {
			$componentNames = explode('.', $componentNamesString);
		} else {
			$componentNames = array();
		}

		$submitPagePath = null;
		if (! empty($componentNames)) {
			$submitPagePath = array_shift($componentNames);
		}

		$pagePath = $this->request->getPathInfo();
		if ($pagePath !== null) {
			$pagePath = substr($pagePath, 1);
		}
		if ($pagePath === null) {
			if ($submitPagePath !== null) {
				$pagePath = $submitPagePath;
				$this->controllerState->requestType = ControllerState::$REQUEST_TYPE_SUBMIT;
			} else {
				$pagePath = 'IndexPage';
			}
		}
			
		$page = null;

		try {
			$page = $this->componentFactory->getPage($pagePath);
		} catch (ClassNotFoundException $ignored) {}

		if ($page === null) {
			throw new NotFoundException();
		}

		$components = array($page);
		$component = $page;
		foreach ($componentNames as $componentName) {
			if (! array_key_exists($componentName, $component->_components)) {
				break;
			}
			$component = $component->_components[$componentName];
			array_push($components, $component);
		}

		$this->controllerState->page = $page;
		$this->controllerState->components = $components;

		$chain->doFilter();
	}

}
?>