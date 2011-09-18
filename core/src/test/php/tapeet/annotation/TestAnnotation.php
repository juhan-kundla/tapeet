<?php
require_once 'addendum/annotations.php';


use \tapeet\annotation\MethodAnnotation;
use \tapeet\annotation\MethodAnnotationChain;


class Annotation_TestAnnotation extends Annotation implements MethodAnnotation {


	function onCall($object, $method, $args, MethodAnnotationChain $chain) {
		return 'Annotated method: ' . $chain->onCall($object, $method, $args);
	}

}
