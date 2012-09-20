<?php
namespace tapeet\rest\util;


use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

use \tapeet\Filter;
use \tapeet\FilterChain;
use \tapeet\annotation\ClassLoader;
use \tapeet\rest\annotation\Application;


class ApplicationFilter implements Filter {


	/** @Application */
	private $application;


	function __construct() {
		foreach (explode(PATH_SEPARATOR, get_include_path()) as $directory) {
			$files = new RegexIterator(
					new RecursiveIteratorIterator(
							new RecursiveDirectoryIterator($directory)
						)
					, '/^.+\.php$/i'
					, RecursiveRegexIterator::GET_MATCH
				);
			foreach ($files as $fileName => $file) {
				if (strstr(file_get_contents($fileName), '@Resource(')) {
					$className = str_replace(DIRECTORY_SEPARATOR, '\\', str_replace($directory, '', $fileName));
					$className = substr($className, 1, -4);
					class_exists($className);
				}
			}
		}
	}


	function doFilter(FilterChain $chain) {
		$this->application->onRequest();
		return $chain->doFilter();
	}

}
