<link type="text/css" rel="stylesheet" href="{$field->getURLPath()}/listit2fd-availability.css" />
<div id="jsavailability-{$field->GetId()}" class="jsavailability">
	<div class="previous-button">&lsaquo;</div>
	<div class="calendar-month"></div>
	<div class="calendar-month"></div>
	<div class="calendar-month">
		<script id="jsavailability-{$field->GetId()}-template" type="text/template">
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
</div>
<div class="jsavailability-legend">
	<div class="day">31</div><span>{$mod->Lang('available')}</span>
	<div class="day event">31</div><span>{$mod->Lang('unavailable')}</span>
</div>
<!--[if lt IE 9]>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<!--<![endif]-->
<script src="{$field->GetURLPath()}/listit2fd-availability.js"></script>
{cms_lang_info assign='nls'}
<script>
jQuery(function () {ldelim}
	JSAvailability.frontend('{$field->GetId()}', '{$nls->isocode()}', '{$value|json_encode}');
{rdelim});
</script>