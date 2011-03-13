<?php
if(isset($params['usr_function'])){
	$usr_params = isset($params['params']) ? explode(',', $params['params']) : '';
	echo call_user_func_array(array($this, $params['usr_function']), $usr_params);
	exit;
}elseif(isset($params['usr_tab'])){
	include(dirname(__FILE__).'/function.admin_'.$params['usr_tab'].'.php');
	exit;
}
?>