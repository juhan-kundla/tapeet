<?php
namespace tapeet\ioc;


interface PropertyDecorator {


	function onInit($object, $property, $chain);

}
