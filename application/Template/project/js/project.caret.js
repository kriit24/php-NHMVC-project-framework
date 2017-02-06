/*
Project.Caret('selector').getText();
Project.Caret('selector').setText('text');
*/

Project.Caret = function(selector){

	var caretElem = $(selector);

	return {

		elem : caretElem,
		range : {begin : 0, end : 0},

		getRange: function(begin, end){

			if( typeof begin == 'number' )
				this.range.begin = begin;

			if( typeof end == 'number' )
				this.range.end = end;

			if( typeof begin == 'number' && typeof end == 'number' )
				return this.range;

			this.range = this.calcRange(begin, end);
			return this.range;
		},

		calcRange: function (begin, end){

			if (this.elem.length == 0) return;
			if (typeof begin == 'number')
			{
				end = (typeof end == 'number') ? end : begin;
				return this.elem.each(function ()
				{
					if (this.elem.setSelectionRange)
					{
						this.elem.setSelectionRange(begin, end);
					} else if (this.elem.createTextRange)
					{
						var range = this.elem.createTextRange();
						range.collapse(true);
						range.moveEnd('character', end);
						range.moveStart('character', begin);
						try { range.select(); } catch (ex) { }
					}
				});
			} else
			{
				if (this.elem[0].setSelectionRange)
				{
					begin = this.elem[0].selectionStart;
					end = this.elem[0].selectionEnd;
				} else if (document.selection && document.selection.createRange)
				{
					var range = document.selection.createRange();
					begin = 0 - range.duplicate().moveStart('character', -100000);
					end = begin + range.text.length;
				}
				return { begin: begin, end: end };
			}
		},

		getElementValue: function(){

			var elemTag = this.elem[0].tagName.toLowerCase();

			if( elemTag == 'input' || elemTag == 'select' || elemTag == 'textarea' )
				return this.elem.val();
			else
				return this.elem.html();
		},

		setElementValue: function( txt ){

			var elemTag = this.elem[0].tagName.toLowerCase();

			if( elemTag == 'input' || elemTag == 'select' || elemTag == 'textarea' )
				return this.elem.val(txt);
			else
				return this.elem.html(txt);
		},

		getText: function(begin, end){

			var range = this.getRange(begin, end);
			var text = this.getElementValue();
			var length = range.end - range.begin;
			return text.substr(range.begin, length);
		},
		
		setText: function( replaceWith, returnText, begin, end ){

			if( begin != undefined && end != undefined ){

				var text = this.getElementValue();
				var textStart = text.substr(0, begin);
				var textEnd = text.substr(end);

				if( returnText != undefined && returnText == true )
					return textStart + replaceWith + textEnd;
				this.setElementValue( textStart + replaceWith + textEnd );

				return;
			}

			var range = this.getRange();
			var text = this.getElementValue();
			var textStart = text.substr(0, range.begin);
			var textEnd = text.substr(range.end);

			if( returnText != undefined && returnText == true )
				return textStart + replaceWith + textEnd;
			this.setElementValue( textStart + replaceWith + textEnd );
		}
	}
};