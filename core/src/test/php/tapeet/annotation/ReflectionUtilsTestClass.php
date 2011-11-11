<?php
namespace tapeet\annotation;


use \Neat\Stuff ;
// use \NoWeDidntUseIt;
use 	\Foo\Bar\Baz 	
		as 	Blah
	;
/* 

 
 use Gotcha; */
use My\Full\FirstClassname as FirstClass;
// comment
use My\Full\SecondClassname as SecondClass // another comment
	, My\Full\NSname /* class ACLass {} */
	;

use blah_blah\blah as doh;

/**
 * Class docs and stuff {} {}
 */
class ReflectionUtilsTestClass {}
