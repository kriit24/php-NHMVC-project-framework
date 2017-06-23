$(document).ready(function(){

	$('.dropdown').live('click', function(){

		Project.clickEvent[$(this).attr('id')] = true;

		var className = '.' + $(this).attr('id');
		if( $(className) == undefined || $(className).length == 0 )
			className = '.' + $(this).attr('for');

		var elem = this;
		if( $(className) != undefined && $(className).length > 0 ){

			//$(className).toggle(500);
			$(className).animate({height: "toggle"}, 500, function(){

				if( $(elem).has('i.fa-chevron-down') != undefined && $(elem).has('i.fa-chevron-down').length > 0 ){

					$('.fa-chevron-down', elem).removeClass('fa-chevron-down').addClass('fa-chevron-up');
				}
				else{

					if( $(elem).has('i.fa-chevron-up') != undefined && $(elem).has('i.fa-chevron-up').length > 0 ){

						$('.fa.fa-chevron-up', elem).removeClass('fa-chevron-up').addClass('fa-chevron-down');
					}
				}
			});
		}
	});
});