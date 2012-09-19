<?php
namespace tapeet\http\response;


class OKStatus extends Status {


	const CODE = 200;
	const TEXT = 'OK';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
