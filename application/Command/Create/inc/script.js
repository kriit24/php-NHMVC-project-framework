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

$(document).ready(function(){

	$('input[name="folder"]').change(function(){

		$('select[name="model_name"]').empty();

		$('select[name="create"] option:selected').removeAttr('selected');
		$('select[name="create"]').removeAttr('disabled');
		if( this.value == 'Cron' ){

			$('select[name="create"]').attr('disabled', 'disabled');
		}
	});

	$('select[name="create"]').change(function(){

		getModelNames(this, '');
	});
});