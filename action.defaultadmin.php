<?php
if (! $this->CheckPermission('Use JSAvailability'))
  return;

if (!empty($params['active_tab'])) {
	$tab = $params['active_tab'];
} else {
	$tab = 'calendar-view';
}

echo $this->StartTabHeaders();
echo $this->SetTabHeader('calendar-view', $this->Lang('calendar-view'), ($tab == 'calendar-view'));

if($this->CheckPermission('Manage JSAvailability objects')) {
	echo  $this->SetTabHeader('objects', $this->Lang('objects'), ('objects' == $tab));
}

echo $this->SetTabHeader('preferences', $this->Lang('preferences'), ('preferences' == $tab));

echo $this->EndTabHeaders();
echo $this->StartTabContent();

echo $this->StartTab('calendar-view', $params);
include(dirname(__FILE__).'/function.admin_calendartab.php');
echo $this->EndTab();

if($this->CheckPermission('Manage JSAvailability objects')){
	echo $this->StartTab('objects', $params);
	include(dirname(__FILE__).'/function.admin_objectstab.php');
	echo $this->EndTab();
}

echo $this->StartTab('preferences', $params);
include(dirname(__FILE__).'/function.admin_preferencestab.php');
echo $this->EndTab();

echo $this->EndTabContent();
?>