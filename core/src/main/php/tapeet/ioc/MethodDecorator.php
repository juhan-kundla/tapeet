<?php
namespace tapeet\ioc;


interface MethodDecorator {


	function onInvoke($object, $method, $args, $chain);

}
