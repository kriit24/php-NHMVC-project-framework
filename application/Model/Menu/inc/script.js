$(document).ready(function(){

	var menuClass = $('.dropdown-maximenu').attr('class');
	var elemCenter = document.getElementById('content-bar').getElementsByClassName('row')[0].getElementsByClassName('col')[0];
	//var scrollWidth = elemCenter.getBoundingClientRect().width;
	var scrollWidth = 600;

	var isOverflowed = function(element){
		return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
	};
	var menuResize = function( elem ){
		
		if( isOverflowed( elem ) ){

			if( menuClass.indexOf('dropdown-block') == -1 ){

				$('.dropdown-minimenu').show();
				$('.dropdown-maximenu').addClass('dropdown-block');

				menuClass = $('.dropdown-maximenu').attr('class');
			}
		}
		else{

			if( menuClass.indexOf('dropdown-block') > -1 && elemCenter.getBoundingClientRect().width >= scrollWidth ){

				$('.dropdown-minimenu').hide();
				$('.dropdown-maximenu').removeClass('dropdown-block');

				menuClass = $('.dropdown-maximenu').attr('class');
			}
		}
	};

	$('.dropdown-minimenu').live('click', function(){

		$('.dropdown-group .dropdown-maximenu.dropdown-block').toggle();
	});

	$( window ).resize(function() {

		menuResize( $('.dropdown-group')[0] );
	});
	menuResize( $('.dropdown-group')[0] );
});