<?php


require_once 'smarty/libs/Smarty.class.php';
require_once 'smarty/libs/Smarty_Compiler.class.php';


class MVC_Smarty_Compiler extends Smarty_Compiler {


	function __construct() {
		parent::__construct();
		$this->_func_regexp = '[a-zA-Z_][\w/]+';
	}

}
?>