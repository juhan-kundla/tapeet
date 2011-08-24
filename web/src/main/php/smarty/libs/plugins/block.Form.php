<?php


require_once 'smarty/libs/plugins/block.component.php';


function smarty_block_Form($params, $content, &$smarty, &$repeat) {
	$params['type'] = 'Form';
	return smarty_block_component($params, $content, $smarty, $repeat);
}


?>