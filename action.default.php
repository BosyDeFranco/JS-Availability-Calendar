<?php
if(get_class($params['field']) !== 'listit2fd_Availability') {
	return false;
}

$smarty = cmsms()->GetSmarty();
$smarty->assign('field', $params['field']);
$smarty->assign('mod', $this);

$values = $params['field']->GetValue(listit2fd_Availability::TYPE_ARRAY);
// TODO: make this working with array_walk
foreach($values as $key => $value)
{
	$values[$key] = json_decode($value);
}
$smarty->assign('value', $values);

echo $this->ProcessTemplate('frontend.tpl');
?>