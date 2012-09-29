<?php
namespace tapeet\util;


class Configuration {


	static function load(array $files) {
		$configuration = array();
		foreach ($files as $file) {
			$configuration = self::merge($configuration, parse_ini_file($file, TRUE));
		}
		return $configuration;
	}


	static function merge(array $first, array $second) {
			foreach ($second as $key => $value) {
				if (array_key_exists($key, $first) && is_array($value) && is_array($first[$key])) {
					$first[$key] = self::merge($first[$key], $second[$key]);
				} else {
					$first[$key] = $value;
				}
			}
			return $first;
	}

}
