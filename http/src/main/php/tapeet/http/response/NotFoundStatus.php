<?php
namespace tapeet\http\response;


class NotFoundStatus extends Status {


	const CODE = 404;
	const TEXT = 'Not Found';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
