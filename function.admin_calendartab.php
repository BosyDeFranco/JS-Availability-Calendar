<?php
if (!$this->CheckPermission('Use JSAvailability'))
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));

$config = cmsms()->GetConfig();

// config
$append_start = $this->GetPreference('append_months_before', 2);;
$append_end = $this->GetPreference('append_months_after', 2);
$current_year = $this->GetPreference('current_year', date('Y'));

// Load entries
$entryarray = array();

$query = 'SELECT type, DATE(arrival) as arrival, DATE(departure) as departure FROM '.cms_db_prefix().'module_jsavailability ORDER BY id DESC';

$dbresult = $db->Execute($query);
while ($dbresult && $row = $dbresult->FetchRow()){
	$entry = new stdClass();
	$entry->arrival = $row['arrival'];
	$entry->departure = $row['departure'];
	$entry->type = $row['type'];
	$entries[$row['arrival']] = $entry;
}
$smarty->assign('entries', $entries);

$years[$current_year-1] = $this->getYearInfo($current_year-1);
$years[$current_year] = $this->getYearInfo($current_year);
$years[$current_year+1] = $this->getYearInfo($current_year+1);
$smarty->assign('years', $years);
$smarty->assign('timestamps', $this->getMonthTimestamps());

$smarty->assign('admindir', $config['root_url'].'/'.$config['admin_dir']);
$smarty->assign('incdir', $config['root_url'].'/modules/JSAvailability/inc/');
$smarty->assign('userkey', $_SESSION[CMS_USER_KEY]);
$smarty->assign('formid', $id);

$smarty->assign('year', $current_year);
$smarty->assign('sundayabbrlabel', substr(strftime('%a', 1298761200), 0, 1));
$smarty->assign('sundaylabel', strftime('%A', 1298761200));
$smarty->assign('append_before', $this->GetPreference('append_months_before', 2));
$smarty->assign('append_after', $this->GetPreference('append_months_after', 2));
$smarty->assign('selectyearlabel', $this->Lang('selectyear'));
$smarty->assign('selectyear', $this->CreateInputDropdown($id, 'y', $this->createYearDropdown(), -1, (string)$current_year, 'id="'.$id.'y"'));

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