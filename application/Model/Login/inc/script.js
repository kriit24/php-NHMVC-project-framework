$(window).load(function(){

	$('.logged-label a').live('click', function(){

		$('.logged-label').hide();
	});
	$('body').on('click', function(){

		if( Project.clickEvent['logged-label'] == undefined )
			return;
		
		if ( Project.clickEvent['logged-label'] == false )
			$('.logged-label').hide();

		Project.clickEvent['logged-label'] = false;
	});
});