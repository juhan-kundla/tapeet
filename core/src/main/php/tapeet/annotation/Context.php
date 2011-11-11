<?php
namespace tapeet\annotation;


use \tapeet\ioc\ContextUtils;


class Context implements PropertyAnnotation {


	function onGet($object, $property, PropertyAnnotationChain $chain) {
		return ContextUtils::getContext();
	}

}
