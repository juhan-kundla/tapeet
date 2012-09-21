<?php
namespace tapeet\ioc;


class ContextUtils {


	const CONTEXT_KEY = '_tapeet_core_context';


	static function getContext() {
		$context = NULL;

		if (array_key_exists(self::CONTEXT_KEY, $GLOBALS)) {
			$context = $GLOBALS[self::CONTEXT_KEY];
		}

		if ($context === NULL) {
			$context = new Context();
			$GLOBALS[self::CONTEXT_KEY] = $context;
		}

		return $context;
	}


	static function load($file) {
		$context = self::getContext();

		foreach (yaml_parse(file_get_contents($file, true)) as $name => $configuration) {
			$descriptor = new Descriptor();
			$descriptor->name = $name;
			$descriptor->class = $configuration["class"];

			if (array_key_exists("constructorArgs", $configuration)) {
				$descriptor->constructorArgs = $configuration['constructorArgs'];
			}

			if (array_key_exists("properties", $configuration)) {
				$descriptor->properties = $configuration['properties'];
			}

			$context->addDescriptor($descriptor);
		}

		return $context;
	}

}
