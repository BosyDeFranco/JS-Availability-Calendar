<?php
if(isset($params['usr_function'])){
	$usr_params = isset($params['params']) ? explode(',', $params['params']) : '';
	echo call_user_func_array(array($this, $params['usr_function']), $usr_params);
	exit;
}
?>