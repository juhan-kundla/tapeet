<?php

require_once 'tapeet/web/ioc/ServiceLocator.php';


function smarty_modifier_encodeURL($string) {
	$serviceLocator = ServiceLocator::getServiceLocator();
	$response = $serviceLocator->getService('response');
	return $response->encodeURL(strval($string));
}
?>