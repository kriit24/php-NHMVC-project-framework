var latestTag = '';
(function($){

    //if u want add kontrol $().function();
	$.fn.extend({

		filterInit: function(){

			var selector = this.selector;

			$(selector+' .remove').live('click', function(){

				var tagName = $(this).parents(selector).attr('rel');
				var href = $.removeLocationParam(window.location.href, tagName);
				href = $.removeLocationParam(href, tagName + '_autocomplete_label');
				href = $.removeLocationParam(href, tagName + '_autocomplete_value');
				if( href == '?filter=true' )
					href = '';
				var url = window.location.href.split('?')[0];
				window.location.href = (href.length > 0 ? url + href.split('#')[0] : url );
				return false;
			});
			$(selector+' .close').live('click', function(){

				$(selector).hide();
				return false;
			});
			$( ".datepicker", selector ).datepicker({dateFormat: "dd.mm.yy"});
		},

		filterEnterClick: function(parentDiv){

			//for keypress - elements not in form
			$('input,select,textarea', this.selector).each(function(key, item){

				if( item.type != 'submit' ){

					$( item ).keypress(function( event ) {

						if ( event.which == 13 ) {

							$().filterSearchClick(this, parentDiv);
							return false;
						}
					});
				}
			});
		},

		HelperFilter: function(html){

			var selector = this.selector;
			var htmlData = $.parseJSON(html);

			$(document).ready(function(){

				$.each(selector.split(','), function(key, item){

					if( $('.'+item).length > 0 ){

						$('.'+item).not('.header-filter').each(function(){

							$(this).addClass('header-filter');

							var html = $(this).html();
							var text = $(this).text();
							html = html.replace(text, '');

							var attrClass = 'filter_tags filter_tag_name_'+item;
							if( window.location.href.indexOf('?') > -1 ){

								if( window.location.href.indexOf(item+'=') > -1 || window.location.href.indexOf(item+'[]=') > -1 ){

									attrClass += ' filter_checked';
								}
							}
							html += htmlData[item];
							$(this).html( html );
							
							var spanElem = $('<span class="'+attrClass+'"><i class="fa fa-filter" aria-hidden="true"></i>'+text+'</span>');
							$(spanElem).bind('click', function(){

								var tagName = $(this, '.filter_helper_header').attr('class').split('filter_tag_name_')[1].split(' ')[0];
								if( latestTag != tagName )
									$('.filter_helper_header.filter_helper_'+latestTag).hide();
								//$('.filter_helper_header.filter_helper_'+tagName).toggle();
								$(this).parent('.header-filter').find('.filter_helper_header.filter_helper_'+tagName).toggle();
								latestTag = tagName;
							});
							$(this).append(spanElem);

							$(this).filterEnterClick('.filter_helper_header');
						});
					}
					else{

						alert('Filter - header class - does not exists: "'+item+'"');
					}
				});
			});

			$('.filter_helper_header input[name="filter"]').live('click', function(){

				$().filterSearchClick(this, '.filter_helper_header');
				return false;
			});
			$('.filter_helper_header').filterInit();
		},

		ClickFilter: function(){

			$(this.selector).filterInit();
			var parentDiv = this.selector;

			$(parentDiv).filterEnterClick(parentDiv);
			$(parentDiv+' input[name="filter"]').live('click', function(){
				
				$().filterSearchClick(this, parentDiv);
				return false;
			});
		},

		filterSearchClick: function(self, parentDiv){

			var serialized = '';
			var href = '';

			$('input,select,textarea', $(self).parents(parentDiv)).each(function(key, item){

				if( item.type != 'submit' ){

					var tagname = item.name.replace('[', '').replace(']', '');
					href = $.removeLocationParam(window.location.href, 'pagenumber');
					href = $.removeLocationParam(href, tagname);

					if( item.value.length > 0 ){

						if( item.type == 'radio' ){

							if( item.checked == true )
								serialized += serialized ? '&'+item.name+'='+encodeURIComponent(item.value) : item.name+'='+encodeURIComponent(item.value);
						}
						else{

							serialized += serialized ? '&'+item.name+'='+encodeURIComponent(item.value) : item.name+'='+encodeURIComponent(item.value);
						}
					}
				}
			});
			href = href.length > 0 && href.indexOf('?') == -1 ? '?'+href : href;
			if( href.indexOf('filter') == -1 && serialized.indexOf('filter') == -1 && serialized.length > 0 )
				serialized += '&filter=true';
			if( serialized.length > 0 )
				window.location.href = href+(href.indexOf('?') == -1 ? '?' : '&')+serialized;
			else
				window.location.href = href;
		}
	});
})(jQuery);

$(document).ready(function(){

	var width = $('.filter_dropdown_box .filter_dropdown_content').width() - 50;
	$('.filter_dropdown_box .filter_dropdown_content .submit').width( width );
	var visible = false;

	/*$('.dropdown').click(function(){

		$('.filter_dropdown_box .filter_dropdown_content').toggle(500, function(){

			if( visible == false ){

				$('.filter_dropdown_title i').attr({'class' : 'fa fa-sort-asc dropdown'});
				visible = true;
			}
			else{

				$('.filter_dropdown_title i').attr({'class' : 'fa fa-sort-desc dropdown'});
				visible = false;
			}
		});
	});*/
});