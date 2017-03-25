$(document).ready(function(){

	Project.Toggle('.select-all').itoggle();

	$('.select-group').live('click', function(){

		var elem = '#' + $(this).attr('for');

		$(this).parents('.subitoggle').find('input[type="checkbox"]').not(elem).prop( "checked", function( i, val ) {
			return !val;
		});
	});
});