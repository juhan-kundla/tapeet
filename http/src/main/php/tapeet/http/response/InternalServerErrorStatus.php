<?php
namespace tapeet\http\response;


class InternalServerErrorStatus extends Status {


	const CODE = 500;
	const TEXT = 'Internal Server Error';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
