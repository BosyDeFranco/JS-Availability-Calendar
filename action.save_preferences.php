<?php
if (!$this->CheckPermission('Use JSAvailability'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$this->SetPreference('append_months_before', $params['append_months_before']);
$this->SetPreference('append_months_after', $params['append_months_after']);

// set a message and return to the page.
$return_params['active_tab'] = 'preferences';
$return_params['module_message'] = $this->Lang('preferencessaved');
$this->Redirect($id, 'defaultadmin', $returnid, $return_params);
exit();