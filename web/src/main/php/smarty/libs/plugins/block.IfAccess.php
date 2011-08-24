<?php


use tapeet\web\ioc\ServiceLocator;
use tapeet\web\security\ACL;
use tapeet\web\security\Group;


function smarty_block_IfAccess($params, $content, &$smarty, &$repeat) {
	if ($repeat) {
		$groups = array();
		if (isset($params['groups'])) {
			foreach (explode(',', $params['groups']) as $groupId) {
				array_push($groups, new Group($groupId));
			}
		}
		$acl = new ACL($groups);

		$negate = false;
		if (isset($params['negate'])) {
			$negate = $params['negate'];
		}

		$serviceLocator = ServiceLocator::getServiceLocator();
		$request = $serviceLocator->getService('request');
		if (! ($negate xor $acl->isAllowed($request->remoteUser))) {
			$repeat = false;
		}
	} else {
		return $content;
	}
}
?>