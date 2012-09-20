<?php
namespace tapeet\annotation;


use \ReflectionClass;


interface ClassAnnotation {


	function onLoad(ReflectionClass $class, ClassAnnotationChain $chain);

}
