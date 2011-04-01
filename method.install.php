<?php
$db =& cmsms()->GetDb();
$taboptarray = array( 'mysql' => 'TYPE=MyISAM' );
$dict = NewDataDictionary( $db );
			
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_jsavailability",
	"id I AUTO KEY, type I, arrival TS, departure TS, ref_object I",
	$taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_jsavailability_objects",
	"id I AUTO KEY, name C(255)",
	$taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$db->Execute('INSERT INTO '.cms_db_prefix().'module_jsavailability_objects (name) VALUES (?)', array($this->Lang('defaultobject')));

// insert fe css
$config = cmsms()->GetConfig();
$css = str_replace('url(../', 'url('.$config['root_url'].'/modules/JSAvailability/', file_get_contents(cms_join_path(dirname(__FILE__), 'inc','styles.css')));
$css_id = $db->GenID(cms_db_prefix().'css_seq');
$db->Execute('insert into '.cms_db_prefix().'css (css_id, css_name, css_text, media_type, create_date) values (?,?,?,?,?)', array($css_id, 'JSAvailability', $css, 'screen', date('Y-m-d')));

$this->CreatePermission('Use JSAvailability', 'Use JSAvailability');
$this->CreatePermission('Manage JSAvailability objects', 'Use JSAvailability objects');

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );
?>