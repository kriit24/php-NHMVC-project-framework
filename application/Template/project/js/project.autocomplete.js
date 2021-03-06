//Project.Autocomplete.setSource('car_id', ['some', 'some2', 'some3']);
//Project.Autocomplete.setSource('car_id', [{ value: "1", label: "some" },{ value: "2", label: "some 2" },{ value: "3", label: "some 3" }]);
//GET values by input name, if u want to autofill input elements

Project.Autocomplete = {

	source : {},

	setSource: function( elemName, source ){

		this.source[ elemName ] = source;
	},

	method: function(elem, origElem, item){

		if( item.value && item.label ){

			$( elem ).val( item.label );
			$( origElem ).val( item.value );
			if( Object.keys(item).length > 2 ){

				var form = $(origElem).parents('form');

				$.each(item, function(k, v){

					if( $('input[name="' + k + '"]', form) != undefined ){

						$('input[name="' + k + '"]', form).val( v );
					}
				});
			}
			return false;
		}
		return true;
	},
	
	elements: function(elem){

		var name = $(elem).attr('name');

		if( $(elem).attr('data-href') == undefined && this.source[name] == undefined ){

			alert('Elem: ' + $(elem).attr('name') + ' source missing: data-href="ajax-url" ');
			$( elem ).css({'background' : '#ff0000'});
			return;
		}

		var source = this.source[name] != undefined ? this.source[name] : $(elem).attr('data-href');
		var value  = $(elem).attr('autocomplete-value') != undefined ? $(elem).attr('autocomplete-value') : $(elem).val();
		var newElem = $(elem).clone(true);
		newElem.attr({'type' : 'hidden', 'data-href' : ''});
		$(elem).after(newElem);
		$(elem).attr({'name' : name + '_autocomplete_label', 'value' : value});
		
		$(elem).on('keyup', function(e){

			if( e.keyCode != 9 && e.keyCode != 13 )
				$(newElem).val( this.value );
		});

		$( elem ).autocomplete({
			'source': source,
			'minLength' : 3,
			'select' : function (event, ui) {
				return Project.Autocomplete.method(this, newElem, ui.item);
			}
		});
	}
};

$(window).on('load', function(){

	$('.autocomplete').each(function(k, elem){

		Project.Autocomplete.elements( elem );
	});
});