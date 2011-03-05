<?php
$db =& cmsms()->GetDb();

$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL( cms_db_prefix().'module_jsavailability');
$dict->ExecuteSQLArray($sqlarray);

$this->RemovePermission('Use JSAvailability');

// put mention into the admin log
$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>