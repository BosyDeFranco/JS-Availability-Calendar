<?php
$db =& cmsms()->GetDb();

$taboptarray = array( 'mysql' => 'TYPE=MyISAM' );

$dict = NewDataDictionary( $db );
			
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_jsavailability",
					"id I AUTO KEY, type I, arrival TS, departure TS",
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

// insert fe css
$config = cmsms()->GetConfig();
$css = str_replace('url(../', 'url('.$config['root_url'].'/modules/JSAvailability', file_get_contents(cms_join_path(dirname(__FILE__), 'inc','styles.css')));
$css_id = $db->GenID(cms_db_prefix().'css_seq');
$db->Execute('insert into '.cms_db_prefix().'css (css_id, css_name, css_text, media_type, create_date) values (?,?,?,?,?)', array($css_id, 'JSAvailability', $css, 'screen', date('Y-m-d')));

$this->CreatePermission('Use JSAvailability', 'Use JSAvailability');

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );
?>