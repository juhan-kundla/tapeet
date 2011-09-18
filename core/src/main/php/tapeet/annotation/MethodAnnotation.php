<?php
namespace tapeet\annotation;


interface MethodAnnotation {


	function onCall($object, $method, $args, MethodAnnotationChain $chain);

}
