<?php
if (!$this->CheckPermission('Use JSAvailability'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$this->smartyConfig(true);

// Display template
echo $this->ProcessTemplate('show_calendar.tpl');