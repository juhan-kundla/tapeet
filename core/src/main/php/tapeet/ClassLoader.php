<?php
namespace tapeet;


class ClassLoader {


	const CLASS_NAME_PATTERN = '/^[\\a-zA-Z0-9_]+$/';
	private static $INSTANCE;


	private $listeners;


	function __construct() {
		$this->listeners = array();
	}


	function addListener(ClassLoaderListener $listener) {
		$this->listeners[] = $listener;
	}


	static function get() {
		return self::$INSTANCE;
	}


	static function init() {
		if (self::$INSTANCE !== null) {
			return;
		}
		self::$INSTANCE = new ClassLoader();
		spl_autoload_register(array(self::$INSTANCE, 'load'), true);
	}


	function load($className) {
		if (! preg_match(self::CLASS_NAME_PATTERN, $className)) {
			return false;
		}

		$file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
		if (stream_resolve_include_path($file)) {
			include_once $file;
		}

		if (! (class_exists($className, false) || interface_exists($className, false))) {
			return false;
		}

		foreach ($this->listeners as $listener) {
			$listener->onLoad($className);
		}

		return true;
	}

}


ClassLoader::init();
