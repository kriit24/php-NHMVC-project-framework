var getModelNames = function(elem, checked){

	//$('select[name="model_name"]').empty();

	if( $(elem).val() == 'Method' || $(elem).val() == 'Action' ){

		$.getJSON( "/Command/Create/getModels/" + '?folder='+$('input[name="folder"]').val(), function( data ) {

			$.each(data, function(k, v){

				var optionSelected = false;
				if( v == checked )
					optionSelected = true;

				$('select[name="model_name"]').append( new Option(v,v,false,optionSelected) );
			});
		});
	}
};

var getColumns = function( value ){

	$.get('/Command/Create/getColumns/table/' + value, function(data){

		var selectedColumns = {};
		var table = value;

		$.canJSON(selected_columns, function(rows){

			if( rows[table] ){

				$.each(rows[table], function(k, v){

					selectedColumns[ v ] = true;
				});
			}
		});

		$.canJSON(data, function(rows){

			$.each(rows, function(key, value){

				var uniqid = value;
				var checked = typeof selectedColumns[value] != 'undefined' ? 'checked="checked"' : '';

				var html = '<div>'+
					'<input type="checkbox" name="table_column[]" value="'+value+'" '+checked+' id="table_column_'+uniqid+'" style="visibility: hidden; margin-bottom: 10px; display: inline-block; width: 15px;">'+
					'<label for="table_column_'+uniqid+'" class="checkbox"><span></span>'+value+'</label>'+
				'</div>';

				$('.table-column').append( html );
			})
		});
	});
};

$(document).ready(function(){

	$('input[name="folder"]').change(function(){

		$('select[name="model_name"]').empty();

		$('select[name="create"] option:selected').removeAttr('selected');
		$('select[name="create"]').removeAttr('disabled');
		if( this.value == 'Cron' ){

			$('select[name="create"]').attr('disabled', 'disabled');
		}
	});

	$('input[name="addTableColumns"]').click(function(){

		var tableColumns = 'table=' + $(this).parents('form').find('select[name="table"]').val();
		$(this).parents('form').find('input[name="table_column[]"]:checked').each(function(){

			tableColumns += (tableColumns ? '&' : '') + this.name + '=' + this.value;
		});

		$.post( '/Command/Create/saveColumns/', tableColumns, function(data){

			//console.log(data);
		} );
		return false;
	});

	$('select[name="create"]').change(function(){

		getModelNames(this, '');
	});

	$('select[name="table"]').change(function(){

		$('.table-column').html( '' );
		getColumns( this.value );
	});

	if( $_POST['addMethod'] ){

		getColumns( $_POST['table'] );
	}
});