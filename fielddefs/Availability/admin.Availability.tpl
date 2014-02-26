<div class="pageoverflow">
    <p class="pagetext">{$fielddef->ModLang('mode')}:</p>
    <p class="pageinput">
    	{$themeObject->DisplayImage('icons/system/info.gif')}<em> {$fielddef->ModLang('help_mode')}</em><br />
    	{assign var="jsa_mode" value=$fielddef->GetOptionValue('allowed', 'daily')}
    	<select name="{$actionid}custom_input[mode]">
    		<option value="daily"{if $jsa_mode == 'daily'} selected{/if}>{$fielddef->ModLang('daily')}</option>
    		<option value="hourly"{if $jsa_mode == 'hourly'} selected{/if}>{$fielddef->ModLang('hourly')}</option>
    	</select>
	</p>
</div>
<div class="pageoverflow">
    <p class="pagetext">{$fielddef->ModLang('force_interval')}:</p>
    <p class="pageinput">
    	{$themeObject->DisplayImage('icons/system/info.gif')}<em> {$fielddef->ModLang('help_force_interval')}</em><br />
    	{assign var="jsa_interval" value=$fielddef->GetOptionValue('interval')}
    	<select name="{$actionid}custom_input[interval]">
    		<option value="30m"{if $jsa_interval == '30m'} selected{/if}>30m</option>
    		<option value="1h"{if $jsa_interval == '1h'} selected{/if}>1h</option>
    	</select>
	</p>
</div>
<div class="pageoverflow">
    <p class="pagetext">{$fielddef->ModLang('block_slots')}:</p>
    <p class="pageinput">
    	{$themeObject->DisplayImage('icons/system/info.gif')}<em> {$fielddef->ModLang('help_block_slots')}</em><br />
    	{assign var="jsa_block" value=$fielddef->GetOptionValue('block')}
    	<select name="{$actionid}custom_input[block]">
    		<option value="off"{if $jsa_block == 'off'} selected{/if}>{$fielddef->ModLang('off')}</option>
    		<option value="recurrent"{if $jsa_block == 'recurrent'} selected{/if}>{$fielddef->ModLang('recurrent')}</option>
    		<option value="once"{if $jsa_block == 'once'} selected{/if}>{$fielddef->ModLang('once')}</option>
    	</select>
	</p>
</div>