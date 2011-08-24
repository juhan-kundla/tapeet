<?php
namespace tapeet\web\validator;


class RequiredValidator {


	public $message;


	function __construct($message = 'Kohustuslik väli') {
		$this->message = $message;
	}


	function validate($parameter) {
		$value = $parameter->getValue();
		if (empty($value)) {
			$parameter->addError($this->message);
		}
	}

}
?>