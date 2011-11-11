<?php
namespace tapeet\annotation;


use \ReflectionClass;


class ReflectionUtils {


	static $ANNOTATIONS = array();
	static $IMPORTS = array();


	static function getAnnotations(ReflectionClass $class) {
		if (array_key_exists($class->getName(), self::$ANNOTATIONS)) {
			return self::$ANNOTATIONS[$class->getName()];
		}

		$parser = new AnnotationParser($class->getNamespaceName(), self::getImports($class));

		$annotations = new Annotations($class);
		$annotations->setClassAnnotations($parser->parse($class->getDocComment()));

		foreach ($class->getMethods() as $method) {
			$methodAnnotations = $parser->parse($method->getDocComment());
			if (! $methodAnnotations) {
				continue;
			}
			$annotations->setMethodAnnotations($method->getName(), $methodAnnotations);
		}

		foreach ($class->getProperties() as $property) {
			$propertyAnnotations = $parser->parse($property->getDocComment());
			if (! $propertyAnnotations) {
				continue;
			}
			$annotations->setPropertyAnnotations($property->getName(), $propertyAnnotations);
		}

		self::$ANNOTATIONS[$class->getName()] = $annotations;
		return $annotations;
	}


	static function getImports(ReflectionClass $class) {
		if (array_key_exists($class->getName(), self::$IMPORTS)) {
			return self::$IMPORTS[$class->getName()];
		}

		$source = php_strip_whitespace($class->getFileName());

		preg_match_all("/use ([^;]+);/i", $source, $importStatements);

		$imports = array();

		foreach ($importStatements[1] as $importStatement) {
			foreach (explode(',', $importStatement) as $import) {
				$import = trim($import);

				$nameAndAlias = preg_split('/\\s+as\\s+/i', $import, 2);
				$name = $nameAndAlias[0];

				if (count($nameAndAlias) == 2) {
					$alias = $nameAndAlias[1];
				} else {
					$nameParts = preg_split('/\\\\/', $name);
					$alias = array_pop($nameParts);
				}

				$imports[$alias] = $name;
			}
		}

		self::$IMPORTS[$class->getName()] = $imports;
		return $imports;
	}

}
