
/*
* if u want to set element to window top
* Project.FixedNav("elem").create();
*
* if u want to set element to window top
* Project.FixedNav("elem").create( 'top' );
*
* if u want to set element to parent top
* Project.FixedNav("elem", "parent-elem").create();
* 
* if some correction needed
* Project.FixedNav('.fix-header thead tr', '#dialog').create( null, function(clonedElem){

	return clonedElem.css({'top' : '37px'});
} );
*/

Project.FixedNav = function( elem, scrollElem ){

	if( typeof options == 'undefined' )
		var options = {};

	if( typeof scrollElem == 'undefined' ){

		options.scrollElem = window;
		options.parentElem = document.body;
	}
	else{

		options.scrollElem = scrollElem;
		options.parentElem = scrollElem;
	}

	return {

		selector : elem,

		create: function( type, complete ){

			var setAs = typeof type == 'undefined' ? ((options.parentElem == document.body || options.parentElem == 'body') ? 'top' : null) : type;

			setTimeout(() => {

				var selectorTop = (options.parentElem == document.body || options.parentElem == 'body' ? $(this.selector).offset().top : $( this.selector ).position().top - $( this.selector ).innerHeight() );
				if( selectorTop < 0 )
					selectorTop = 0;
				var clonedElem = $(this.selector).clone( true );

				if( setAs == 'top' ){

					clonedElem = $(clonedElem).css({'display' : 'none', 'position' : 'fixed', 'top' : '0px', 'left' : '0px', 'background-color' : '#ffffff', 'width' : '100%', 'z-index' : 101}).addClass('fix-nav-elem');
				}
				else{

					clonedElem = $(clonedElem).css({'display' : 'none', 'position' : 'absolute', 'background-color' : '#ffffff', 'z-index' : 100}).addClass('fix-nav-elem');
				}

				if( complete ){

					var retClonedElem = complete( clonedElem );
					if( retClonedElem )
						clonedElem = retClonedElem;
				}
				clonedElem = this.setTrChildrensWidth( clonedElem );

				$(this.selector).parent().prepend( clonedElem );

				$(options.scrollElem).scroll(function(){

					var windowTop = $(this).scrollTop();
					if ( windowTop > selectorTop ){

						$('.fix-nav-elem', options.parentElem).show();
					} 
					else{

						$('.fix-nav-elem', options.parentElem).hide();
					}
				});

				$(document).ready(function(){

					var windowTop = $(options.scrollElem).scrollTop();
					if ( windowTop > selectorTop ){

						$('.fix-nav-elem', options.parentElem).show();
					}
					else{

						$('.fix-nav-elem', options.parentElem).hide();
					}
				});
			});
		},
		
		setTrChildrensWidth : function( clonedElem ){

			var maxWidth = $( options.parentElem ).outerWidth();

			if( $(this.selector)[0].tagName.toUpperCase() == 'TR' ){

				var cloneElemMaxWidth = 0;
				var elemCount = 0;
				var addToEachElemWidth = 0;

				$('th', $(this.selector)).each(function(){

					cloneElemMaxWidth += $(this).outerWidth();
					elemCount += 1;
				});

				$('td', $(this.selector)).each(function(){

					cloneElemMaxWidth += $(this).outerWidth();
					elemCount += 1;
				});

				addToEachElemWidth = (maxWidth - cloneElemMaxWidth) / elemCount;
				if( addToEachElemWidth < 0 )
					addToEachElemWidth = 0;

				$('th', $(this.selector)).each(function(){

					var w = $(this).outerWidth();
					var i = $(this).index();

					$(clonedElem).find('th').eq(i).css({'width' : w + 'px'});
				});

				$('td', $(this.selector)).each(function(){

					var w = $(this).outerWidth();
					var i = $(this).index();
					$(clonedElem).find('td').eq(i).css({'width' : w + 'px'});
				});
			}

			return clonedElem;
		}
	};
}