$(document).ready(function(){

	$('.select-all').itoggle();
	//$('.select-group').itoggle();

	$('.select-group').live('click', function(){

		var elem = '#' + $(this).attr('for');

		$(this).parents('.subitoggle').find('input[type="checkbox"]').not(elem).prop( "checked", function( i, val ) {
			console.log(this);
			return !val;
		});
	});
});