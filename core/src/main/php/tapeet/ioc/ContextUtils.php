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

}
