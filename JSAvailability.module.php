<?php
/**
 * JSAvailability
 *
 * @author Jonathan Schmid
 * @modifiedby $LastChangedBy: foaly* $
 * @lastmodified $Date: 2011-03-13 22:42 +0200 $
 * TODO:
 * - icons
 * - loading hint
 * - proof overlap check
 * - correct cursors
 * - stylesheet bei Deinstallation löschen
 * @license GPL
 **/
class JSAvailability extends CMSModule
{
	var $yearinfo;
	var $timestamps;

	function JSAvailability(){
		parent::CMSModule();
		setlocale(LC_ALL, cmsms()->siteprefs['frontendlang']);
		$smarty =& cmsms()->GetSmarty();
		$smarty->register_modifier('js_str_pad', array($this, 'smarty_modifier_str_pad'));
	}
	function GetName(){
		return 'JSAvailability';
	}
	function GetFriendlyName(){
		return $this->Lang('friendlyname');
	}
	function GetVersion(){
		return '0.9';
	}
	function GetHelp(){
		return $this->Lang('help');
	}
	function GetAuthor(){
		return 'JS';
	}
	function GetAuthorEmail(){
		return 'hi@jonathanschmid.de';
	}
	function GetChangeLog(){
		return $this->Lang('changelog');
	}
	function IsPluginModule(){
		return true;
	}
	function HasAdmin(){
		return true;
	}
	function GetAdminSection(){
		return 'content';
	}
	function GetAdminDescription(){
		return $this->Lang('moddescription');
	}
	function VisibleToAdminUser(){
		return $this->CheckPermission('Use JSAvailability');
	}
	function GetDashboardOutput() {
		return false;
	}
	function GetNotificationOutput($priority=2) {
		return '';
	}
	function GetDependencies(){
		return array();
	}
	function MinimumCMSVersion(){
		return "1.9";
	}
	function SetParameters(){
		$this->RegisterModulePlugin();
		$this->RestrictUnknownParams();
	}
	function InstallPostMessage(){
		return $this->Lang('postinstall');
	}
	function UninstallPostMessage(){
		return $this->Lang('postuninstall');
	}
	function UninstallPreMessage(){
		return $this->Lang('really_uninstall');
	}
	function getYearInfo($year){
		if(!is_array($this->yearinfo))
			for($n = 1; $n <= 12; $n++){
				$this->yearinfo[$n]['length'] = date("t", mktime(0, 0, 0, $n, 1, $year));
				for($x = 1; $x <= $this->yearinfo[$n]['length']; $x++){
					if(date("N", mktime(0, 0, 0, $n, $x, $year)) == 6)
						$this->yearinfo[$n]['sundays'][$x] = true;
				}
			}
		return $this->yearinfo;
	}
	function getMonthTimestamps(){
		if(!is_array($this->timestamps)){
			$timestamp = 1;
			for($n = 1; $n <= 12; $n++){
				$this->timestamps[$n] = $timestamp;
				$timestamp += 60 * 60 * 24 * $this->yearinfo[$n]['length'];
			}
		}
		return $this->timestamps;
	}
	function createYearDropdown(){
		$year = date('Y');
		$years = array();
		for($n = -1; $n <= 5; $n++)
			$years[$year+$n] = $year+$n;
		return $years;
	}
	function setCurrentYear($year){
		if(!preg_match('#^[0-9]{4}$#', $year))
			return false;
		$this->SetPreference('current_year', $year);
		return true;
	}
	function postPeriod($arrival, $departure, $type){
		$arrival = strtotime($arrival)+100;
		$departure = strtotime($departure)+100;
		if(!$arrival || !$departure || $departure <= $arrival)
			return $this->Lang('wrongdateformat');

		$db =& cmsms()->GetDb();
		// does this work in all cases?
		$query = 'SELECT id FROM `'.cms_db_prefix().'module_jsavailability` WHERE UNIX_TIMESTAMP(arrival) > ? AND UNIX_TIMESTAMP(arrival) < ?';
		$dbresult = $db->Execute($query, array($arrival, $departure));
		if($dbresult->NumRows() > 0)
			return $this->Lang('overlap');

		$types = array('reservation' => 1, 'booking' => 2);
		$query = 'INSERT INTO '.cms_db_prefix().'module_jsavailability (type, arrival, departure) VALUES (?, FROM_UNIXTIME(?), FROM_UNIXTIME(?))';
		$db->Execute($query, array($types[$type], $arrival, $departure));
		return $this->Lang('saved');
	}
	function deletePeriod($date){
		$date = strtotime($date)+100;
		if(!$date)
			return $this->Lang('wrongdateformat');

		$db =& cmsms()->GetDb();
		$query = 'DELETE FROM '.cms_db_prefix().'module_jsavailability WHERE UNIX_TIMESTAMP(arrival) <= ? AND UNIX_TIMESTAMP(departure) >= ?';
		$db->Execute($query, array($date, $date));
	}
	function smarty_modifier_str_pad($string, $length, $pad_string='', $pad_type='left'){
		$pads = array('left'=>STR_PAD_LEFT, 'right'=>STR_PAD_RIGHT, 'both'=>STR_PAD_BOTH);
		if(array_key_exists($pad_type, $pads))
			return str_pad($string, $length ,$pad_string,$pads[$pad_type]);
	}
	function getEntries(){
		$db =& cmsms()->GetDb();

		$query = 'SELECT type, DATE(arrival) as arrival, DATE(departure) as departure FROM '.cms_db_prefix().'module_jsavailability ORDER BY id DESC';

		$dbresult = $db->Execute($query);
		$entries = array();
		while ($dbresult && $row = $dbresult->FetchRow()){
			$entry = new stdClass();
			$entry->arrival = $row['arrival'];
			$entry->departure = $row['departure'];
			$entry->type = $row['type'];
			$entries[$row['arrival']] = $entry;
		}
		return $entries;
	}
	function smartyConfig($admin = false){
		$config = cmsms()->GetConfig();
		$smarty = cmsms()->GetSmarty();

		$append_start = $this->GetPreference('append_months_before', 2);;
		$append_end = $this->GetPreference('append_months_after', 2);
		$current_year = $this->GetPreference('current_year', date('Y'));

		$smarty->assign('entries', $this->getEntries());

		$years[$current_year-1] = $this->getYearInfo($current_year-1);
		$years[$current_year] = $this->getYearInfo($current_year);
		$years[$current_year+1] = $this->getYearInfo($current_year+1);
		$smarty->assign('years', $years);
		$smarty->assign('timestamps', $this->getMonthTimestamps());

		$smarty->assign('year', $current_year);
		$smarty->assign('sundayabbrlabel', substr(strftime('%a', 1298761200), 0, 1));
		$smarty->assign('sundaylabel', strftime('%A', 1298761200));
		$smarty->assign('append_before', $this->GetPreference('append_months_before', 2));
		$smarty->assign('append_after', $this->GetPreference('append_months_after', 2));

		$smarty->assign('incdir', $config['root_url'].'/modules/JSAvailability/inc/');

		if($admin){
			$smarty->assign('selectyearlabel', $this->Lang('selectyear'));
			$smarty->assign('selectyear', $this->CreateInputDropdown($id, 'y', $this->createYearDropdown(), -1, (string)$current_year, 'id="'.$id.'y"'));

			$smarty->assign('admindir', $config['root_url'].'/'.$config['admin_dir']);
			$smarty->assign('userkey', $_SESSION[CMS_USER_KEY]);
			$smarty->assign('formid', $id);
			$smarty->assign('selectyearlabel', $this->Lang('selectyear'));
			$smarty->assign('selectyear', $this->CreateInputDropdown($id, 'y', $this->createYearDropdown(), -1, (string)$current_year, 'id="'.$id.'y"'));
		}
	}
}
?>