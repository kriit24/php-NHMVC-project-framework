Project.Table = function( elem ){

	return {

		trToggled : {},

		click : function( bgColor, parentElem ){

			var self = this;

			$('head').append('<style>.background-color-click-event{background-color:'+bgColor+' !important;}</style>');

			$( elem ).live('click', function(){

				if( parentElem ){

					$(this).parents(parentElem).toggleClass("background-color-click-event");
				}
				else{

					$(this).toggleClass("background-color-click-event");
				}
			});
		}
	};
};

$(document).ready(function(){

	Project.Table('tr').click( 'rgba(0,0,0,0.075)' );
	Project.Table('.dialog').click( 'rgba(0,0,0,0.075)', 'tr' );
});