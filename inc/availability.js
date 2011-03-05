var mode_status = 2;
var mode_action = 0;

function dropDates(start, end){
	$('#calendar_message').update('Speichern...');
	var url = '/modules/Belegungsplan/change_event.php?mode=2&start=' + encodeURIComponent(start) + '&end=' + encodeURIComponent(end);
	new Ajax.Request(url, {
		method: 'get',
		encoding: 'UTF-8',
		onSuccess: function(transport) {
			$('#calendar_message').update(transport.responseText).show();
		}
	});
}
function addDates(start, end, type){
	$('#calendar_message').update('Speichern...');
	var url = '/modules/Belegungsplan/change_event.php?mode=1&start=' + encodeURIComponent(start) + '&end=' + encodeURIComponent(end) + '&type=' + encodeURIComponent(type);
	new Ajax.Request(url, {
		method: 'get',
		encoding: 'UTF-8',
		onSuccess: function(transport) {
			$('#calendar_message').update(transport.responseText).show();
		}
	});
}
function makeTableUnclickable(){
	function makeTDUnclickable(element){
		element.stopObserving('click');
	}
	$('#calendar').removeClass('clickable');
	$$('#calendar td').each(makeTDUnclickable);
}
function makeTableClickable(mode1, mode2, refId){

	function makeTDClickable(element){
		element.stopObserving('click');
		element.observe('click', function(event){

			if(mode1 < 3 && (this.hasClassName('b') == false && this.hasClassName('r') == false))
				this.removeClass('f');
			else if(mode1 < 3 && (this.hasClassName('b') || this.hasClassName('r') || this.hasClassName('arrival-b')))
				return;
			else if(mode1 == 3){
				if(this.hasClassName('f'))
					return;

				var thisId = this.id.substr(1, 5);
				var removeType = 0;
				var start = '';
				var end = '';
				if(this.hasClassName('arrival-b')){
					this.removeClass('arrival-b');
					removeType = 1;
				} else if(this.hasClassName('departure-b')){
					this.removeClass('departure-b');
					removeType = 2;
				} else if(this.hasClassName('b')){
					this.removeClass('b');
					removeType = 3;
				}else if(this.hasClassName('arrival-r')){
					this.removeClass('arrival-r');
					removeType = 4;
				}else if(this.hasClassName('departure-r')){
					this.removeClass('departure-r');
					removeType = 5;
				}else if(this.hasClassName('r')){
					this.removeClass('r');
					removeType = 6;
				}
				this.addClass('f');

				if(removeType == 1 || removeType == 4)
					start = this.ClassName;
				else if(removeType == 2 || removeType == 5)
					end = this.ClassName;

				if(removeType != 1 && removeType != 4)
					for(n = thisId; n > 0; n--){
						removeElement = $('#d'+n);
						if(removeType == 2 || removeType == 3){
							if(removeElement.hasClassName('b')){
								removeElement.removeClass('b');
								removeElement.addClass('f');
							}else if(removeElement.hasClassName('arrival-b')){
								start = removeElement.className;
								removeElement.removeClass('arrival-b');
								if(removeElement.hasClassName('departure-r') == false && removeElement.hasClassName('departure-b') == false)
									removeElement.addClass('f');
								break;
							}
						}else if(removeType == 5 || removeType == 6){
							if(removeElement.hasClassName('r')){
								removeElement.removeClass('r');
								removeElement.addClass('f');
							}else if(removeElement.hasClassName('arrival-r')){
								start = removeElement.className;
								removeElement.removeClass('arrival-r');
								if(removeElement.hasClassName('departure-r') == false && removeElement.hasClassName('departure-b') == false)
									removeElement.addClass('f');
								break;
							}
						}
					}
				if(removeType != 2 && removeType != 5)
					for(n = thisId; n < 9999; n++){
						if(!$('#d'+n)) break;
						removeElement = $('#d'+n);
						if(removeType == 1 || removeType == 3){
							if(removeElement.hasClassName('departure-b')){
								end = removeElement.className;
								removeElement.removeClass('departure-b');
								if(removeElement.hasClassName('arrival-r') == false && removeElement.hasClassName('arrival-b') == false)
									removeElement.addClass('f');
								break;
							}else if(removeElement.hasClassName('b')){
								removeElement.removeClass('b');
								removeElement.addClass('f');
							}
						}else if(removeType == 4 || removeType == 6){
							if(removeElement.hasClassName('r')){
								removeElement.removeClass('r');
								removeElement.addClass('f');
							}else if(removeElement.hasClassName('departure-r')){
								end = removeElement.className;
								removeElement.removeClass('departure-r');
								if(removeElement.hasClassName('arrival-r') == false && removeElement.hasClassName('arrival-b') == false)
									removeElement.addClass('f');
								break;
							}
						}
					}
					dropDates(start, end);
			}

			if(mode1 == 1){
				if(mode2 == 1)
					this.addClass('arrival-r');
				else if(mode2 == 2)
					this.addClass('arrival-b');
				changeMode1(2, mode2, refId = this.id.substr(1, 5));
			}
			else if(mode1 == 2){
				if(mode2 == 1)
					this.addClass('departure-r');
				else if(mode2 == 2)
					this.addClass('departure-b');
				var thisId = this.id.substr(1, 5);
				addDates($('#d'+refId).className, $('#d'+thisId).className, mode2);
				for(n = parseInt(refId)+1; n < thisId; n++){
					element = $('#d'+n);
					if(element.hasClassName('n'))
						continue;
					element.removeClass('f');
					element.removeClass('arrival');
					element.removeClass('departure');
					if(mode2 == 1){
						element.removeClass('b');
						element.addClass('r');
					} else if(mode2 == 2){
						element.removeClass('r');
						element.addClass('b');
					}
				}
				changeMode1(0, mode2);
			}

	   });
	}

	if(mode1 < 3)
		$('#calendar').addClass('clickable');
	else
		$('#calendar').addClass('removable');
	$$('#calendar td').each(makeTDClickable);
}
function changeMode1(mode1, mode2, refId){
	if(mode1 == 0){
		makeTableUnclickable();
		$('#arrival-ctrl').removeClass('active');
		$('#departure-ctrl').removeClass('active');
		$('#delete-ctrl').removeClass('active');
	} else if(mode1 == 1){
		makeTableClickable(mode1, mode2, refId);
		$('#arrival-ctrl').addClass('active');
		$('#departure-ctrl').removeClass('active');
		$('#delete-ctrl').removeClass('active');
	} else if(mode1 == 2){
		makeTableClickable(mode1, mode2, refId);
		$('#departure-ctrl').addClass('active');
		$('#arrival-ctrl').removeClass('active');
		$('#delete-ctrl').removeClass('active');
	} else if(mode1 == 3){
		makeTableClickable(mode1, mode2, refId);
		$('#delete-ctrl').addClass('active');
		$('#arrival-ctrl').removeClass('active');
		$('#departure-ctrl').removeClass('active');
	}
}
$('#arrival-ctrl').click(function(){
	changeMode1(1, mode2);
});
$('#delete-ctrl').click(function(){
	changeMode1(3, mode2);
});
$('#reservation-ctrl').click(function(){
	mode_status = 1;
	$('#reservation-ctrl').addClass('active');
	$('#booking-ctrl').removeClass('active');
});
$('#booking-ctrl').click(function(){
	mode_status = 2;
	$('#booking-ctrl').addClass('active');
	$('#reservation-ctrl').removeClass('active');
});