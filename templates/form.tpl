{$startform}
{foreach from=$fields item=field}
<div class="pageoverflow">
		<p class="pagetext">{$field.text}:</p>
		<p class="pageinput">{$field.input}{if $field.link} {$field.link}{/if}</p>
</div>
{/foreach}
<div class="pageoverflow">
	<p class="pagetext">&nbsp;</p>
	<p class="pageinput">{$submit}{$cancel}</p>
</div>

{$hidden}
{$endform}