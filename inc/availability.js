$(function(){
		   initYearSwitch();
		   initObjectSwitch();
		   initCalendar();
});
function ajax_function(usr_function, params, avoid_answer, callback){
	return $.post(CMS_ADMIN_DIR+"/moduleinterface.php", { 
			mact: 'JSAvailability,m1_,ajax,0',
			sp_: CMS_USER_KEY,
			m1_usr_function: usr_function,
			showtemplate: false,
			m1_params: params
		},
		function(data){
			if(avoid_answer != true && data != ''){
				var message = $('<div class="pagemcontainer"><p class="pagemessage">' + data + '</p></div>').insertBefore('#reloadArea');
				window.setTimeout(function(){ message.hide(); }, 9000);
			}
			if(typeof callback == 'function')
				callback();
		}
	);
}
function ajax_tab(usr_tab, container){
	return $.post(CMS_ADMIN_DIR+"/moduleinterface.php", { 
			mact: 'JSAvailability,m1_,ajax,0',
			sp_: CMS_USER_KEY,
			m1_usr_tab: usr_tab,
			showtemplate: false
		},
		function(data){
			$(container).replaceWith(data);
		}
	);
}
function reload(){
	ajax_tab('calendartab', '#reloadArea');
}
function initYearSwitch(){
	$('#' + CMS_FORM_ID + 'y').change(function(){
		ajax_function('setCurrentYear', $(this).val(), true, reload);
   });
}
function initObjectSwitch(){
	$('#' + CMS_FORM_ID + 'o').change(function(){
		ajax_function('setCurrentObject', $(this).val(), true, reload);
   });
}
function initCalendar(){
	var _t = $('#calendar-view_c').find('table').eq(0);
	var mode_action = '';
	var mode_status = 'booking';
	var arrival = '';
	var departure = '';

	// Table
	function postPeriod(){
		ajax_function('postPeriod', arrival+','+departure+','+mode_status);
		arrival = '';
		departure = '';
		reload();
	}
	$('td', _t).each(function(){
		$(this).click(function(){
			var a = $(this);
			if(mode_action == '')
				return;

			switch(mode_action){
				case 'arrival':
					if(a.hasClass('f') == false && a.hasClass('departure') == false)
						break;
					arrival = a.attr('id');
					a.removeClass('f').addClass('arrival').addClass(mode_status);
					changeModeAction('departure');
					$('td', _t).hover(
						function(){
							if($(this).attr('id').replace(/-/g, '') <= arrival.replace(/-/g, '') || $(this).hasClass('f') == false)
								return;
							$(this).data('class', $(this).attr('class')).attr('class', 'departure '+mode_status);
						},
						function(){
							$(this).attr('class', $(this).data('class'));
						}
					);
					break;
				case 'departure':
					if(a.attr('id').replace(/-/g, '') <= arrival.replace(/-/g, ''))
						break;
					departure = a.attr('id');
					postPeriod();
					clearModeAction();
					$('td', _t).unbind('mouseenter mouseleave');
					break;
				case 'delete':
					ajax_function('deletePeriod', $(this).attr('id'));
					reload();
					break;
			}
		});
	 });

	// Controls
	function clearModeAction(){
		mode_action = '';
		$('.control-1').removeClass('active');
		_t.removeClass('clickable');
	}
	function changeModeAction(mode){
		mode_action = mode;
		$('.control-1').removeClass('active');
		$('#' + mode + '-ctrl').addClass('active');
		_t.addClass('clickable');
	}
	function changeModeStatus(mode){
		mode_status = mode;
		$('.control-2').removeClass('active');
		$('#' + mode + '-ctrl').addClass('active');
	}
	$('#arrival-ctrl').click(function(){
		changeModeAction('arrival');
	});
	$('#delete-ctrl').click(function(){
		changeModeAction('delete');
	});
	$('#reservation-ctrl').click(function(){
		changeModeStatus('reservation');
	});
	$('#booking-ctrl').click(function(){
		changeModeStatus('booking');
	});
}
function editObject(el){
	var container = $(el).parent().parent().children('.objectname');
	var input = $('<input type="text" size="55" />').val(container.html());
	container.html(input);
	$(el).parent().data('original', $(el)).end().replaceWith(CMS_SAVE_ICON);
}
function saveObject(el){
	$(el).attr('onclick', '').click(function(){return false});
	var container = $(el).parent().parent().children('.objectname');
	var input = $('input', container);
	var object_id = container.parent().children('td:first-child').html();
	container.addClass('loading').append('<img src="' + CMS_INC_DIR + 'ajax-loader.gif" class="animation" />');
	ajax_function('saveObject', object_id+','+input.val(), true, function(){
		$(el).replaceWith($(el).parent().data('original'));
		container.removeClass('loading').html(input.val());
		reload();
	});
}