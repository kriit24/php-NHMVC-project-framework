//ADD TABS elment attribute style="display:none;"
//ADD BEFORE tabs div element
//<div class="tabs-loader"><img src="/Template/admin/images/ajax-loader-big.gif"/></div>
/*
//NB!!! DO NOT BUT this on $(window).load
$(document).ready(function(){

	Project.Tabs('elem').tabs();
});
*/

Project.Tabs = function(selector){

	var elem = $(selector);

	return {

		selector : elem,

		tabs : function(){

			var self = this;

			$('ul a', self.selector).click(function(){

				var urlObject = {};
				urlObject[0] = this.href;
				Project.Session.set('tab_url', urlObject);

				window.location.href = this.href;
				$(self.selector).tabs({'active' : self.getActiveTab()});
				return;
			});

			$(window).load(function(){

				$(self.selector).show();
				$(self.selector).tabs({'active' : self.getActiveTab()});
				$('.tabs-loader').hide();
			});
		},
		
		getActiveTab : function(){

			var id = window.location.href.split('#')[1];
			if( id == undefined ){

				var tab_url = Project.Session.get('tab_url');
				if( tab_url ){

					var id = tab_url[0].split('#')[1];
				}
			}

			if( id == undefined )
				return 0;

			var index = $('a[href="#' + id + '"]', this.selector).parent().index();
			if( index < 0 )
				index = 0;
			return index;
		}
	}
};