<script type="text/javascript">
<!--
var CMS_ADMIN_DIR = '{$admindir}';
var CMS_USER_KEY = '{$userkey}';
var CMS_INC_DIR = '{$incdir}';
var CMS_SAVE_ICON = '{$saveicon}';
-->
</script>
<script type="text/javascript" src="{$incdir}ajax.js"></script>
<link rel="stylesheet" type="text/css" href="{$incdir}imagecompressor.css" />
<table width="100%" class="pagetable" cellspacing="0">
	<thead>
	<tr>
		<th class="pageicon" style="padding-right:50px;">{$idtext}</th>
		<th>{$objectnametext}</th>
		<th class="pageicon">&nbsp;</th>
		<th class="pageicon">&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$objects item=object name="objects"}
	<tr class="row{if $smarty.foreach.objects.iteration % 2}1{else}2{/if}">
		<td valign="middle">{$object->id}</td>
		<td valign="middle" class="objectname">{$object->name}</td>
		<td valign="middle">{$object->editlink}</td>
		<td valign="middle">{$object->deletelink}</td>
	</tr>
	{/foreach}
	</tbody>
</table>