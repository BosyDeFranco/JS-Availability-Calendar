<?php
if(version_compare($oldversion, '0.9') <= 0){
	$db =& cmsms()->GetDb();
	$taboptarray = array('mysql' => 'TYPE=MyISAM');
	$dict = NewDataDictionary($db);

	$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_jsavailability_objects",
		"id I AUTO KEY, name C(255)",
		$taboptarray);
	$dict->ExecuteSQLArray($sqlarray);
	
	$db->Execute('INSERT INTO '.cms_db_prefix().'module_jsavailability_objects (name) VALUES (?)', array($this->Lang('defaultobject')));

	$db->Execute('ALTER TABLE `'.cms_db_prefix().'module_jsavailability` ADD `ref_object` INT NOT NULL');
	$db->Execute('ALTER TABLE `'.cms_db_prefix().'module_jsavailability` CHANGE `arrival` `arrival` TIMESTAMP NOT NULL DEFAULT 0');
	$db->Execute('UPDATE `'.cms_db_prefix().'module_jsavailability` SET ref_object =1');

	$this->CreatePermission('Manage JSAvailability objects', 'Use JSAvailability objects');
}
?>