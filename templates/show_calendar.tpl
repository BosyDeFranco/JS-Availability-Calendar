<div id="reloadArea">
<script type="text/javascript">
<!--
var CMS_ADMIN_DIR = '{$admindir}';
var CMS_USER_KEY = '{$userkey}';
var CMS_FORM_ID = '{$formid}';
-->
</script>
<script type="text/javascript" src="{$incdir}availability.js"></script>
<link rel="stylesheet" type="text/css" href="{$incdir}styles.css" />
<p>{$selectyearlabel}: {$selectyear}</p>
<table>
	<tr class="day_names">
		<td>&nbsp;</td>
	{section name="day" start=1 loop=32}
		<td>{$smarty.section.day.index}</td>
	{/section}
	</tr>
	{section name="month" start=1 loop=$append_before+$append_after+13}
	{assign var="month" value=$smarty.section.month.index}
		<tr{if $month <= $append_before} class="append_before"{elseif $month > $append_before+12} class="append_after"{/if}>
	{if $month <= $append_before}{assign var="currentyear" value=$year-1}{elseif $month > $append_before+12}{assign var="currentyear" value=$year+1}{else}{assign var="currentyear" value=$year}{/if}
	{if $month <= $append_before}{assign var="month" value=$month+12-$append_before}{elseif $month > $append_before+12}{assign var="month" value=$month-12-$append_before}{else}{assign var="month" value=$month-$append_before}{/if}
		{section name="day" loop=$years[$currentyear][$month].length+1}
			{capture assign="id"}{$currentyear}-{$month|js_str_pad:2:'0':'left'}-{$smarty.section.day.index|js_str_pad:2:'0':'left'}{/capture}
			{if $entries[$id]}{assign var="just_started" value=$id}{/if}
			{if $smarty.section.day.index == 0}
			<td class="month_name">{$timestamps[$month]|date_format:"%B"|escape:"htmlall"}</td>
			{else}
			<td class="{if $started == '' && $just_started == ''}f{else}{if $entries[$started]->type == 1 || $entries[$just_started]->type == 1}reservation{/if}{if $entries[$started]->type == 2 || $entries[$just_started]->type == 2} booking{/if}{/if}{if $just_started == $id} arrival{/if}{if $entries[$started]->departure == $id} departure{/if}{if $smarty.section.day.index % 2 == 0} odd{/if}" id="{$id}">{if $years[$currentyear][$month].sundays[$smarty.section.day.index]}{$sundayabbrlabel}{/if}</td>
			{/if}
			{if $started != '' && $entries[$started]->departure == $id}{assign var="started" value=""}{elseif $just_started != ''}{assign var="started" value=$just_started}{assign var="just_started" value=""}{/if}
		{/section}
		</tr>
	{/section}
</table>
<p>&nbsp;</p>
<p>{$sundayabbrlabel} = {$sundaylabel}</p>
<table>
	<tr>
		<td id="arrival-ctrl" class="control-1">Ankunft</td>
		<td id="departure-ctrl" class="control-1">Abfahrt</td>
		<td id="delete-ctrl" class="control-1">Entfernen</td>
		<td class="separator-2">&nbsp;</td>
		<td id="reservation-ctrl" class="control-2">Reservation</td>
		<td id="booking-ctrl" class="control-2 active">Buchung</td>
	</tr>
</table>
</div>