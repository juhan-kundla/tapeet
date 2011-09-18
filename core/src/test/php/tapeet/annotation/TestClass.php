<?php
namespace tapeet\annotation;


class TestClass {


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

}
