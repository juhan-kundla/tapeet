<?php
namespace tapeet\annotation;


require_once 'tapeet/annotation/TestAnnotation.php';


use \PHPUnit_Framework_TestCase;


class AnnotationTest extends PHPUnit_Framework_TestCase {


	function setUp() {
		AnnotationProcessor::init();
	}


	function testMethodAnnotations() {
		$obj = new TestClass();
		$this->assertEquals('foo', $obj->foo());
		$this->assertEquals('Annotated method: bar', $obj->bar());
		$this->assertEquals('Annotated method: quux', $obj->baz('quux'));
	}


	function testPropertyAnnotations() {
		$obj = new TestClass();
		$this->assertEquals('a', $obj->getA());
		$this->assertEquals('Annotation: b', $obj->getB());
		$this->assertEquals('Annotation', $obj->getC());
		$this->assertEquals('x', $obj->x);
		$this->assertEquals('Annotation: y', $obj->y);
		$this->assertEquals('Annotation', $obj->z);
	}

}
