$(document).ready(function(){

	$('.dropdown-minimenu-block').html( $('.dropdown-maximenu').html() );

	var isOverflowed = function(element){

		if( element.clientWidth == 0 )
			return false;

		return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
	};
	var menuResize = function( elem ){
		
		if( isOverflowed( elem ) ){

			$('.dropdown-minimenu').show();
			$('.dropdown-maximenu').css({'visibility' : 'hidden'});
		}
		else{

			$('.dropdown-minimenu').hide();
			$('.dropdown-maximenu').css({'visibility' : 'visible'});
		}
	};


	$('.dropdown-minimenu').live('click', function(){

		$('.dropdown-minimenu-block').toggle();
	});

	$( window ).resize(function() {

		menuResize( $('.dropdown-group')[0] );
	});
	menuResize( $('.dropdown-group')[0] );
});