(function($){

    //if u want add kontrol $().function();
	$.fn.extend({

		HelperSort: function(html){

			var selector = this.selector;
			var htmlData = $.parseJSON(html);

			$(document).ready(function(){

				$.each(selector.split(','), function(key, item){

					$('.'+item).prepend( htmlData[item] );
				});
			});

			$('.sort_helper').live('click', function(){

				var tagName = $(this).attr('class').split('sort_helper_')[1].split(' ')[0];
				var href = window.location.href.split('?')[1];
				var direction = window.location.href.indexOf('sort['+tagName+']=DESC') > -1 ? 'ASC' : 'DESC';
				var newHref = (href != undefined ? href.replace(/sort\[([a-zA-Z0-9\__]+)\]\=([a-zA-Z0-9\__]+)\&/gi, '') : '');
				newHref = newHref.replace(/\&sort\[([a-zA-Z0-9\__]+)\]\=([a-zA-Z0-9\__]+)/gi, '');
				newHref = newHref.replace(/sort\[([a-zA-Z0-9\__]+)\]\=([a-zA-Z0-9\__]+)/gi, '');

				window.location.href = (newHref.length > 0 ? '?' : '')+newHref+(newHref.length > 0 ? '&' : '?')+'sort['+tagName+']='+direction;
			});
		}
	});
})(jQuery);