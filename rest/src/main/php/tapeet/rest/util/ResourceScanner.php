<?php
namespace tapeet\rest\util;


use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

use \tapeet\Filter;
use \tapeet\FilterChain;


class ResourceScanner implements Filter {


	// TODO: caching
	private $resourceClasses;


	function doFilter(FilterChain $chain) {
		foreach ($this->getResourceClasses() as $class) {
			// Trigger the loading of the classes having Resource annotation
			// and thus the registration of the resource endpoints
			class_exists($class);
		}
		return $chain->doFilter();
	}


	function getResourceClasses() {
		if ($this->resourceClasses === NULL) {
			$this->initResourceClasses();
		}

		return $this->resourceClasses;
	}


	function initResourceClasses() {
		$this->resourceClasses = array();

		foreach (explode(PATH_SEPARATOR, get_include_path()) as $directory) {
			$files = new RegexIterator(
					new RecursiveIteratorIterator(
							new RecursiveDirectoryIterator($directory)
						)
					, '/^.+\.php$/i'
					, RecursiveRegexIterator::GET_MATCH
				);
			foreach ($files as $fileName => $file) {
				if (strstr(file_get_contents($fileName), '@' . 'Resource(')) {
					$class = str_replace(DIRECTORY_SEPARATOR, '\\', str_replace($directory, '', $fileName));
					$class = substr($class, 1, -4);
					$this->resourceClasses[] = $class;
				}
			}
		}
	}


}
