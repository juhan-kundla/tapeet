<?php
namespace tapeet\web\security;


class User {


	public $groups;
	public $firstName;
	public $id;
	public $lastName;


	function __construct($groups = array()) {
		$this->groups = $groups;
	}


	function isMember($group) {
		foreach ($this->groups as $grantedGroup) {
			if ($grantedGroup->id == $group->id) {
				return true;
			}
		}
		return false;
	}

}
?>