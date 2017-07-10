var Project = {};
Project.clickEvent = [];
Project.autoSizeStyle = {};
Project.textSelect = {'selected' : false, 'elem' : null};
(function($){

	//$p.toggle

    //if u want add kontrol $().function();
	$.fn.extend({

		center: function(parentElement){

			if( $(this.selector).css('position') == 'fixed' ){

				var new_top = ( $(parentElement).height()-$(this.selector).height() )/2;
				var new_left = ( $(parentElement).width()-$(this.selector).width() )/2;
				$(this.selector).css({'top' : new_top + 'px', 'left' : new_left + 'px'});
			}
			else{

				var padding_left = $(this.selector).css('padding-left');
				var padding_top = $(this.selector).css('padding-top');

				var margin_left = ( $(parentElement).width()-$(this.selector).width()-parseInt(padding_left) )/2;
				var margin_top = ( $(parentElement).height()-$(this.selector).height()-parseInt(padding_top) )/2;
				if($(window).scrollTop() > 0){

					var scrollTop = $(window).scrollTop();
					margin_top += scrollTop;
				}
				$(this.selector).css({'left' : margin_left+'px', 'top' : margin_top+'px'});
			}
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
		confirm: function(text, complete){

			$( this.selector ).live('click', function(){

				var answer = confirm(text);
				if( complete ){

					return complete(answer, this);
				}
				if (answer){

					if( $(this)[0].tagName.toUpperCase() == 'A' && $(this).attr('href').length > 0 ){

						window.location.href = $(this).attr('href');
						return false;
					}
					return true;
				}
				else
					return false;
			});
		},
		
		scrollTo: function( speed, offsetAdd ){

			var selector = this.selector.length == 0 ? this : $(this.selector);

			if( speed == undefined )
				var speed = 500;
			if( offsetAdd == undefined )
				var offsetAdd = 0;

			$('html, body').animate({
				scrollTop: ( selector.offset().top + offsetAdd )
			}, speed);
		},
		
		scrollToAction: function( offsetAdd ){

			var offsetTop = 1;
			if( !this.selector ){

				offsetTop = $(window).scrollTop();
			}
			else{
				
				$(this.selector).offset().top;
			}

			if( offsetAdd == undefined )
				var offsetAdd = 0;

			Project.Session.set('scrollTo', offsetTop + offsetAdd);
		},
		
		findElementByText: function( text ){

			return $( this.selector + ":contains("+text+")")
            .filter(function(){ return $(this).children().length === 0;});
		}
	});

	$.extend({

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

		reload: function(){

			var regex = new RegExp("([?;&])reload[^&;]*[;&]?");
			var query = window.location.href.split('#')[0].replace(regex, "$1").replace(/&$/, '');
			window.location.href =
				(window.location.href.indexOf('?') < 0 ? "?" : query + (query.slice(-1) != "?" ? "&" : ""))
				+ "reload=" + new Date().getTime() + window.location.hash;
		},

		removeLocationParam: function( href, param, partialMatch ){

			var hrefSplit = href.split('?')[1];
			if( hrefSplit == undefined )
				return '';
			var newHref = '';
			$.each(hrefSplit.split('&'), function(key, item){

				if( item.indexOf(param+'=') == -1 && item.indexOf(param+'[') == -1 )
					newHref += newHref ? '&'+item : item;
			});
			return (newHref.indexOf('?') == -1 && newHref.length > 0 ? '?' : '')+newHref;
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

			if( typeof value != 'string' ) return {};
			if(value == undefined || value.length == 0) return {};

			value = value.replace(/^\s+|\s+$/g,"");
			if( (value.substr(0,1) == '{' || value.substr(0,1) == '[') && (value.substr( (value.length - 1 ),1) == '}' || value.substr( (value.length - 1 ),1) == ']' ) ){
			//if( value.substr(0,1) == '{' || value.substr(0,1) == '[' ){

				if( complete ){

					var obj = this;
					obj.complete = complete;
					return obj.complete( $.parseJSON(value) );
				}
				return $.parseJSON(value);
			}
			return {};
		}
	});
})(jQuery);