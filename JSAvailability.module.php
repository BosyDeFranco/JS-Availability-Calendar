<?php
/**
 * JSAvailability
 *
 * @author Jonathan Schmid
 * @modifiedby $LastChangedBy: foaly* $
 * @lastmodified $Date: 2011-03-13 22:42 +0200 $
 * TODO:
 * - correct year for append months
 * - show saved entries
 * - icons
 * - loading hint
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
		$arrival = strtotime($arrival);
		$departure = strtotime($departure);
		if(!$arrival || !$departure || $departure <= $arrival)
			return $this->Lang('wrongdateformat');

		$db =& cmsms()->GetDb();
		$query = 'SELECT id FROM `'.cms_db_prefix().'module_jsavailability` WHERE UNIX_TIMESTAMP(arrival) > ? AND UNIX_TIMESTAMP(arrival) < ?';
		$dbresult = $db->Execute($query, array($arrival, $departure));
		if($dbresult->NumRows() > 0)
			return $this->Lang('overlap');

		$types = array('reservation' => 1, 'booking' => 2);
		$query = 'INSERT INTO '.cms_db_prefix().'module_jsavailability (type, arrival, departure) VALUES (?, FROM_UNIXTIME(?), FROM_UNIXTIME(?))';
		$db->Execute($query, array($types[$type], $arrival, $departure));
		return $this->Lang('saved');
	}
	function smarty_modifier_str_pad($string, $length, $pad_string='', $pad_type='left'){
		$pads = array('left'=>STR_PAD_LEFT, 'right'=>STR_PAD_RIGHT, 'both'=>STR_PAD_BOTH);
		if(array_key_exists($pad_type, $pads))
			return str_pad($string, $length ,$pad_string,$pads[$pad_type]);
	}
}
?>