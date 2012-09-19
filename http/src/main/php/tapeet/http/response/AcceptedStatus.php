<?php
namespace tapeet\http\response;


class AcceptedStatus extends Status {


	const CODE = 202;
	const TEXT = 'Accepted';


	function __construct($text = self::TEXT) {
		parent::__construct(self::CODE, $text);
	}

}
