Project.Toggle = function(selector){

	if( selector == undefined )
		selector = 'span.toggle';
	var selectorElem = selector;

	//BEST EXAMPLE IN Helper\Form\Form\Company
	return{

		'elem' : selectorElem,

		'load' : function(){

			var self = this;

			$(self.elem).each(function(){

				var forAttr = $(this).attr('for');
				if( forAttr != undefined ){

					var elem = null;
					var parent = $(this).parent('*');

					if( $('input[name="'+forAttr+'"]', parent).length > 0 ){

						elem = $('input[name="'+forAttr+'"]', parent);
					}
					if( $('textarea[name="'+forAttr+'"]', parent).length > 0 ){

						elem = $('textarea[name="'+forAttr+'"]', parent);
					}
					if( $('select[name="'+forAttr+'"]', parent).length > 0 ){

						elem = $('select[name="'+forAttr+'"]', parent);
					}

					if( !elem ){

						elem = $('<input type="text" name="'+forAttr+'" value="'+ $(this).html() +'" class="form-control hidden">');
						$(this).before(elem);
					}

					if( elem ){

						$(parent).css({'padding-right' : '30px'});
						$(elem).after('<img src="/Template/public/images/close_enabled.png" style="position:absolute;padding:5px;" class="toggle hidden" for="'+forAttr+'">');
						$(elem).bind('change paste keyup', function(){

							$('span.toggle[for="'+forAttr+'"]', parent).html( this.value );
							$(parent).css({'background' : '#cde8fe', 'color' : '#000000'});
						});
					}
				}
			});

			$( self.elem + ',img.toggle').bind('click', function(){

				var forAttr = $(this).attr('for');
				if( forAttr != undefined ){

					var elem = null;
					var parent = $(this).parent('*');

					if( $('input[name="'+forAttr+'"]', parent).length > 0 ){

						elem = $('input[name="'+forAttr+'"]', parent);
					}
					if( $('textarea[name="'+forAttr+'"]', parent).length > 0 ){

						elem = $('textarea[name="'+forAttr+'"]', parent);
					}
					if( $('select[name="'+forAttr+'"]', parent).length > 0 ){

						elem = $('select[name="'+forAttr+'"]', parent);
					}

					if( elem ){

						$(elem).toggle(0, function(){ $(elem).focus(); });
						$(this).toggle();

						if( $(this)[0].tagName == 'SPAN' ){

							var tElem = $('img.toggle[for="'+forAttr+'"]', parent);

							if( tElem.css('display') == 'none' )
								tElem.css({'display' : 'inline-block'});
							else
								tElem.css({'display' : 'none'});
						}

						if( $(this)[0].tagName == 'IMG' ){

							var tElem = $('span.toggle[for="'+forAttr+'"]', parent);

							if( tElem.css('display') == 'none' )
								tElem.css({'display' : 'inline-block'});
							else
								tElem.css({'display' : 'none'});
						}
					}
				}
			});
		},
		
		//paragraph toggle
		ptoggle : function(speed){

			var sel = this.elem.split('.');
			var elem = (sel[1] != undefined ? sel[1] : sel[0]) . replace('#', '') . replace('.', '');
			if( $('form.'+elem).length > 0 ){

				$('form.'+elem).addClass('paragraph-toggle');
			}
			else if( $('div.'+elem).length > 0 ){

				$('div.'+elem).addClass('paragraph-toggle');
			}
			else if( $('div.'+elem).length == 0 && $('form.'+elem).length == 0 && $( '.' + elem ).length == 0 ){

				$( '.' + elem ).addClass('paragraph-toggle');
			}
			else{

				alert('Cannot find "form.'+elem+'" OR "div.'+elem+'" to toggle');
			}

			$(this.elem).live('click', function(){

				if( $('form.'+elem).length > 0 ){

					$('form.'+elem).animate({height: "toggle"}, speed);
				}
				else if( $('div.'+elem).length > 0 ){

					$('div.'+elem).animate({height: "toggle"}, speed);
				}
				else if( $('div.'+elem).length == 0 && $('form.'+elem).length == 0 && $( '.' + elem ).length == 0 ){

					$( '.' + elem ).animate({height: "toggle"}, speed);
				}
				else{

					alert('Cannot find "form.'+elem+'" OR "div.'+elem+'" to toggle');
				}
				return false;
			});
		},
		
		//toggle checkbox elements
		itoggle : function(){

			$(this.elem).live('click', function(){

				$(this).parents('form').find('input[type="checkbox"]').not(this).prop( "checked", function( i, val ) {
					return !val;
				});
			});
		}
	}
};

$(window).load(function(){

	Project.Toggle().load();
});