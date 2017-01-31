var Project = {};
Project.clickEvent = [];
(function($){

	//$p.toggle

    //if u want add kontrol $().function();
	$.fn.extend({

		center: function(parentElement){

			var padding_left = $(this.selector).css('padding-left');
			var padding_top = $(this.selector).css('padding-top');

			var margin_left = ( $(parentElement).width()-$(this.selector).width()-parseInt(padding_left) )/2;
			var margin_top = ( $(parentElement).height()-$(this.selector).height()-parseInt(padding_top) )/2;
			if($(window).scrollTop() > 0){

				var scrollTop = $(window).scrollTop();
				margin_top += scrollTop;
			}
			$(this.selector).css({'left' : margin_left+'px', 'top' : margin_top+'px'});
		},
		live: function(action, complete){

			$.each(this.selector.split(','), function(key, item){

				$( document.body ).delegate( item, action, {'selector' : item}, function(e) {

					var obj = this;
					if( typeof obj.complete == 'boolean' )
						var obj = $(this);
					obj.complete = complete;
					obj.selector = e.data.selector;

					if( obj.length > 0 ) {

						$.each( obj[0].attributes, function( index, attr ) {

							obj[attr.name] = attr.value;
						}); 
					}

					if(obj.complete) return obj.complete(e);
				});
			});
		},
		confirm: function(text){

			$( this.selector ).live('click', function(){

				var answer = confirm(text);
				if (answer){

					window.location.href = $(this).attr('href');
					return false;
				}
				else
					return false;
			});
		},
		
		//paragraph toggle
		ptoggle : function(speed){

			var sel = this.selector.split('.');
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

			$(this.selector).live('click', function(){

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

			$(this).live('click', function(){

				$(this).parents('form').find('input[type="checkbox"]').not(this).prop( "checked", function( i, val ) {
					return !val;
				});
			});
		},

		scrollTo( speed, posAdd ){

			var selector = this.selector;

			if( speed == undefined )
				var speed = 500;
			if( posAdd == undefined )
				var posAdd = 0;

			$('html, body').animate({
				scrollTop: ( $(selector).offset().top + posAdd )
			}, speed);
		}
	});

	$.extend({

		_POST: {},

		//$.location( {'key' : 'value'} );
		location: function( href, params ){

			var hrefSplit = href.split('?')[1];
			if( hrefSplit == undefined )
				hrefSplit = '';
			var newHref = '';
			$.each(hrefSplit.split('&'), function(key, item){

				$.each(params, function(key2, value){

					if( item.indexOf(key2+'=') == -1 )
						newHref += newHref ? '&'+item : item;
				});
			});
			$.each(params, function(key, value){

				newHref += newHref ? '&'+key+'='+value : key+'='+value;
			});
			return (newHref.indexOf('?') == -1 && newHref.length > 0 ? '?' : '')+newHref;
		},

		removeLocationParam: function( href, param ){

			var hrefSplit = href.split('?')[1];
			if( hrefSplit == undefined )
				return '';
			var newHref = '';
			$.each(hrefSplit.split('&'), function(key, item){

				if( item.indexOf(param+'=') == -1 )
					newHref += newHref ? '&'+item : item;
			});
			return (newHref.indexOf('?') == -1 && newHref.length > 0 ? '?' : '')+newHref;
		},
		
		setPOST: function( jsonPost ){

			if( this.canJSON( jsonPost ) ){

				this._POST = $.parseJSON(jsonPost);
			}
		},

		ucFirst: function(string) {

			return string.substring(0, 1).toUpperCase() + string.substring(1);
		},

		replace_ment: function(key){

			key = key.replace('{', '');
			key = key.replace('}', '');
			if(typeof pregArray[key] == 'string' || typeof pregArray[key] == 'number')
				return pregArray[key];
			return '';
		},

		preg_replace: function(str, dataArray){

			if( typeof str == 'string' ){

				pregArray = dataArray;
				//alert('str'+str);
				str = str.replace(/%7B/g, '{').replace(/%7D/g, '}');
				var return_string = str.replace(/\{([a-zA-Z0-9\__]+)\}/gi, this.replace_ment);
				//alert('return_string '+return_string);
				return return_string;
			}
		},

		preg_match_all: function(regex, haystack){

		   var globalRegex = new RegExp(regex, 'gi');
		   var globalMatch = haystack.match(globalRegex);
		   matchArray = new Array();
		   for (var i in globalMatch) {

			  nonGlobalRegex = new RegExp(regex);
			  nonGlobalMatch = globalMatch[i].match(nonGlobalRegex);
			  matchArray.push(nonGlobalMatch[1]);
		   }
		   return matchArray;
		},

		canJSON: function(value, complete) {

			if(value.length == 0) return {};

			value = value.replace(/^\s+|\s+$/g,"");
			if( value.substr(0,1) == '{' || value.substr(0,1) == '[' ){

				if( complete ){

					var obj = this;
					obj.complete = complete;
					return obj.complete( $.parseJSON(value) );
				}
				return {};
			}
			return {};
		}
	});
})(jQuery);

$(document).ready(function(){

	$(".datepicker").live("focusin", function(){

	   $(this).datepicker({dateFormat: "dd.mm.yy"});
	});

	$('.dropdown').live('click', function(){

		Project.clickEvent[$(this).attr('id')] = true;

		var className = '.' + $(this).attr('id');
		var elem = this;
		if( $(className) != undefined && $(className).length > 0 ){

			//$(className).toggle(500);
			$(className).animate({height: "toggle"}, 500, function(){

				if( $(elem).has('i.fa-chevron-down') != undefined && $(elem).has('i.fa-chevron-down').length > 0 )
					$('i', elem).removeClass('fa-chevron-down').addClass('fa-chevron-up');
				else{

					if( $(elem).has('i.fa-chevron-up') != undefined && $(elem).has('i.fa-chevron-up').length > 0 )
						$('i', elem).removeClass('fa-chevron-up').addClass('fa-chevron-down');
				}
			});
		}
	});
});