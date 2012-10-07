<?php
function smarty_resource_include_path_source($tpl_name, &$tpl_source, &$smarty) {
	$tpl_source = file_get_contents($tpl_name, FILE_USE_INCLUDE_PATH);
    return true;
}

function smarty_resource_include_path_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {
	$tpl_timestamp = filemtime(stream_resolve_include_path($tpl_name));
	return true;
}

function smarty_resource_include_path_secure($tpl_name, &$smarty) {
    return true;
}

function smarty_resource_include_path_trusted($tpl_name, &$smarty) {
}
