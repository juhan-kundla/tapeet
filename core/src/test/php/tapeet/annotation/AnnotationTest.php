<?php
namespace tapeet\annotation;


require_once 'tapeet/annotation/TestAnnotation.php';


use \PHPUnit_Framework_TestCase;


class ClassLoaderTest extends PHPUnit_Framework_TestCase {


	function setUp() {
		AnnotationProcessor::init();
	}


	function testMethodAnnotations() {
		$obj = new TestClass();
		$this->assertEquals($obj->foo(), 'foo');
		$this->assertEquals($obj->bar(), 'Annotated method: bar');
		$this->assertEquals($obj->baz('quux'), 'Annotated method: quux');
	}

}
