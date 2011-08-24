<?php
namespace tapeet\web\ioc;


interface ClassDecorator {


	function afterConstruct($object, $type, $chain);


	function onConstruct($type, $chain);

}
?>