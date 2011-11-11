<?php
namespace tapeet\ioc;


class ContextUtils {


	const CONTEXT_KEY = '___tapeet.context';


	static function getContext() {
		return $GLOBALS[self::CONTEXT_KEY];
	}


	static function setContext(Context $context) {
		$GLOBALS[self::CONTEXT_KEY] = $context;
	}

}
