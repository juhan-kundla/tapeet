<?php
namespace tapeet\annotation;


interface ClassAnnotation {


	function onLoad($class, ClassAnnotationChain $chain);

}
