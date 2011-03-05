<link rel="stylesheet" type="text/css" href="{$incdir}styles.css" />
<h1>{$year}</h1>
<table id="calendar">
	<tr class="day_names">
		<td>&nbsp;</td>
	{section name="day" start=1 loop=32}
		<td>{$smarty.section.day.index}</td>
	{/section}
	</tr>
	{section name="month" start=1 loop=13}
		<tr>
		{section name="day" loop=$years[$year][$smarty.section.month.index].length+1}
			{if $smarty.section.day.index == 0}
			<td class="month_name">{$timestamps[$smarty.section.month.index]|date_format:"%B"|escape:"htmlall"}</td>
			{else}
			<td class="f">{if $years[$year][$smarty.section.month.index].sundays[$smarty.section.day.index]}{$sundayabbrlabel}{/if}</td>
			{/if}
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