<?php


require_once 'smarty/libs/plugins/block.component.php';


function smarty_block_Layout($params, $content, &$smarty, &$repeat) {
	$params['type'] = 'Layout';
	return smarty_block_component($params, $content, $smarty, $repeat);
}


?>