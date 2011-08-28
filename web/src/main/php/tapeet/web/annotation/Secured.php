<?php


require_once 'addendum/annotations.php';

use tapeet\ioc\ClassDecorator;
use tapeet\ioc\MethodDecorator;
use tapeet\ioc\ServiceLocator;
use tapeet\web\security\ACL;
use tapeet\web\security\Group;


class Annotation_Secured extends Annotation implements ClassDecorator, MethodDecorator {


	function afterConstruct($object, $type, $chain) {
		$chain->afterConstruct($object, $type);
	}


	function getACL() {
		$groups = array();
		if (is_array($this->value)) {
			foreach ($this->value as $groupId) {
				array_push($groups, new Group($groupId));
			}
		} else {
			array_push($groups, new Group($this->value));
		}
		return new ACL($groups);
	}


	function getUser() {
		$serviceLocator = ServiceLocator::getServiceLocator();
		$request = $serviceLocator->getService('request');
		return  $request->remoteUser;
	}


	function onConstruct($type, $chain) {
		$this->getACL()->assertAccess($this->getUser());
		return $chain->onConstruct($type);
	}


	function onInvoke($object, $method, $args, $chain) {
		$this->getACL()->assertAccess($this->getUser());
		return $chain->onInvoke($object, $method, $args);
	}

}
?>