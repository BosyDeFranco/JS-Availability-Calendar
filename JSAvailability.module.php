<?php
/**
 * JSAvailability
 *
 * @author Jonathan Schmid
 * TODO:
 * - icons in notifications
 * - loading hint
 * - correct cursors
 * - database-driven fe template
 * @license GPL
 **/
class JSAvailability extends CMSModule
{
	var $yearinfo;
	var $timestamps;

	function __construct(){
		parent::__construct();

		setlocale(LC_ALL, cmsms()->siteprefs['frontendlang']);
		$smarty = cmsms()->GetSmarty();
		$smarty->register_modifier('js_str_pad', array($this, 'smarty_modifier_str_pad'));
	}
	function GetName(){
		return 'JSAvailability';
	}
	function GetFriendlyName(){
		return $this->Lang('friendlyname');
	}
	function GetVersion(){
		return '0.9.1';
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
	function GetDependencies(){
		return array();
	}
	function MinimumCMSVersion(){
		return "1.9.4.2";
	}
	function MaximumCMSVersion(){
		return "1.10";
	}
	function SetParameters(){
		$this->RegisterModulePlugin();
		$this->RestrictUnknownParams();
		$this->CreateParameter('object_id', 1, $this->Lang('help_object_id'));
		$this->SetParameterType('object_id', CLEAN_INT);
		$this->CreateParameter('year', date('Y'), $this->Lang('help_year'));
		$this->SetParameterType('year', CLEAN_INT);
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
	function setCurrentObject($object){
		if(!preg_match('#^[0-9]+$#', $object))
			return false;
		$this->SetPreference('current_object', $object);
		return true;
	}
	function postPeriod($arrival, $departure, $type){
		$arrival = strtotime($arrival)+100;
		$departure = strtotime($departure)+100;
		if(!$arrival || !$departure || $departure <= $arrival)
			return $this->Lang('wrongdateformat');

		$db =& cmsms()->GetDb();
		// does this work in all cases?
		$query = 'SELECT id FROM `'.cms_db_prefix().'module_jsavailability` WHERE UNIX_TIMESTAMP(arrival) > ? AND UNIX_TIMESTAMP(arrival) < ? AND ref_object = ?';
		$dbresult = $db->Execute($query, array($arrival, $departure, $this->GetPreference('current_object', 1)));
		if($dbresult->NumRows() > 0)
			return $this->Lang('overlap');

		$types = array('reservation' => 1, 'booking' => 2);
		$query = 'INSERT INTO '.cms_db_prefix().'module_jsavailability (type, arrival, departure, ref_object) VALUES (?, FROM_UNIXTIME(?), FROM_UNIXTIME(?), ?)';
		$db->Execute($query, array($types[$type], $arrival, $departure, $this->GetPreference('current_object', 1)));
		return $this->Lang('saved');
	}
	function deletePeriod($date){
		$date = strtotime($date)+100;
		if(!$date)
			return $this->Lang('wrongdateformat');

		$db =& cmsms()->GetDb();
		$query = 'DELETE FROM '.cms_db_prefix().'module_jsavailability WHERE UNIX_TIMESTAMP(arrival) <= ? AND UNIX_TIMESTAMP(departure) >= ? AND ref_object = ?';
		$db->Execute($query, array($date, $date, $this->GetPreference('current_object', 1)));
	}
	function smarty_modifier_str_pad($string, $length, $pad_string='', $pad_type='left'){
		$pads = array('left'=>STR_PAD_LEFT, 'right'=>STR_PAD_RIGHT, 'both'=>STR_PAD_BOTH);
		if(array_key_exists($pad_type, $pads))
			return str_pad($string, $length ,$pad_string,$pads[$pad_type]);
	}
	function getEntries($object, $year){
		$db =& cmsms()->GetDb();

		$query = 'SELECT type, DATE(arrival) as arrival, DATE(departure) as departure FROM '.cms_db_prefix().'module_jsavailability WHERE ref_object = ? AND UNIX_TIMESTAMP(arrival) > UNIX_TIMESTAMP(01-01-?) AND UNIX_TIMESTAMP(departure) > UNIX_TIMESTAMP(31-12-?)';

		$dbresult = $db->Execute($query, array($object, $year-1, $year+1));
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
	function smartyConfig($admin = false, $year = -1, $object = -1){
		$config = cmsms()->GetConfig();
		$smarty = cmsms()->GetSmarty();

		$append_start = $this->GetPreference('append_months_before', 2);;
		$append_end = $this->GetPreference('append_months_after', 2);
		$current_year = $admin ? $this->GetPreference('current_year', date('Y')) : ($year?$year:date('Y'));
		$current_object = $admin ? $this->GetPreference('current_object', 1) : $object;

		$smarty->assign('entries', $this->getEntries($current_object, $current_year));

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
			$smarty->assign('selectyear', $this->CreateInputDropdown('', 'y', $this->createYearDropdown(), -1, (string)$current_year, 'id="y"'));
			$smarty->assign('selectobjectlabel', $this->Lang('selectobject'));
			$smarty->assign('selectobject', $this->CreateInputDropdown('', 'o', $this->createObjectsDropdown(), -1, (string)$current_object, 'id="o"'));

			$smarty->assign('admindir', $config['root_url'].'/'.$config['admin_dir']);
			$smarty->assign('userkey', $_SESSION[CMS_USER_KEY]);
			$smarty->assign('selectyearlabel', $this->Lang('selectyear'));
			$smarty->assign('selectyear', $this->CreateInputDropdown('', 'y', $this->createYearDropdown(), -1, (string)$current_year, 'id="y"'));
		}
	}
	function createObjectsDropdown(){
		$db = cmsms()->GetDb();
		$dbresult = $db->Execute('SELECT id, name FROM '.cms_db_prefix().'module_jsavailability_objects');
		$dropdown = array();
		while($dbresult && $row = $dbresult->FetchRow())
			$dropdown[$row['id'].': '.$row['name']] = $row['id'];
		return $dropdown;
	}
	function saveObject($id, $name){
		$db = cmsms()->GetDb();
		$db->Execute('UPDATE '.cms_db_prefix().'module_jsavailability_objects SET name = ? WHERE id = ?', array($name, $id));
	}
	function addObject(){
		$db = cmsms()->GetDb();
		$db->Execute('INSERT INTO '.cms_db_prefix().'module_jsavailability_objects (name) VALUES (?)', array($this->Lang('newobject')));
	}
	function deleteObject($object){
		$db = cmsms()->GetDb();
		$db->Execute('DELETE FROM '.cms_db_prefix().'module_jsavailability_objects WHERE id = ?', array($object));
	}
	function DisplayImage($imageName, $alt='', $width='', $height='', $class=''){
		$admintheme =& cmsms()->variables['admintheme'];
		if(!is_object($admintheme))
			$admintheme = new AdminTheme(cmsms(), cmsms()->variables['user_id'], cmsms()->userprefs['admintheme']);
		return $admintheme->DisplayImage($imageName, $alt, $width, $height, $class);
	}
}
?>