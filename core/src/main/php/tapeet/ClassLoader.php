<?php
namespace tapeet;


class ClassLoader {


	const CLASS_NAME_PATTERN = '/^[\\a-zA-Z0-9_]+$/';


	static function init() {
		spl_autoload_register(array(__CLASS__, 'load'), true);
	}


	static function load($type) {
		if (! preg_match(self::CLASS_NAME_PATTERN, $type)) {
			return false;
		}

		$file = str_replace('\\', DIRECTORY_SEPARATOR, $type) . '.php';
		if (stream_resolve_include_path($file)) {
			include_once $file;
		}
		return class_exists($type, false);
	}

}

ClassLoader::init();
