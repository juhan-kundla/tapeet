<?php
namespace tapeet\http\response;


class ForbiddenStatus extends Status {


	const CODE = 403;
	const TEXT = 'Forbidden';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
