<?php
namespace tapeet\web\ioc;


interface PropertyDecorator {


	function onInit($object, $property, $chain);

}
?>