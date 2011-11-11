<?php
namespace tapeet\annotation;


use \PHPUnit_Framework_TestCase;
use \ReflectionClass;


class ReflectionUtilsTest extends PHPUnit_Framework_TestCase {


	function testGetImports() {
		$class = new ReflectionClass('\tapeet\annotation\ReflectionUtilsTestClass');

		$imports = ReflectionUtils::getImports($class);

		$this->assertTrue(array_key_exists('Stuff', $imports));
		$this->assertEquals('\Neat\Stuff', $imports['Stuff']);

		$this->assertTrue(array_key_exists('Blah', $imports));
		$this->assertEquals('\Foo\Bar\Baz', $imports['Blah']);

		$this->assertTrue(array_key_exists('FirstClass', $imports));
		$this->assertEquals('My\Full\FirstClassname', $imports['FirstClass']);

		$this->assertTrue(array_key_exists('SecondClass', $imports));
		$this->assertEquals('My\Full\SecondClassname', $imports['SecondClass']);

		$this->assertTrue(array_key_exists('NSname', $imports));
		$this->assertEquals('My\Full\NSname', $imports['NSname']);

		$this->assertTrue(array_key_exists('doh', $imports));
		$this->assertEquals('blah_blah\blah', $imports['doh']);

		$this->assertEquals(6, count($imports));
	}

}
