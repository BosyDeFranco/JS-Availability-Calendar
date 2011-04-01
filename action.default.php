<?php
if(isset($params['year']) && !preg_match('#[0-9]{4}#', $params['year'])){
	echo sprintf('<p style="color:#f00">%s</p>', $this->Lang('wrongdateformat'));
	exit;
}

$this->smartyConfig(false, $params['year'], $params['object_id']);

// Display template
echo $this->ProcessTemplate('fe_show_calendar.tpl');