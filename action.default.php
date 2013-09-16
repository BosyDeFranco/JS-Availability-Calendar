<?php
if(get_class($params['field']) !== 'listit2fd_Availability') {
	return false;
}

$smarty = cmsms()->GetSmarty();
$smarty->assign('field', $params['field']);

echo $this->ProcessTemplate('frontend.tpl');
?>