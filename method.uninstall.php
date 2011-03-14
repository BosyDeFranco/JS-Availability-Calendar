<?php
$db =& cmsms()->GetDb();

$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL( cms_db_prefix().'module_jsavailability');
$dict->ExecuteSQLArray($sqlarray);

$db->Execute('DELETE FROM '.cms_db_prefix().'css WHERE css_name = ?', array('JSAvailability'));

$this->RemovePermission('Use JSAvailability');

// put mention into the admin log
$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>