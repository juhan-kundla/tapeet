<?php
namespace tapeet\web;


use DateTime;
use Exception;


class Parameter {


	public $errors;
	public $name;
	private $validators;
	private $values;


	function __construct($name) {
		$this->errors = array();
		$this->name = $name;
		$this->validators = array();
		$this->values = array();
	}


	function addError($error) {
		array_push($this->errors, $error);
	}


	function addErrors($errors) {
		$this->errors = array_merge($this->errors, $errors);
	}


	function addValidator($validator) {
		array_push($this->validators, $validator);
	}


	function getBooleanValue() {
		return $this->getValue() != null;
	}


	function getDateValue() {
		if ($this->getValue() !== null) {
			try {
				return new DateTime($this->getValue());
			} catch (Exception $ignored) {}
		}
		return null;
	}


	function getIntValue() {
		$value = $this->getValue();
		if ($value != null && is_numeric($value)) {
			return (int) $value;
		}
		return null;
	}


	function getName() {
		return $this->name;
	}


	function getValue() {
		if (count($this->values) > 0) {
			return $this->values[0];
		}
		return null;
	}


	function onActivate($request) {
		$this->values = $request->getParameterValues($this->name);
	}


	function onValidate() {
		$this->errors = array();
		foreach ($this->validators as $validator) {
			$validator->validate($this);
		}
	}


	function setBooleanValue($value) {
		$this->setValue($value ? 'true' : null);
	}


	function setValue($value) {
		$this->values = array();
		if ($value != null) {
			array_push($this->values, $value);
		}
	}


	public function __toString() {
		$encodedValues = array();

		foreach ($this->values as $value) {
			if ($value != null) {
				array_push($encodedValues, urlencode($this->name) . '=' . urlencode($value));
			}
		}

		return implode('&', $encodedValues);
	}

}
?>