var content_html = '';

function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}

$(document).ready(function(){

	if( posted ){

		setTimeout(function(){ 
			$('.layout-content-copying div').html( 'Done' );
		}, 1500);

		setTimeout(function(){ 

			$( ".layout-content-copying-background" ).animate({opacity: 0}, 300, function() {

				$('.layout-content-copying-background').hide();
				$('.layout-content-copying').hide();

				if( Project.Session.get('scrollTo') ){

					var top = Project.Session.get('scrollTo');
					$('html, body').animate({scrollTop: top}, 0);
					Project.Session.remove('scrollTo');
				}
			});
		}, 2000);
	}

	$('div,section,nav,header,footer').each(function(){

		$(this).hover(
			function(){

				$(this).addClass('layout-copy-border');
			},
			function(){

				$(this).removeClass('layout-copy-border');
			}
		);

		$(this).not('.layout-content').click(function(e){

			var html = $(this)[0].outerHTML;
			html = $(html).removeClass('layout-copy-border');
			content_html = b64EncodeUnicode( $(html)[0].outerHTML );

			$('.layout-content').css({'top' : e.pageY, 'left' : e.pageX + 20, 'display' : 'block'});
			return false;
		});
	});

	$('input[name="addContent"]').click(function(){

		var scrollTop = $('body').scrollTop();
		Project.Session.set('scrollTo', scrollTop);

		$(this).parents('form').find('input[name="base64_content"]').val( content_html );
		$(this).parents('form').submit();
		return false;
	});
});