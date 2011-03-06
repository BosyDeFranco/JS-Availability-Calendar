<?php
if (!$this->CheckPermission('Use JSAvailability'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$this->smarty->assign('startform', $this->CreateFormStart($id, 'save_preferences', $returnid, 'post'));
$this->smarty->assign('endform', $this->CreateFormEnd());

$fields = array();

$fields[0]['text'] = $this->Lang('append_months_before');
$fields[0]['input'] = $this->CreateInputDropdown($id, 'append_months_before', range(0, 12), $this->GetPreference('append_months_before', 0));

$fields[1]['text'] = $this->Lang('append_months_after');
$fields[1]['input'] = $this->CreateInputDropdown($id, 'append_months_after', range(0, 12), $this->GetPreference('append_months_after', 0));

$this->smarty->assign('fields', $fields);

$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', $this->lang('submit')));
$this->smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', $this->lang('cancel')));
$this->smarty->assign('id', $id);

echo $this->ProcessTemplate('form.tpl');