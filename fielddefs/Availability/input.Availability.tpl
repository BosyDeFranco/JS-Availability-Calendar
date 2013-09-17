<div class="pageoverflow">
	<p class="pagetext">{$fielddef->GetName()}{if $fielddef->IsRequired()}*{/if}:</p>
	<div id="jsavailability-{$fielddef->GetId()}" class="pageinput jsavailability">
		<div class="previous-button">&lsaquo;</div>
		<div class="calendar-month"></div>
		<div class="calendar-month"></div>
		<div class="calendar-month">
			<script id="jsavailability-{$fielddef->GetId()}-template" type="text/template">
			<div class="controls">
				<div class="month"><%= month %> <%= year %></div>
			</div>
			<div class="days-container">
				<div class="days">
					<div class="headers">
						<% _.each(daysOfTheWeek, function(day) { %>
							<div class="day-header"><%= day %></div>
						<% }); %>
					</div>
					<div class="row">
					<% _.each(days, function(day, n) { %>
					<%
						var extraClasses = '';
						if((day.events.length > 0 && day.events[0].isStart) || (day.events[1] !== undefined && day.events[1].isStart)) {
							extraClasses += ' event-start';
						}
						if((day.events.length > 0 && day.events[0].isEnd) || (day.events[1] !== undefined && day.events[1].isEnd)) {
							extraClasses += ' event-end';
						}
					%>
						<% if(n % 7 == 0 && n > 0) { %>
						</div><div class="row">
						<% } %>
						<div
							class="<%= day.classes %><%= extraClasses %>"
							id="<%= day.id %>"
						>
							<%= day.day %>
						</div>
					<% }); %>
					</div>
				</div>
			</div>
			</script>
		</div>
		<div class="next-button">&rsaquo;</div>
		{foreach from=$events item=event}
		<input type="hidden" name="{$actionid}customfield[{$fielddef->GetId()}][]" value="{$event|escape:'htmlall'}" />
		{/foreach}
	</div>
</div>
<script>jQuery(function () {
	JSAvailability.backend('{$fielddef->GetId()}', '{$actionid}', '{$cms_lang}');
});
</script>