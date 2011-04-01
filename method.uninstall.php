<?php
$db =& cmsms()->GetDb();

$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL( cms_db_prefix().'module_jsavailability');
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL( cms_db_prefix().'module_jsavailability_objects');
$dict->ExecuteSQLArray($sqlarray);

$db->Execute('DELETE c, a FROM '.cms_db_prefix().'css c LEFT JOIN '.cms_db_prefix().'css_assoc a ON  c.css_id = a.assoc_css_id WHERE c.css_name = ?', array('JSAvailability'));
$this->RemovePermission('Use JSAvailability');

// put mention into the admin log
$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>