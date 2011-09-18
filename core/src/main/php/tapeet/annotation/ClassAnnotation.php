<?php
namespace tapeet\annotation;


interface ClassAnnotation {


	function onLoad($class, $chain);

}
