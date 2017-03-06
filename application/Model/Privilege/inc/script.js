$(document).ready(function(){

	$('.select-all').itoggle();
	//$('.select-group').itoggle();

	$('.select-group').live('click', function(){

		$(this).parents('.subitoggle').find('input[type="checkbox"]').not(this).prop( "checked", function( i, val ) {
			return !val;
		});
	});
});