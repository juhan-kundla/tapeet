<?php
namespace tapeet\web\ioc;


interface MethodDecorator {


	function onInvoke($object, $method, $args, $chain);

}
?>