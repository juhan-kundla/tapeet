<?php
namespace tapeet\web\component;


class Form {


	/** @Parameter('_component') */
	public $component;
	public $id;
	public $pageContextEnabled = true;


	function getName() {
		$name = array();
		$component = $this;

		while ($component != null) {
			if ($component->_parent != null) {
				array_unshift($name, $component->_name);
			} else {
				// top-level component is a Page
				// so it has no name, but it has path instead
				array_unshift($name, $component->_path);
			}
			$component = $component->_parent;
		}

		return join('.', $name);
	}


	function getPage() {
		$page = $this;

		while ($page->_parent != null) {
			$page = $page->_parent;
		}

		return $page;
	}


	function getPageContext() {
		if (! $this->pageContextEnabled) {
			return array();
		}

		$page = $this->getPage();
		if (
				isset($page->object) && method_exists($page->object, 'onPassivate')
				|| method_exists($page, 'onPassivate')
		) {
			return $page->onPassivate();
		}

		return array();
	}


	function isSubmit() {
		return $this->getName() == $this->component->getValue();
	}

}
?>