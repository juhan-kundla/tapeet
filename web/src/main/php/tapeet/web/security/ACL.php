<?php
namespace tapeet\web\security;


class ACL {


	public $groups;
	public $users;


	function __construct($groups = array(), $users = array()) {
		$this->groups = $groups;
		$this->users = $users;
	}


	function assertAccess($user) {
		if (! $this->isAllowed($user)) {
			throw new AccessDeniedException();
		}
	}


	function isAllowed($user) {
		foreach ($this->users as $allowedUser) {
			if ($user->id == $allowedUser->id) {
				return true;
			}
		}

		foreach ($this->groups as $group) {
			if ($user->isMember($group)) {
				return true;
			}
		}

		return false;
	}

}
?>