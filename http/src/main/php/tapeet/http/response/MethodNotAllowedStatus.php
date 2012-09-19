<?php
namespace tapeet\http\response;


class MethodNotAllowedStatus extends Status {


	const CODE = 405;
	const TEXT = 'Method Not Allowed';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
