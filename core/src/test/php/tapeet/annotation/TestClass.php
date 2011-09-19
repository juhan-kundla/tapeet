<?php
namespace tapeet\annotation;


class TestClass {


	protected $a = 'a';
	/**
	 * @TestAnnotation
	 */
	protected $b = 'b';
	/**
	 * @TestAnnotation
	 */
	protected $c = null;
	public $x = 'x';
	/**
	 * @TestAnnotation
	 */
	public $y = 'y';
	/**
	 * @TestAnnotation
	 */
	public $z = null;


	function foo() {
		return 'foo';
	}


	/**
	 * @TestAnnotation
	 */
	function bar() {
		return 'bar';
	}


	/**
	 * @TestAnnotation
	 */
	function baz($quux) {
		return $quux;
	}


	function getA() {
		return $this->a;
	}


	function getB() {
		return $this->b;
	}


	function getC() {
		return $this->c;
	}

}
