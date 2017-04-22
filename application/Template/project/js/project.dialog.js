Project.Dialog = function( elem ){

	if( elem ){

		if( elem.indexOf('#') == -1 ){

			alert('Dialog element selector must be ID like: #dialog');
			return {};
		}

		elem = elem.replace('#', '');

		//var loader = elem + '_loader';
		var loader = 'dialog_loader';
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
		if( $('#'+elem).length == 0 )
			$(document.body).append('<div id="' + elem + '" class="project-dialog" style="display:none;"></div>');

		if( $('#'+loader).length == 0 )
			$(document.body).append('<div id="' + loader + '" style="' + style + '"><img src="/Template/public/images/ajax-loader-big.gif" style="float:left;"/> <div style="float:left;margin-left:5px;margin-top:15px;">Loading ...</div></div>');

		$('#'+loader).center(window);
	}

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
		IF U HAVE DIALOG OPENED
		$.dialog('#dialog').clickEvent(this).create();

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

		//IN some cases is needed regular window.open
		//$.dialog().window({'url' : '".$this->url( array('model' => '', 'method' => '') )."', 'width' : 800, 'height' : 800});

		window : function( Object ){

			var top = ( $(window).outerHeight() - Object.height ) / 2;
			var left = ( $(window).outerWidth() - Object.width ) / 2;

			window.open( Object.url, 'dialog', 'width=' + Object.width + ',height=' + Object.height + ',top=' + top + ',left=' + left );
			return false;
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

			if( this.selector == undefined ){

				$('.project-dialog').each(function(){

					$(this).dialog("close");
				});

				return;
			}

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

			var selector = this.selector.split('_')[0] + '_' + this.counter;
			this.selector = selector;
			var dialog = $.dialog('#'+selector);
			var z_index = parseInt($('#'+selector).css('z-index'));
			$('#'+selector).css({'z-index' : (z_index + 1) });
			dialog.appendElement = true;
			dialog.createDialogElem(data);
			this.counter ++;
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

		dialogClick : function(e, attrClass){

			if( Project.textSelect ){

				Project.textSelect = false;
				return false;
			}

			var elem = $(e.target);

			if( e.target.tagName.toUpperCase() == 'SPAN' ){

				if( elem.parent('label') && elem.parent('label').attr('for') != undefined ){

					var id = elem.parent('label').attr('for');
					if( elem.parent('label').prev().attr('id') != undefined && elem.parent('label').prev().attr('id') == id && elem.parent('label').prev()[0].tagName.toUpperCase() == 'INPUT' )
						return false;
				}
			}
			if( e.target.tagName.toUpperCase() == 'INPUT' || e.target.tagName.toUpperCase() == 'A' ){

				if( typeof elem.attr('class') == 'undefined' || elem.attr('class').indexOf('dialog') == -1 )
					return false;
			}

			if( elem.attr('disabled') != undefined )
				return false;

			return true;
		},

		bindClick : function( data, title, scrollTop ){

			var self = this;

			$('input[type="submit"]', $('#'+self.selector)).bind('click', function(event){

				var form = $(this).parents('form');
				var takeAction = true;
				$('input[required],textarea[required]', form).each(function(k, inputElem){

					if( $(this).val().length == 0 ){

						if( $(this).attr('required-label') != undefined )
							Project.Required.isInvalid(this, $(this).attr('required-label'));
						takeAction = false;
						return false;
					}
					else{

						this.setCustomValidity('');
					}
				});

				self.closeImmediately = $(this).attr('class') != undefined && $(this).attr('class').indexOf('dialog-close') > -1 ? true : false;

				if( form.attr('action') != undefined && form.attr('action').length > 0 && takeAction == true ){

					var formData = new FormData(
						form[0]
					);
					formData.append($(this).attr('name'), $(this).attr('value'));

					if( typeof CKEDITOR != 'undefined' && CKEDITOR.instances != undefined ){

						$.each(CKEDITOR.instances, function(k, v){

							formData.append(k, CKEDITOR.instances[k].getData());
						})
					}

					self.post({'url' : form.attr('action'), 'data' : formData, 'title' : title, 'complete' : function(data){

						self.bindClick( data, title, scrollTop );
						self.reload = true;
						if( scrollTop )
							Project.Session.set('scrollTo', scrollTop);
					}}).create();
					return false;
				}
			});
			return false;
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
				href = $(elem).attr('data-href');

			if( $(elem).prop('title') )
				title = $(elem).prop('title');

			if( href == undefined || href.length == 0 ){

				alert('DIALOG url missing: if A element then attr("href") if TR element attr("data-href")');
				return false;
			}

			var scrollTop = $('body').scrollTop();
			return self.get({'url' : href, 'data' : '', 'title' : title, 'complete' : function(data){

				return self.bindClick(data, title, scrollTop);
			}});
		}
	};
};

$(document).ready(function(){

	$.extend( {dialog : Project.Dialog} );
	var dialog = $.dialog('#dialog');
	dialog.reload = false;

	$('.dialog').live('click', function(e){

		if( dialog.dialogClick(e) )
			dialog.clickEvent(this).create();
		return false;
	});
	$('.dialog-append').live('click', function(e){

		if( dialog.dialogClick(e) )
			dialog.clickEvent(this).append();
		return false;
	});
	$('button.ui-dialog-titlebar-close').live('click', function(){

		//no reload - it can re post data
		if( dialog.reload ){

			$.reload();
		}
	});
});