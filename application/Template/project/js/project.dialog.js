Project.Dialog = function( elem ){

	if( elem.indexOf('#') == -1 ){

		alert('Dialog element selector must be ID like: #dialog');
		return {};
	}

	elem = elem.replace('#', '');

	var loader = elem + '_loader';
	var style = 'background:white;';
	style += 'border:1px solid #626262;';
	style += 'padding:3px;';
	style += 'position:fixed;';
	style += 'display:none;';
	style += 'z-index:1500;';
	style += 'border:5px solid rgb(98, 98, 98);';
	style += 'border-radius:0.55em;-moz-border-radius:0.55em;-webkit-border-radius:0.55em;';
	style += 'padding:15px;';
	style += 'font-weight:bold;';

	//Project.Dialog.Object = this;
	$(document.body).append('<div id="' + elem + '" style="display:none;"></div>');
	$(document.body).append('<div id="' + loader + '" style="' + style + '"><img src="/Template/public/images/ajax-loader-big.gif" style="float:left;"/> <div style="float:left;margin-left:5px;margin-top:15px;">Loading ...</div></div>');
	$('#'+loader).center(window);

	return {

		selector : elem,
		loaderSelector : loader,
		counter : 1,
		createElement : false,
		appendElement : false,
		closeImmediately : false,
		reload : false,
		Object : {},

		start : function(){

			if( $('#'+this.loaderSelector).css('display') == 'none' )
				$('#'+this.loaderSelector).show();

			return this;
		},

		/*
		FOR SHORT LOADING
		$.dialog('#elem').get({'url' : '/ajax.php?model=s&page=s', 'data' : 'GETDATA', 'title' : 'title', 'complete' : function(data){
			$(elem).live('click', function(){ $.dialog.post(); });
		}}).create();

		FOR LONG LOADING
		var dialog = $.dialog('#elem').start();
		dialog.get().create();
		*/

		get : function( Object ){

			this.Object = Object;

			this.request( 'GET' );
			return this;
		},

		/*
		FOR SHORT LOADING
		$.dialog('#elem').post({'url' : '/ajax.php?model=s&page=s', 'data' : 'POSTDATA', 'title' : 'title', 'complete' : function(data){
			$(elem).live('click', function(){ $.dialog.post(); });
		}}).create();

		FOR LONG LOADING
		var dialog = $.dialog('#elem').start();
		dialog.post().create();
		*/

		post : function( Object ){

			this.Object = Object;

			this.request( 'POST' );
			return this;
		},

		/*
		FOR SHORT LOADING
		$.dialog('#elem').html({'data' : 'HTML', 'title' : 'title', 'complete' : function(data){
			$(elem).live('click', function(){ $.dialog.post(); });
		}}).create();

		FOR LONG LOADING
		var dialog = $.dialog('#elem').start();
		dialog.html().create();
		*/

		html : function( Object ){

			this.Object = Object;
			setTimeout(() => {

				this.compileElement(this.Object.data);
			 }, 0);
			return this;
		},

		create : function(){

			this.createElement = true;
			this.start();
		},

		append : function(){

			this.appendElement = true;
			this.start();
		},

		close : function(){

			$('#'+this.selector).dialog("close");
		},

		request : function( method ){

			var self = this;

			$.ajax({
				url: self.Object.url,
				type: method,
				success: function(data){

					self.compileElement(data);
				},
				data: self.Object.data,
				cache: false,
				contentType: false,
				processData: false
			});
		},

		compileElement : function( data ){

			if( this.checkContent(data) )
				return true;

			if( this.createElement == true )
				this.createDialogElem(data);

			if( this.appendElement == true )
				this.appendDialogElem(data);

			if( this.closeImmediately == true ){

				this.closeImmediately = false;
				this.close();
			}

			this.onComplete(data);

			this.createElement = false;
			this.appendElement = false;
		},

		checkContent : function(content){

			if( content.indexOf('project.dialog.js') > -1 ){

				if( $.cookie('SESSION_LOGGED') ){

					$.cookie('SESSION_LOGGED', 'false', { 'expires' : 1, 'path' : '/'});
					//reload() is not good bechause it will resubmit <form>
					window.location.href = window.location.href;
				}
				else{

					alert('Warning: project.dialog.js file in content');
				}
				return true;
			}
			return false;
		},

		createDialogElem : function(data){

			$('#'+this.selector).html(data);
			$('#'+this.loaderSelector).hide();
			$('#'+this.selector).dialog( this.properties() );
		},

		appendDialogElem : function(data){

			var selector = this.selector + '_' + this.counter;
			var dialog = $.dialog('#'+selector);
			dialog.appendElement = true;
			dialog.createDialogElem(data);

			/*
			$($.dialog.selector).html(data);
			var properties = $.dialog.properties();
			$('#'+$.dialog.loader).hide();
			$($.dialog.selector).dialog(properties);
			*/

			dialog.counter ++;
		},

		onComplete : function(data){

			if(this.Object.complete)
				this.Object.complete(data);
		},

		properties : function(){

			//var new_top = ( $(window).height()-$(".ui-dialog").height() )/2;
			//var new_left = ( $(window).width()-$(".ui-dialog").width() )/2;
			//$( ".ui-dialog" ).css({"position" : "fixed", "top" : (new_top <= 0 ? 10 : new_top - 10)+"px", "left" : (new_left <= 0 ? 10 : new_left - 10)+"px"});
			return {'title' : this.Object.title};
		},
		
		clickEvent : function(elem){

			if( typeof elem == 'string' ){

				this.get({'url' : elem}).create();
				return;
			}

			var self = this;
			var href = '';
			var title = '';

			if( $(elem).prop('href') && $(elem).prop('href').length > 0 )
				href = $(elem).prop('href');
			else
				href = $(elem).attr('rel');

			if( $(elem).prop('title') )
				title = $(elem).prop('title');

			if( href == undefined || href.length == 0 ){

				alert('DIALOG url missing: if A element then attr("href") if TR element attr("rel")');
				return false;
			}

			var scrollTop = $('body').scrollTop();
			self.get({'url' : href, 'data' : '', 'title' : title, 'complete' : function(data){

				$('input[type="submit"]', $('#'+self.selector)).live('click', function(){

					var elem = $(this).parents('form');
					self.closeImmediately = $(this).attr('class') != undefined && $(this).attr('class').indexOf('dialog-close') > -1 ? true : false;

					if( elem.attr('action') != undefined && elem.attr('action').length > 0 ){

						var form = $(this).parents('form')[0];
						var formData = new FormData(
							form
						);
						formData.append($(this).attr('name'), $(this).attr('value'));

						if( typeof CKEDITOR != 'undefined' && CKEDITOR.instances != undefined ){

							$.each(CKEDITOR.instances, function(k, v){

								formData.append(k, CKEDITOR.instances[k].getData());
							})
						}

						self.post({'url' : elem.attr('action'), 'data' : formData, 'title' : title, 'complete' : function(data){

							self.reload = true;
							if( scrollTop )
								Project.Session.set('dialogScrollto', scrollTop);
						}}).create();
						return false;
					}
					return true;
				});
			}}).create();
			return false;
		}
	};
};

$(document).ready(function(){

	$.extend( {dialog : Project.Dialog} );
	var dialog = $.dialog('#dialog');
	dialog.reload = false;

	if( Project.Session.get('dialogScrollto') ){

		$('html, body').animate({scrollTop: Project.Session.get('dialogScrollto')}, 500);
		Project.Session.remove('dialogScrollto');
	}

	$('.dialog').live('click', function(e){

		var elem = $(e.target);

		if( e.target.tagName.toUpperCase() == 'SPAN' ){

			if( elem.parent('label') && elem.parent('label').attr('for') != undefined ){

				var id = elem.parent('label').attr('for');
				if( elem.parent('label').prev().attr('id') != undefined && elem.parent('label').prev().attr('id') == id && elem.parent('label').prev()[0].tagName.toUpperCase() == 'INPUT' )
					return;
			}
		}
		if( e.target.tagName.toUpperCase() == 'INPUT' || e.target.tagName.toUpperCase() == 'A' ){

			if( typeof elem.attr('class') == 'undefined' || elem.attr('class').indexOf('dialog') == -1 )
				return;
		}

		return dialog.clickEvent(this);
	});
	$('button.ui-dialog-titlebar-close').live('click', function(){

		//no reload - it can re post data
		if( dialog.reload ){

			if( window.location.hash.length == 0 )
				window.location.href = window.location.href;
		}
	});
});