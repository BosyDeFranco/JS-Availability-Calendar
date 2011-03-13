$(function(){
		   initYearSwitch();
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