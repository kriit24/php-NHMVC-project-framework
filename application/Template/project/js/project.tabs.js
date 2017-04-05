//ADD TABS elment attribute style="display:none;"
//ADD AFTER tabs div element
//<div class="tabs-loader"><img src="/Template/admin/images/ajax-loader-big.gif"/></div>
/*
//NB!!! DO NOT BUT this on $(window).load
$(document).ready(function(){

	Project.Tabs('elem').tabs();
});
*/

Project.Tabs = function(selector){

	var elem = selector;

	return {

		selector : elem,
		selectorElem : $(elem),
		timeoutId : '',

		tabs : function( Object ){

			var self = this;
			if( Object == undefined )
				Object = {};

			$('ul a', self.selectorElem).click(function(){

				if( this.href.indexOf('#') == -1 ){

					var showLoader = true;
					var id = this.id;
					if( id != undefined ){

						if( $('div[aria-labelledby="'+id+'"]').html().length > 0 )
							showLoader = false;
					}

					if( showLoader )
						$('.tabs-loader').show();
					return;
				}

				window.location.href = this.href;
				$('.tabs-loader').hide();
				return;
			});

			//$(window).load(function(){
			$(document).ready(function(){

				var activeTab = self.getActiveTab();

				if( typeof Object.complete != 'undefined' )
					Object.complete( $( "li", self.selectorElem ).eq( activeTab ).find('a') );

				$(self.selectorElem).tabs({
					'active' : activeTab, 
					'activate': function(event, ui){

						if( self.timeoutId )
							clearTimeout(self.timeoutId);

						self.setActiveTab( ui.newTab );

						if( typeof Object.complete != 'undefined' )
							Object.complete( ui.newTab.find('a') );
					},
					'load': function(){

						$('.tabs-loader').hide();
						$(self.selectorElem).show();
					},
					'create': function(){

						self.timeoutId = setTimeout(function(){

							$('.tabs-loader').hide();
							$(self.selectorElem).show();
						}, 150);

					}
				});

				if( window.location.href.indexOf('#') != -1 ){

					$('.tabs-loader').hide();
					$(self.selectorElem).show();
				}
			});
		},

		getActiveTab : function(){

			var tab_active = Project.Session.get('tab_active');
			if( window.location.href.indexOf('#') != -1 ){

				var id = window.location.href.split('#')[1];
				var index = $('a[href="#' + id + '"]', this.selector).parent().index();
				if( $('a[href="#' + id + '"]', this.selector).length > 0 )
					return index;
			}
			if( tab_active != undefined && tab_active[ this.getUrlParam() ] != undefined ){

				return tab_active[ this.getUrlParam() ].active;
			}
			return 0;
		},

		setActiveTab : function( tabActive ){

			var urlObject = Project.Session.get('tab_active') ? Project.Session.get('tab_active') : {};
			urlObject[ this.getUrlParam() ] = {'active' : tabActive.index()};
			Project.Session.set('tab_active', urlObject);
		},

		getUrlParam : function(){

			var urlSplit = window.location.href.split('/');
			var ret = urlSplit[0] + '/' + urlSplit[1] + '/' + urlSplit[2];
			if( urlSplit[3] != undefined )
				ret += '/' + urlSplit[3];

			if( urlSplit[4] != undefined )
				ret += '/' + urlSplit[4];

			if( urlSplit[5] != undefined )
				ret += '/' + urlSplit[5];

			if( urlSplit[6] != undefined )
				ret += '/' + urlSplit[6];

			ret += '/' + this.selector;

			return ret;
		}
	}
};