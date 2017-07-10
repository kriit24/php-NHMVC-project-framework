$(document).ready(function(){

	$(".datepicker").live("focusin", function(){

	   $(this).datepicker({dateFormat: "dd.mm.yy"});
	});

	$('div').mousedown(function(e){

		if( Project.textSelect.selected ){

			Project.textSelect.selected = false;

			var sel = window.getSelection ? window.getSelection() : document.selection;
			if (sel) {

				if (sel.removeAllRanges) {

					sel.removeAllRanges();
				} 
				else if (sel.empty) {

					sel.empty();
				}
			}
		}
	});

	$('div').mouseup(function(e) {

		//if( Project.textSelect.selected == false ){

			var text = null;
			if (window.getSelection) {

				text = window.getSelection().toString();
			} 
			else if (document.selection) {

				text = document.selection.createRange().text;
			}

			if( text && text.length > 0 ){

				var jSpot = $( 'td' ).findElementByText( text ).parent();

				if( jSpot.length == 0 )
					jSpot = $( 'tr' ).findElementByText( text ).parent();

				if( jSpot.length == 0 )
					jSpot = $( 'table' ).findElementByText( text ).parent();

				if( jSpot.length == 0 )
					jSpot = $( 'p' ).findElementByText( text ).parent();

				if( jSpot.length == 0 )
					jSpot = $( 'div' ).findElementByText( text ).parent();

				Project.textSelect.selected = true;
				Project.textSelect.elem = jSpot;
			}
		//}
	});

	$('.link').live('click', function(e){

		if( Project.textSelect.selected ){

			Project.textSelect.selected = false;
			return;
		}

		if( e.target.tagName.toUpperCase() == 'SPAN' ){

			var elem = $(e.target);
			if( elem.parent('label') && elem.parent('label').attr('for') != undefined ){

				var id = elem.parent('label').attr('for');
				if( elem.parent('label').prev().attr('id') != undefined && elem.parent('label').prev().attr('id') == id && elem.parent('label').prev()[0].tagName.toUpperCase() == 'INPUT' )
					return;
			}
		}
		if( e.target.tagName.toUpperCase() == 'INPUT' || e.target.tagName.toUpperCase() == 'A' )
			return;

		window.location.href = $(this).attr('rel');
	});

	$('.autosize').live('focus', function(){

		var styleArray = {};
		var style = $(this).attr('autosize');

		if( $(this).attr('style') )
			Project.autoSizeStyle[this] = $(this).attr('style');

		var tmp = style.split(';');
		$.each(tmp, function(k, v){

			if( v.length > 0 ){

				var sp = v.split(':');
				styleArray[ sp[0] ] = sp[1];
			}
		});
		$( this ).css( styleArray );
	});

	$('.autosize').live('focusout', function(){

		//it is needed for sliding ibox
		setTimeout(() => {

			$(this).attr('style', Project.autoSizeStyle[this]);
		 }, 100);
	});

	$('.check-all').live('click', function(){

		var checkElements = $(this).attr('for');
		if( checkElements != undefined ){

			$('.' + checkElements).prop('checked', (!$('.' + checkElements).prop('checked') ? true : false) );
		}
	});

	$('.no-click').live('click', function(){

		return false;
	});

	if( typeof Project.Session != 'undefined' ){

		if( Project.Session.get('scrollTo') ){

			var top = Project.Session.get('scrollTo');
			$('html, body').animate({scrollTop: top}, 500);
			Project.Session.remove('scrollTo');
		}
	}

	$('label.checkbox').prev('input[type="checkbox"]').css({'visibility' : 'hidden', 'margin-bottom' : '10px', 'display' : 'inline-block', 'width' : '15px'});
	$('label.checkbox').prev('input[type="radio"]').css({'visibility' : 'hidden', 'margin-bottom' : '0px', 'display' : 'inline-block', 'width' : '15px'});
});