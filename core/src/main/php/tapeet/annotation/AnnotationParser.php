<?php
namespace tapeet\annotation;


require_once 'annotations.php';

use \AnnotationsMatcher;


class AnnotationParser {


	private $imports;
	private $namespace;


	function __construct($namespace, array $imports) {
		$this->imports = $imports;
		$this->namespace = $namespace;
	}


	function parse($string) {
		$annotations = array();

		if (! $string) {
			return $annotations;
		}

		$parser = new AnnotationsMatcher();
		$parser->matches($string, $data);

		foreach ($data as $name => $properties) {
			if (array_key_exists($name, $this->imports)) {
				$className = $this->imports[$name];
			} elseif (substr($name, 0, 1) != '\\') {
				$className = $this->namespace . '\\' . $name;
			} else {
				$className = $name;
			}

			$annotation = new $className();
			foreach ($properties[0] as $property => $value) {
				$annotation->$property = $value;
			}
			$annotations[] = $annotation;
		}

		return $annotations;
	}

}
