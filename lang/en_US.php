<?php
$lang['module_friendlyname'] = 'JSAvailability';
$lang['module_description'] = 'ListItExtended Cross module / Availability field definition';
$lang['occupancies'] = '%u days occupied';
$lang['available'] = 'Available';
$lang['unavailable'] = 'Unavailable';

$lang['mode'] = 'Mode';
$lang['daily'] = 'Day mode';
$lang['hourly'] = 'Hour mode';
$lang['help_mode'] = 'Allows to set availability slots to either days or hours';

$lang['force_interval'] = 'Force interval';
$lang['help_force_interval'] = 'Set fixed intervals so availability slots can only fill a multiple of these.';

$lang['block_slots'] = 'Block slots';
$lang['off'] = 'Off';
$lang['recurrent'] = 'Recurrent';
$lang['once'] = 'Once only';
$lang['help_block_slots'] = 'Enables to show certain slots as blocked. These can optionally be recurrent each week (in day mode) or each month (in hour mode)';

$lang['general'] = 'General';
$lang['about'] = 'About';
$lang['team'] = 'Team';
$lang['help_general'] = '<h3>General Info</h3>      
    <p>JSAvailability provides a field type for the <a href="http://dev.cmsmadesimple.org/projects/listit2" target="_blank">ListItExtended module</a> to allow the management of availability of items.</p>
    <h3>ListIt Template</h3>
    <p>To output the calendar on the ListIt detail page simply insert this to the end of your detail template and replace <em>fielddef_alias</em> by the alias of your field definition:</p>';

$lang['help_about'] = <<<EOT
	<h3>About</h3>
    <p>If you find any bugs please feel free to submit a bug report <a href="http://dev.cmsmadesimple.org/bug/list/982" target="_blank">here</a> or for any good ideas consider submiting a feature request <a href="http://dev.cmsmadesimple.org/feature_request/list/982" target="_blank">here</a>. </p>
    <p>Please keep in mind that developers do have their daily jobs which means that feature requests are considered and done as time allows. If you need a feature really badly consider contacting one of the developers for a sponsored development.
    </p>
EOT;

$lang['help_param_field'] = 'Pass the ListItExtened field you want to display in front-end.';

$lang['fielddef_Availability'] = 'Availability Calendar';
?>