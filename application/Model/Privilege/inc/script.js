$(document).ready(function(){

	var setClass = function(){

		var routeName = $('[name="route"]').val();
		$('[name="class"]').find('option').remove();
		$.get( classUrl + '?routename=' + routeName + '&role_id=' + $('select[name="role_id"]').val(), function(data){

			$.canJSON(data, function(data){

				$('[name="class"]').append(
					$('<option></option>').val('').html('Select')
				);

				$.each(data, function(key, value){

					if( $._POST['class'] == value ){

						$('[name="class"]').append(
							$('<option selected="selected"></option>').val(value).html(value)
						);
					}
					else{

						$('[name="class"]').append(
							$('<option></option>').val(value).html(value)
						);
					}
				});
			});
		});
	};

	var setMethod = function(){

		var routeName = $('[name="route"]').val();
		var className = $('[name="class"]').val();
		$('[name="method"]').find('option').remove();
		if( !className )
			return true;
		$.get( methodUrl + '?routename=' + routeName + '&classname=' + className + '&role_id=' + $('select[name="role_id"]').val(), function(data){

			$.canJSON(data, function(data){

				$.each(data, function(key, value){

					$('[name="method"]').append(
						$('<option></option>').val(value).html(value)
					);
				});
			});
		});
	};

	setClass();
	setTimeout(function(){

		setMethod();
	}, 300);

	$('[name="route"]').on('change', function(){

		setClass();
		setMethod();
	});
	$('[name="class"]').on('change', function(){

		setMethod();
	});
	$('a.add-privilege').ptoggle(400);
	$('a.clone-privilege').ptoggle(400);
});