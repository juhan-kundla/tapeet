<?php
namespace tapeet\http\response;


class BadRequestStatus extends Status {


	const CODE = 400;
	const TEXT = 'Bad Request';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
