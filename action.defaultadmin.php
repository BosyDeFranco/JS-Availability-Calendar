<?php
if (! $this->CheckPermission('Use JSAvailability')) {
  return;
}

$tab = trim($params['active_tab']) ? $params['active_tab'] : '';

$tab_header = $this->StartTabHeaders();

$tab_header .= $this->SetTabHeader('calendar-view',$this->Lang('calendar-view'),('calendar-view' == $tab)?true:false);
if($this->CheckPermission('Manage JSAvailability objects'))
	$tab_header .= $this->SetTabHeader('objects',$this->Lang('objects'),('objects' == $tab)?true:false);
$tab_header .= $this->SetTabHeader('preferences',$this->Lang('preferences'),('preferences' == $tab)?true:false);

echo $tab_header.$this->EndTabHeaders().$this->StartTabContent();

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