<?php
if (!$this->CheckPermission('Manage JSAvailability objects'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$smarty = cmsms()->GetSmarty();
$admintheme = cmsms()->variables['admintheme'];
$smarty->assign('idtext', $this->Lang('id'));
$smarty->assign('objectnametext', $this->Lang('objectname'));
$smarty->assign('saveicon', '<a href="#" onclick="saveObject(this);return false">'.$admintheme->DisplayImage('icons/system/true.gif', $this->Lang('save'), '', '', 'systemicon').'</a>');

$db = cmsms()->GetDb();
$dbresult = $db->Execute('SELECT id, name FROM '.cms_db_prefix().'module_jsavailability_objects');
$objects = array();
while ($dbresult && $row = $dbresult->FetchRow()){
	$object = new StdClass();
	$object->id = $row['id'];
	$object->name = $row['name'];
	$object->editlink = '<a href="#" onclick="editObject(this);return false">'.$admintheme->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon').'</a>';
	$object->deletelink = $admintheme->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon');
	$objects[] = $object;
}
$smarty->assign('objects', $objects);

// Display template
echo $this->ProcessTemplate('objectslist.tpl');