<?php
namespace tapeet\http\response;


class CreatedStatus extends Status {


	const CODE = 201;
	const TEXT = 'Created';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
