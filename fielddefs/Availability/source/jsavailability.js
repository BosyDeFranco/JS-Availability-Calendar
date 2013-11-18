/*! JSAvailability v0.10 2013-09-16 */
/*jslint white: true */
var JSAvailability = (function ($) {
	"use strict";
	var $container = null,
		calendars = [],
		events = [],
		newEvent = null,
		$sampleInput = null,
		changeMonth = function (e) {
			switch(e.target.className) {
				case 'previous-button':
					$.each(calendars, function () {
						this.back();
					});
					break;
				case 'next-button':
					$.each(calendars, function () {
						this.forward();
					});
					break;
			}
		},
		updateEvents = function () {
			$.each(calendars, function () {
				this.setEvents(events);
			});
			$container.find('input[type=hidden]').remove();
			$.each(events, function () {
				var $input = $sampleInput.clone();
				$input.attr('value', JSON.stringify(this));
				$container.append($input);
			});
		},
		eventStart = function (target) {
			 newEvent = target;
			$(newEvent.element).addClass('event-start');
		},
		// TODO: check overlap
		eventEnd = function (target) {
			var date = newEvent.date.clone(),
				existing = null,
				existingIndex = null;
			if(target.date - newEvent.date <= 0 || (target.events.length > 0 && target.events[0].start !== target.date.format('YYYY-MM-DD'))) {
				$(newEvent.element).removeClass('event-start');
				newEvent = null;
				return false;
			}
			events.push({
				start: newEvent.date.format('YYYY-MM-DD'),
				end: target.date.format('YYYY-MM-DD')
			});
			newEvent = null;
			updateEvents();
		},
		eventRemove = function (target) {
			if(confirm('Do you wish to remove this event?')) {
				events = $.grep(events, function (event) {
					if(target.date.diff(event.start) >= 0 && target.date.diff(event.end) <= 0) {
						return false;
					}
					return true;
				});
				updateEvents();
			}
		},
		changeEvent = function (target) {
			if(target.date === null) {
				return false;
			}
			if(newEvent) {
				eventEnd(target);
			} else if(target.events.length == 2 || (target.events.length == 1 && target.events[0].end !== target.date.format('YYYY-MM-DD'))) {
				eventRemove(target);
			} else {
				eventStart(target);
			}
		};
	return {
		backend: function (id, actionid, lang) {
			var $inputs = null;
			moment.lang(lang);
			$container = $('#jsavailability-'+id);
			$inputs = $container.find('input[type=hidden]');
			if($inputs.length > 0) {
				$inputs.each(function (index, $input) {
					events.push(JSON.parse($input.value));
				});
			}
			$sampleInput = $('<input type="hidden" name="'+actionid+'customfield['+id+'][]" />');
			$container.find(' > .calendar-month').each(function (index, month) {
				var calendar = $(month).clndr({
						template: $('#jsavailability-'+id+'-template').html(),
						weekOffset: 1,
						startWithMonth: moment().add('month', index),
						showAdjacentMonths: false,
						multiDayEvents: {
							startDate: 'start',
							endDate: 'end'
						},
						events: events,
						clickEvents: {
							click: changeEvent
						}
					});
				calendars.push(calendar);
			});
			$('.jsavailability .previous-button, .jsavailability .next-button').click(changeMonth);
		},
		frontend: function (id, lang, events) {
			moment.lang(lang);
			events = JSON.parse(events);
			$('#jsavailability-'+id+' > .calendar-month').each(function (index, month) {
				var calendar = $(month).clndr({
						template: $('#jsavailability-'+id+'-template').html(),
						weekOffset: 1,
						startWithMonth: moment().add('month', index),
						showAdjacentMonths: false,
						multiDayEvents: {
							startDate: 'start',
							endDate: 'end'
						},
						events: events
					});
				calendars.push(calendar);
			});
			$('.jsavailability .previous-button, .jsavailability .next-button').click(changeMonth);
		}
	};
}(jQuery));