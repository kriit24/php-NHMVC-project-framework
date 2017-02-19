Project.Dialog = {
	'Object' : {},
	'Element' : {}
};
Project.Dialog.Element = {

	dialog : 'dialog',
	selector : '#dialog',
	loader : 'loader',
	complete : function(){},
	counter : 1,
	createElem : false,
	appendElem : false,
	reload : false,
	closeImmediately : false,//if element class "dialog-close" exists

	/*public methods*/

	load : function(){

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

		Project.Dialog.Object = this;
		$(document.body).append('<div id="'+Project.Dialog.Object.dialog+'" style="display:none;"></div>');
		$(document.body).append('<div id="'+Project.Dialog.Object.loader+'" style="'+style+'"><img src="/Template/public/images/ajax-loader-big.gif" style="float:left;"/> <div style="float:left;margin-left:5px;margin-top:15px;">Loading ...</div></div>');
		var new_top = ( $(window).height()-$("#" + +Project.Dialog.Object.loade).height() )/2;
		var new_left = ( $(window).width()-$(".ui-dialog").width() )/2;
		//$('#'+Project.Dialog.Object.loader).center(window);
		$('#'+Project.Dialog.Object.loader).css({'top' : new_top + 'px', 'left' : new_left + 'px'});
	},

	close : function(){

		$(Project.Dialog.Element.selector).dialog("close");
	},

	create : function(){

		$('#'+Project.Dialog.Object.loader).show();
		Project.Dialog.Object.createElem = true;
	},

	append : function(){

		$('#'+Project.Dialog.Object.loader).show();
		Project.Dialog.Object.appendElem = true;
	},

	/*
	$.dialog.get({'url' : '/ajax.php?model=s&page=s', 'data' : 'URLPARAMS', 'complete' : function(data){
		$(elem).live('click', function(){ $.dialog.post(); });
	}}).create();
	*/
	get : function(obj){

		Project.Dialog.Object.url = obj.url;
		Project.Dialog.Object.data = obj.data;
		Project.Dialog.Object.title = obj.title;
		Project.Dialog.Object.complete = obj.complete;

		var param = Project.Dialog.Object.data != undefined && Project.Dialog.Object.data.length > 0 ? Project.Dialog.Object.data.replace('?', '') : '';
		$.get(Project.Dialog.Object.url, param, function(data){

			if( Project.Dialog.Object.checkContent(data) )
				return true;

			if( Project.Dialog.Object.createElem == true )
				Project.Dialog.Object.createDialogElem(data);
			if( Project.Dialog.Object.appendElem == true )
				Project.Dialog.Object.appendDialogElem(data);

			Project.Dialog.Object.onComplete(data);

			Project.Dialog.Object.createElem = false;
			Project.Dialog.Object.appendElem = false;
		});
		return Project.Dialog.Object;
	},

	/*
	$.dialog.post({'url' : '/ajax.php?model=s&page=s', 'data' : 'POSTDATA', 'complete' : function(data){
		$(elem).live('click', function(){ $.dialog.post(); });
	}}).create();
	*/
	post : function(obj){

		Project.Dialog.Object.url = obj.url;
		Project.Dialog.Object.data = obj.data;
		Project.Dialog.Object.title = obj.title;
		Project.Dialog.Object.complete = obj.complete;

		var param = Project.Dialog.Object.data != undefined && Project.Dialog.Object.data.length > 0 ? Project.Dialog.Object.data.replace('?', '') : '';
		$.post(Project.Dialog.Object.url, param, function(data){

			if( Project.Dialog.Object.checkContent(data) )
				return true;

			if( Project.Dialog.Object.createElem == true )
				Project.Dialog.Object.createDialogElem(data);
			if( Project.Dialog.Object.appendElem == true )
				Project.Dialog.Object.appendDialogElem(data);

			if( Project.Dialog.Element.closeImmediately == true ){

				Project.Dialog.Element.closeImmediately = false;
				Project.Dialog.Element.close();
			}

			Project.Dialog.Object.onComplete(data);

			Project.Dialog.Object.createElem = false;
			Project.Dialog.Object.appendElem = false;
		});
		return Project.Dialog.Object;
	},

	//$.dialog.ajax({'url' : '/ajax.php?model=s&page=s', 'data' : 'POSTDATA', 'complete' : function(data){
	//	$(elem).live('click', function(){ $.dialog.post(); });
	//}}).create();
	ajax : function(obj){

		Project.Dialog.Object.url = obj.url;
		Project.Dialog.Object.data = obj.data;
		Project.Dialog.Object.complete = obj.complete;

		$.ajax({
			url: Project.Dialog.Object.url,
			type: 'POST',                
			success: function(data){
			
				if( Project.Dialog.Object.checkContent(data) )
					return true;

				if( Project.Dialog.Object.createElem == true )
					Project.Dialog.Object.createDialogElem(data);
				if( Project.Dialog.Object.appendElem == true )
					Project.Dialog.Object.appendDialogElem(data);

				if( Project.Dialog.Element.closeImmediately == true ){

					Project.Dialog.Element.closeImmediately = false;
					Project.Dialog.Element.close();
				}

				Project.Dialog.Object.onComplete(data);

				Project.Dialog.Object.createElem = false;
				Project.Dialog.Object.appendElem = false;
			},
			data: Project.Dialog.Object.data,
			cache: false,
			contentType: false,
			processData: false
		});
		return Project.Dialog.Object;
	},

	/*
	$.dialog.html({'data' : 'HTML', 'complete' : function(data){
		$(elem).live('click', function(){ $.dialog.post(); });
	}}).create();
	*/
	html : function(obj){

		var data = obj.data;
		Project.Dialog.Object.title = obj.title;
		Project.Dialog.Object.complete = obj.complete;

		if( Project.Dialog.Object.checkContent(data) )
			return true;

		//this is for that .create() or .append() method access before
		setTimeout(function(){

			if( Project.Dialog.Object.createElem == true )
				Project.Dialog.Object.createDialogElem(data);
			if( Project.Dialog.Object.appendElem == true )
				Project.Dialog.Object.appendDialogElem(data);

			Project.Dialog.Object.onComplete(data);

			Project.Dialog.Object.createElem = false;
			Project.Dialog.Object.appendElem = false;
		 }, 0);
		return Project.Dialog.Object;
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

	/*private methods*/

	createDialogElem : function(data){

		$($.dialog.selector).html(data);
		var properties = $.dialog.properties();
		$('#'+$.dialog.loader).hide();
		$($.dialog.selector).dialog(properties);
	},

	appendDialogElem : function(data){

		$.dialog.dialog ='dialog_'+$.dialog.counter;
		$.dialog.selector = '#dialog_'+$.dialog.counter;
		$.dialog.appendElem = true;

		$.dialog.load();

		$($.dialog.selector).html(data);
		var properties = $.dialog.properties();
		$('#'+$.dialog.loader).hide();
		$($.dialog.selector).dialog(properties);

		$.dialog.counter ++;
	},
	
	onComplete : function(data){

		if(Project.Dialog.Object.complete) 
			Project.Dialog.Object.complete(data);
	},

	properties : function(){

		var title = Project.Dialog.Object.title;
		var new_top = ( $(window).height()-$(".ui-dialog").height() )/2;
		var new_left = ( $(window).width()-$(".ui-dialog").width() )/2;
		$( ".ui-dialog" ).css({"position" : "fixed", "top" : (new_top <= 0 ? 10 : new_top - 10)+"px", "left" : (new_left <= 0 ? 10 : new_left - 10)+"px"});
		return {'title' : title, 'display' : 'block'};
	},

	clickAction: function(elem){

		if( typeof elem == 'string' ){

			var href = elem;
			$.dialog.get({'url' : href}).create();

			return false;
		}

		var href = '';
		var title = '';

		if( elem.selector == 'a.dialog' )
			href = $(elem).attr('href');
		if( elem.selector == 'tr.dialog' )
			href = $(elem).attr('rel');
		if( $(elem).attr('title') != undefined )
			title = $(elem).attr('title');

		if( href.length == 0 ){

			alert('DIALOG url missing: if A element then attr("href") if TR element attr("rel")');
			return true;
		}

		var scroll_t = $('body').scrollTop();
		$.dialog.get({'url' : href, 'data' : '', 'title' : title, 'complete' : function(data){

			$('input[type="submit"]').live('click', function(){

				var elem = $(this).parents('form');
				$.dialog.closeImmediately = $(this).attr('class') != undefined && $(this).attr('class').indexOf('dialog-close') > -1 ? true : false;

				if( elem.attr('action') != undefined && elem.attr('action').length > 0 ){

					var form = $(this).parents('form')[0];
					var formData = new FormData(
						form
					);
					formData.append($(this).attr('name'), $(this).attr('value'));

					if( CKEDITOR != undefined && CKEDITOR.instances != undefined ){

						$.each(CKEDITOR.instances, function(k, v){

							formData.append(k, CKEDITOR.instances[k].getData());
						})
					}

					$.dialog.ajax({'url' : elem.attr('action'), 'data' : formData, 'title' : title, 'complete' : function(data){
						
						$.dialog.reload = true;
						if( scroll_t )
							Project.Session.set('dialogScrollto', scroll_t);
					}}).create();
					return false;
				}
				return true;
			});
		}}).create();
		return false;
	}
};
$(document).ready(function(){

	$.extend( {dialog : Project.Dialog.Element} );
	$.dialog.load();
	var handlerClick = true;

	//set scroll position after reload
	if( Project.Session.get('dialogScrollto') ){

		$('html, body').animate({scrollTop: Project.Session.get('dialogScrollto')}, 500);
		Project.Session.remove('dialogScrollto');
	}

	if( $('tr.dialog,a.dialog').length > 0 ){

		$('a', 'tr.dialog').live('click', function(){

			if( $(this).attr('class') != 'dialog' )
				handlerClick = false;
			return true;
		});
	}

	$('tr.dialog,a.dialog').live('click', function(){

		if( handlerClick == true ){

			return $.dialog.clickAction(this);
		}
		handlerClick = true;
	});
	$('button.ui-dialog-titlebar-close').live('click', function(){

		//no reload - it can re post data
		if( $.dialog.reload )
			window.location.href = window.location.href;
		$.dialog.reload = false;
	});
});