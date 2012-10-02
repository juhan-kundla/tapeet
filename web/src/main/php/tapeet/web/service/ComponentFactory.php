<?php
namespace tapeet\web\service;


class ComponentFactory {


	public $applicationPackage;


	function getComponent($pathString) {
		return $this->getComponentFromPackage($pathString, 'component');
	}


	function getComponentFromPackage($pathString, $package) {
		$basePaths = array($this->applicationPackage, 'tapeet\web');
		$subPath = $this->parsePathString($pathString);
		if (empty($subPath)) {
			return;
		}

		$component = null;

		$pathSize = count($subPath);
		$errors = array();
		$type = null;
		foreach ($basePaths as $basePath) {
			for ($i = $pathSize - 1; $i >= 0; $i--) {
				$path = array();
				for ($j = 0; $j < $pathSize; $j++) {
					if ($i == $j) {
						array_push($path, $package);
					}
					array_push($path, $subPath[$j]);
				}
				$type = $basePath . '\\' . implode('\\', $path);
				if (class_exists($type)) {
					$component = new $type;
					break 2;
				} else {
					array_push($errors, $type);
				}
			}
		}

		if ($component === null) {
			throw new ClassNotFoundException(
					'Failed to load a component from the following paths: ' . join(', ', $errors)
				);
		}

		if (! isset($component->object->_components)) {
			$component->_components = array();
		}

		$component->_name = null;

		if (! isset($component->object->_parameters)) {
			$component->_parameters = array();
		}

		$component->_parent = null;
		$component->_template = str_replace('\\', DIRECTORY_SEPARATOR, $type) . '.tpl';

		return $component;
	}


	function getPage($path) {
		$page = $this->getComponentFromPackage($path, 'page');
		$page->_path = $path;
		return $page;
	}


	function parsePathString($pathString) {
    	if (empty($pathString)) {
    		return array();
    	}
        return explode('/', $pathString);
	}

}
