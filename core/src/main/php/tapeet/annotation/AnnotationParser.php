<?php
namespace tapeet\annotation;


use \tapeet\addendum\parser\AnnotationsMatcher;


class AnnotationParser {


	private $aliases;
	private $namespace;


	function __construct($namespace, array $aliases) {
		$this->aliases = $aliases;
		$this->namespace = $namespace;
	}


	function parse($string) {
		$annotations = array();

		if (! $string) {
			return $annotations;
		}

		$parser = new AnnotationsMatcher();
		$parser->matches($string, $data);

		foreach ($data as $name => $values) {
			if (array_key_exists($name, $this->aliases)) {
				$className = $this->aliases[$name];
			} elseif (substr($name, 0, 1) != '\\') {
				$className = $this->namespace . '\\' . $name;
			} else {
				$className = $name;
			}

			$annotations[] = new $className();
		}

		return $annotations;
	}

}
