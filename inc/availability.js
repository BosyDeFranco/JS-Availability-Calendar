$(function(){
		   initYearSwitch();
		   initCalendar();
});
function ajax_function(usr_function, params){
	return $.post(CMS_ADMIN_DIR+"/moduleinterface.php", { 
			mact: 'JSAvailability,m1_,ajax,0',
			sp_: CMS_USER_KEY,
			m1_usr_function: usr_function,
			showtemplate: false,
			m1_params: params
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
			$(container).html(data);
		}
	);
}
function initYearSwitch(){
	$('#' + CMS_FORM_ID + 'y').change(function(){
		ajax_function('setCurrentYear', $(this).val());
		ajax_tab('calendartab', '#calendar-view_c');
   });
}
function initCalendar(){
	var mode_action = '';
	var mode_status = 'booking';
	var _t = $('#calendar-view_c').find('table').eq(0);

	// Table
	$('td', _t).each(function(){
		$(this).click(function(){
			if(mode_action == '')
				return;

			if(mode_action == 'arrival' && $(this).hasClass('f'))
				alert('frei');
		});
	 });

	// Controls
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