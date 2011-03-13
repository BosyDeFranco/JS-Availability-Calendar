<?php
$db =& cmsms()->GetDb();

$taboptarray = array( 'mysql' => 'TYPE=MyISAM' );

$dict = NewDataDictionary( $db );
			
$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_jsavailability",
					"id I AUTO KEY, type I, arrival TS, departure TS",
				   $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$this->CreatePermission('Use JSAvailability', 'Use JSAvailability');

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );
?>