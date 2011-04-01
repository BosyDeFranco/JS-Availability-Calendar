<?php
if (!$this->CheckPermission('Manage JSAvailability objects'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$smarty = cmsms()->GetSmarty();
$smarty->assign('idtext', $this->Lang('id'));
$smarty->assign('objectnametext', $this->Lang('objectname'));
$smarty->assign('saveicon', '<a href="#" onclick="saveObject(this);return false">'.$this->DisplayImage('icons/system/true.gif', $this->Lang('save'), '', '', 'systemicon').'</a>');
$smarty->assign('addlink', '<a href="#" onclick="addObject(this);return false;">'.$this->DisplayImage('icons/system/newobject.gif', $this->Lang('add'), '', '', 'systemicon').' '.$this->Lang('add').'</a>');

$db = cmsms()->GetDb();
$dbresult = $db->Execute('SELECT id, name FROM '.cms_db_prefix().'module_jsavailability_objects');
$objects = array();
while ($dbresult && $row = $dbresult->FetchRow()){
	$object = new StdClass();
	$object->id = $row['id'];
	$object->name = $row['name'];
	$object->editlink = '<a href="#" onclick="editObject(this);return false">'.$this->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon').'</a>';
	$object->deletelink = '<a href="#" onclick="deleteObject(this);return false">'.$this->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon').'</a>';
	$objects[] = $object;
}
$smarty->assign('objects', $objects);

// Display template
echo $this->ProcessTemplate('objectslist.tpl');