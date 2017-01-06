$(document).ready(function(){

	$('#add-role-2').ptoggle(400);
	$('.edit').click(function(){

		window.location.href = $(this).attr('rel');
	});
});