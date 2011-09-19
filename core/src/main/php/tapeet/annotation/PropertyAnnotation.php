<?php
namespace tapeet\annotation;


interface PropertyAnnotation {


	function onGet($object, $property, PropertyAnnotationChain $chain);

}
