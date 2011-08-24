<?php
namespace tapeet\web\validator;


class LengthValidator {


	public $max;
	public $message;
	public $min;


	function __construct($min = null, $max = null, $message = null) {
		$this->max = $max;
		$this->min = $min;

		if ($message == null) {
			if ($max == null) {
				$message = 'Nõutud on rohkem kui ' . $min . ' sümbolit';
			} else if ($min == null) {
				$message = 'Üle ' . $max . ' sümboli pole lubatud';
			} else if ($min == $max) {
				$message = 'Nõutud on täpselt ' . $min . ' sümbolit';
			} else {
				$message = 'Väärtuse pikkus peab olema vahemikus ' . $min . ' kuni ' . $max . ' sümbolit';
			}
		}
		$this->message = $message;
	}


	function validate($parameter) {
		if ($parameter->getValue() == null) {
			return;
		}

		if (
				$this->min != null && strlen(trim($parameter->getValue())) < $this->min
				|| $this->max != null && strlen(trim($parameter->getValue())) > $this->max
			) {
			$parameter->addError($this->message);
		}
	}

}
?>