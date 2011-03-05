<?php
if (!$this->CheckPermission('Use JSAvailability'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$config = cmsms()->GetConfig();

// config
$append_start = $this->GetPreference('append_months_before', 2);;
$append_end = $this->GetPreference('append_months_after', 2);

// Load entries
$entryarray = array();

$query = 'SELECT type, start, end FROM '.cms_db_prefix().'module_jsavailability ORDER BY id DESC';

$dbresult = $db->Execute($query);
while ($dbresult && $row = $dbresult->FetchRow())
	$guests[str_replace('-', '', $row['start'])] = array('end' => str_replace('-', '', $row['end']), 'type' => $row['type']);
$smarty->assign('guests', $guests);

$years[date('Y')-1] = $this->getYearInfo(date('Y')-1);
$years[date('Y')] = $this->getYearInfo(date('Y'));
$years[date('Y')+1] = $this->getYearInfo(date('Y')+1);
$smarty->assign('years', $years);
$smarty->assign('timestamps', $this->getMonthTimestamps());

$smarty->assign('incdir', $config['root_url'].'/modules/JSAvailability/inc/');
$smarty->assign('year', date('Y'));
$smarty->assign('sundayabbrlabel', substr(date('D', 1298761200), 0, 1));
$smarty->assign('sundaylabel', date('l', 1298761200));

/*$year = date("Y");
$smarty->assign('year', $year);

$months_append_start = array();
for($n = 13-$append_start; $n <= 12; $n++)
	$months_append_start[$n] = date("t", mktime(0, 0, 0, $n, 1, $year-1));
$smarty->assign('months_append_start', $months_append_start);

$months = array();
for($n = 1; $n <= 12; $n++){
	$month_names[$n] = utf8_encode(strftime("%B", mktime(0, 0, 0, $n, 1, $year)));
	$months[$n] = date("t", mktime(0, 0, 0, $n, 1, $year));
}
$smarty->assign('month_names', $month_names);
$smarty->assign('months', $months);

$months_append_end = array();
for($n = 1; $n <= $append_end; $n++)
	$months_append_end[$n] = date("t", mktime(0, 0, 0, $n, 1, $year+1));
$smarty->assign('months_append_end', $months_append_end);*/

// Display template
echo $this->ProcessTemplate('show_calendar.tpl');