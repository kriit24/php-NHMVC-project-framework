var allowFilepickerClose = true;

$(document).ready(function(){

	Project.Tabs('#tabs').tabs();
	var i = 1;

	$('.color-picker').each(function(){

		var color = this.value.length > 0 ? this.value : '#000000';

		$(this).attr({'id' : i+'-input'});

		$(this).after(
		'<div class="color-selector" id="' + i + '-div" style="display:none;position:absolute;background:#ffffff;padding:20px;border:1px solid #f7f7f7;z-index:10;">'+
			'<input type="color" id="' + i + '-colorinput" class="form-control" style="width:55px;cursor:pointer;" value="' + color + '">'+
		'</div>'
		);

		i++;
	});

	$('.color-picker').live('click', function(){

		var id = $(this).attr('id').replace('-input', '');

		$('.color-selector').not('.color-selector#' + id+ '-div').hide();
		$('.color-selector#' + id+ '-div').toggle();
		if( this.value.length == 0 )
			$(this).val('#000000');
	});

	$('.file-picker,.filepicker-div').live('click', function(){

		allowFilepickerClose = false;
		$(this).parent('td').find('.filepicker-div').show();
	});

	$('.filepicker-div').change(function(){

		$(this).parent('td').find('.file-picker').val( $('select', this).val() );
		$('select', this).find('option:selected').removeAttr('selected');
	});

	$('input[type="color"]').live('change', function(){

		var id = this.id.replace('-colorinput', '');
		$('#' + id+'-input').val( this.value );
		$('.color-selector#' + id + '-div').hide();
	});

	$(document.body).click(function(){

		if( allowFilepickerClose )
			$('.filepicker-div').hide();

		allowFilepickerClose = true;
	});
});