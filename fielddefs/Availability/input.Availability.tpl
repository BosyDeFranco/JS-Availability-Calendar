<div class="pageoverflow">
	<p class="pagetext">{$fielddef->GetName()}{if $fielddef->IsRequired()}*{/if}:</p>
	<div id="JSAvailability-{$fielddef->GetId()}" class="pageinput jsavailability">
		<div class="previous-button">&lsaquo;</div>
		<div class="calendar-month"></div>
		<div class="calendar-month"></div>
		<div class="calendar-month">
			<script id="JSAvailability-{$fielddef->GetId()}-template" type="text/template">
			<div class="controls">
				<div class="month"><%= month %></div>
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
						<% if(n % 7 == 0 && n > 0) { %>
						</div><div class="row">
						<% } %>
						<div class="<%= day.classes %>" id="<%= day.id %>"><%= day.day %></div>
					<% }); %>
					</div>
				</div>
			</div>
			</script>
		</div>
		<div class="next-button">&rsaquo;</div>
	</div>
</div>
<script>jQuery(function () {
	JSAvailability.init('{$fielddef->GetId()}', '{$cms_lang}');
});
</script>