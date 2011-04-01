<?php
$this->smartyConfig(false, $params['year'], $params['object_id']);

// Display template
echo $this->ProcessTemplate('fe_show_calendar.tpl');