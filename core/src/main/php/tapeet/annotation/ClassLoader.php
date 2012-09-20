<?php
namespace tapeet\annotation;


class ClassLoader implements PropertyAnnotation {


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		return \tapeet\ClassLoader::get();
	}

}
