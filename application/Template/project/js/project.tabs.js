

Project.Tabs = function(selector){

	var elem = $(selector);

	return {

		selector : elem,

		tabs : function(){

			var self = this;

			$('ul a', this.selector).click(function(){

				window.location.href = this.href;
				$(this.selector).tabs({'active' : self.getActiveTab()});
				return;
			});

			$(this.selector).tabs({'active' : self.getActiveTab()});
		},
		
		getActiveTab : function(){

			var id = '#' + window.location.href.split('#')[1];
			var index = $('a[href="' + id + '"]', this.selector).parent().index();
			if( index < 0 )
				index = 0;
			return index;
		}
	}
};