<?php


use tapeet\web\ioc\IOCProxy;
use tapeet\web\ioc\ServiceLocator;


function smarty_block_component($params, $content, &$smarty, &$repeat) {
	$name = null;
	if (isset($params['name'])) {
		$name = $params['name'];
	}

	$type = null;
	if (isset($params['type'])) {
		$type = $params['type'];
	}

	if ($name == null) {
		$name = $type;
	}

	$parent = $smarty->_tpl_vars['c'];
	if (! isset($parent->object->_components)) {
		$parent->_components = array();
	}

	$component = null;
	if (array_key_exists($name, $parent->_components)) {
		$component = $parent->_components[$name];
	} else {
		$serviceLocator = ServiceLocator::getServiceLocator();
		$componentFactory = $serviceLocator->getService('componentFactory');
		$component = $componentFactory->getComponent($type);
		$parent->_components[$name] = $component;
		$component->_name = $name;
		$component->_parent = $parent;
	}

	$componentBlock = new ComponentBlock();
	$componentBlock->component = $component;
	return $componentBlock->renderBlock($params, $content, $smarty, $repeat);
}


class ComponentBlock {


	public $component;


	function render($params, &$smarty) {
		foreach ($params as $property => $value) {
			if ($property == 'type' || $property == 'name') {
				continue;
			}
			$this->component->$property = $value;
		}

		$serviceLocator = ServiceLocator::getServiceLocator();
		$renderer = $serviceLocator->getService('renderer');
		return $renderer->render($this->component);
	}


	function renderBlock($params, $content, &$smarty, &$repeat) {
		if ($repeat) {
			return;
		}
		if (isset($content)) {
			$this->component->content = $content;
		}
		return $this->render($params, $smarty);
	}


}


?>