Project.Required = {

	elements: function(elem){

		$(elem).on('invalid', function(){

			if( $(this).attr('required-label') != undefined )
				Project.Required.isInvalid(this, $(this).attr('required-label'));
		});
		$(elem).on('input', function(){

			this.setCustomValidity('');
			return;
		});
	},
	
	isInvalid: function(textbox, message){

		if (textbox.value == '') {
			textbox.setCustomValidity(message);
		}
		else if(textbox.validity.typeMismatch){
			textbox.setCustomValidity(message);
		}
		else{

			if(typeof textbox.setCustomValidity !== 'undefined')
				textbox.setCustomValidity('');
		}
		return true;
	}
};

$(window).on('load', function(){

	$('input[required],textarea[required]').each(function(k, elem){

		Project.Required.elements( elem );
	});

	$('.ui-dialog-content').bind("DOMNodeInsertedIntoDocument",function(){

		$(this).ready(function(){

			$('input[required],textarea[required]', this).each(function(k, elem){

				Project.Required.elements( elem );
			});
		});
	});
});