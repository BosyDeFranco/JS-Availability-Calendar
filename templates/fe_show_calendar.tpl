{assign var="n" value=1}
<table id="calendar">
	<tr class="separator">
	<td colspan="32">{$year-1}</td>
	</tr>
	<tr class="day_names">
	{section name="day loop=32}
		{if $smarty.section.day.index == 0}
		<td class="month_name">&nbsp;</td>
		{else}
		<td width="15">{$smarty.section.day.index}</td>
		{/if}
	{/section}
	</tr>

{foreach name=month from=$months_append_start item=month}
	<tr class="append_start">
		{assign var="index" value=$smarty.foreach.month.index}
		{php}global $gCms;$index = $gCms->smarty->get_template_vars('index');$gCms->smarty->assign('index', 11-$months_append_start+$index);{/php}
		<td class="month_name">{$month_names.$index|truncate:3:'':true}</td>
		{section name=day loop=31}
		{assign var="datey" value=$year-1}
		{assign var="datem" value=$index|str_pad:2:"0"}
		{assign var="dated" value=$smarty.section.day.index+1}
		{assign var="date" value="$datey-$datem-$dated"}
		{assign var="valid_date" value="$datey-$datem-$dated"}
		<td class="{if $guests.$date}{if $guests.$date.type == 1}b{else}r{/if}{elseif $smarty.section.day.index < $month}f {$index}-{$smarty.section.day.index+1}-{$year-1}{else}n{/if}" id="d{$n}">{if $valid_date|date_format:"%u" == 7}S{else}&nbsp;{/if}</td>
		{assign var="n" value=$n+1}
		{/section}
	</tr>
{/foreach}

	<tr class="separator">
	<td colspan="32">{$year}</td>
	</tr>

	<tr class="day_names">
	{section name="day loop=32}
		{if $smarty.section.day.index == 0}
		<td class="month_name">&nbsp;</td>
		{else}
		<td width="15">{$smarty.section.day.index}</td>
		{/if}
	{/section}
	</tr>

{foreach name=month from=$months item=month}
	<tr>
	{assign var="index" value=$smarty.foreach.month.index+1}
	<td class="month_name" width="62">{$month_names.$index|truncate:3:'':true}</td>
	{section name=day loop=31}
		{assign var="datem" value=$index|str_pad:2:"0"}
		{assign var="dated" value=$smarty.section.day.index+1|str_pad:2:"0"}
		{assign var="date" value="$year$datem$dated"}
		{assign var="valid_date" value="$year-$datem-$dated"}
		{if $guest_started == '' || $guest_started == $date}
			{if $guest_started == $date}
				{assign var="guest_just_ended_type" value=$guest_started_type}
				{assign var="guest_just_ended" value=1}
			{/if}
			{if $guests.$date}
				{assign var="guest_started" value=$guests.$date.end}
				{assign var="guest_started_type" value=$guests.$date.type}
				{assign var="guest_just_started" value=1}
			{/if}
		{/if}
		<td class="{if $guest_started}{if $guest_just_started}arrival-{/if}{if $guest_started_type == 2}b{else}r{/if}{if $guest_just_ended} departure-{if $guest_just_ended_type == 2}b{else}r{/if}{/if}{elseif $smarty.section.day.index < $month}f {$month_names.$index}-{$smarty.section.day.index+1}-{$year}{else}n{/if}" id="d{$n}">{if $valid_date|date_format:"%u" == 7}S{else}&nbsp;{/if}</td>
		{if $guest_started == $date}{assign var="guest_started" value=''}{/if}
		{assign var="guest_just_started" value=''}
		{assign var="guest_just_ended" value=''}
		{assign var="n" value=$n+1}
		{/section}
	</tr>
{/foreach}
	<tr class="separator">
	<td colspan="32">{$year+1}</td>
	</tr>

	<tr class="day_names">
	{section name="day loop=32}
		{if $smarty.section.day.index == 0}
		<td class="month_name">&nbsp;</td>
		{else}
		<td width="15">{$smarty.section.day.index}</td>
		{/if}
	{/section}
	</tr>

{foreach name=month from=$months_append_end item=month}
	<tr class="append_end">
	{assign var="index" value=$smarty.foreach.month.index+1}
	<td class="month_name">{$month_names.$index|truncate:3:'':true}</td>
	{section name=day loop=31}
	{assign var="datey" value=$year+1}
	{assign var="datem" value=$index|str_pad:2:"0"}
	{assign var="dated" value=$smarty.section.day.index+1|str_pad:2:"0"}
	{assign var="date" value="$datey$datem$dated"}
	{assign var="valid_date" value="$datey-$datem-$dated"}
	{if $guest_started == '' || $guest_started == $date}
		{if $guest_started == $date}
				{assign var="guest_just_ended_type" value=$guest_started_type}
				{assign var="guest_just_ended" value=1}
			{/if}
			{if $guests.$date}
				{assign var="guest_started" value=$guests.$date.end}
				{assign var="guest_started_type" value=$guests.$date.type}
				{assign var="guest_just_started" value=1}
			{/if}
	{/if}
	<td class="{if $guest_started}{if $guest_just_started}arrival-{/if}{if $guest_started_type == 2}b{else}r{/if}{if $guest_just_ended} departure-{if $guest_just_ended_type == 2}b{else}r{/if}{/if}{elseif $smarty.section.day.index < $month}f{else}n{/if} {$month_names.$index}-{$smarty.section.day.index+1}-{$datey}" id="d{$n}">{if $valid_date|date_format:"%u" == 7}S{else}&nbsp;{/if}</td>
	{if $guest_started == $date}{assign var="guest_started" value=''}{/if}
	{assign var="guest_just_started" value=''}
	{assign var="guest_just_ended" value=''}
	{assign var="n" value=$n+1}
	{/section}
	</tr>
{/foreach}
</table>
<p>&nbsp;</p>
<table id="calendar_legend">
<tr>
	<td width="62">Legende</td>
	<td class="f">&nbsp;</td>
	<td>Frei</td>
	<td>&nbsp;</td>
	<td class="r">&nbsp;</td>
	<td>Reserviert</td>
	<td>&nbsp;</td>
	<td class="b">&nbsp;</td>
	<td>Belegt</td>
</tr>
</table>