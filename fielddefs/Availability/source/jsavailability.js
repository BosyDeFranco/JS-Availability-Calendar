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
		findEvent = function (date) {
			return $.grep(events, function (event) {
				if(event.date === date.format('YYYY-MM-DD')) {
					return true;
				}
				return false;
			})[0];
		},
		eventStart = function (target) {
			 newEvent = target;
			$(newEvent.element).addClass('event-start');
		},
		eventEnd = function (target) {
			var date = newEvent.date.clone(),
				existing = null,
				existingIndex = null;
			if(target.date - newEvent.date <= 0 || (target.events.length > 0 && target.events[0].isStart === false)) {
				$(newEvent.element).removeClass('event-start');
				newEvent = null;
				return false;
			}
			do {
				existing = findEvent(date);
				if(typeof existing === 'undefined') {
					events.push({
						date: date.format('YYYY-MM-DD'),
						isStart: date.format('YYYYMMDD') === newEvent.date.format('YYYYMMDD'),
						isEnd: date.format('YYYYMMDD') === target.date.format('YYYYMMDD')
					});
				} else {
					existingIndex = jQuery.inArray(existing, events);
					if(events[existingIndex].isStart || events[existingIndex].isEnd) {
						events[existingIndex].isStart = true;
						events[existingIndex].isEnd = true;
					}
				}
				date.add('days', 1);
			} while(target.date - date >= 0);
			newEvent = null;
			updateEvents();
		},
		eventRemove = function (target) {
			var date = target.date.clone(),
				event = null,
				index = null;
			if(confirm('Do you wish to remove this event?')) {
				// find start
				do {
					event = findEvent(date);
					index = jQuery.inArray(event, events);
					if(event.isEnd === false) {
						events.splice(index, 1);
					} else {
						events[index].isStart = false;
						break;
					}
					date.subtract('days', 1);
				} while(event.isStart === false);
				// find end
				date = target.date.clone();
				do {
					date.add('days', 1);
					event = findEvent(date);
					index = jQuery.inArray(event, events);
					if(event.isStart === false) {
						events.splice(index, 1);
					} else {
						events[index].isEnd = false;
						break;
					}
				} while(event.isEnd === false);
				updateEvents();
			}
		},
		changeEvent = function (target) {
			if(target.date === null) {
				return false;
			}
			if(newEvent) {
				eventEnd(target);
			} else if(target.events.length > 0 && target.events[0].isEnd === false) {
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
						events: events
					});
				calendars.push(calendar);
			});
			$('.jsavailability .previous-button, .jsavailability .next-button').click(changeMonth);
		}
	};
}(jQuery));