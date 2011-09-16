<?php
namespace tapeet\ioc;


class ContextLoader {


	static function load($file) {
		$context = new Context();
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
