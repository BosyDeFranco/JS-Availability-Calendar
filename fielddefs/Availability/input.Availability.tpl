<div class="pageoverflow">
	<p class="pagetext">{$fielddef->GetName()}{if $fielddef->IsRequired()}*{/if}:</p>
	<div id="JSAvailability-{$fielddef->GetId()}" class="pageinput">
		
	</div>
</div>
<script>jQuery(function () {
	JSAvailability.init('{$fielddef->GetId()}');
});
</script>