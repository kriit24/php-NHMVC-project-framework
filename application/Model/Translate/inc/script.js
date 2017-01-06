$(document).ready(function(){

	$('select[name="byLanguage"]').change(function(){

		window.location.href = $.removeLocationParam( $.location(window.location.href, {'byLanguage': this.value}), 'pagenumber' );
	});
});