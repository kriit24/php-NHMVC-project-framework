(function($){

	$.fn.extend({

		fixed_menu : function(){

			var elem = this.selector;
			var elemTop = $(elem).offset().top;

			 $(window).scroll(function(){

				var windowTop = $(this).scrollTop();

				if ( windowTop >= elemTop ){

					$(elem).addClass("fixed-nav");
				} 
				else {

					$(elem).removeClass("fixed-nav");
				}
			});
		}
	});
})(jQuery);