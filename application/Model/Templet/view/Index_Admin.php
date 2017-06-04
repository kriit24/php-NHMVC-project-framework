<div id="tabs" class="tabs">
	<ul>
		<li><a href="#tabs-simple"><?=$this->Language( 'Simple' )?></a></li>
		<li><a href="#tabs-advanced"><?=$this->Language( 'Advanced' )?></a></li>
		<li><a href="#tabs-style"><?=$this->Language( 'Style' )?></a></li>
		<li><a href="#tabs-restore"><?=$this->Language( 'Restore' )?></a></li>
		<li><a href="#tabs-help"><?=$this->Language( 'Help' )?></a></li>
	</ul>
	
	<div id="tabs-simple">
		<?

		$this->Form->generateForm( $this->data );

		?>
	</div>
	<div id="tabs-advanced">
		<?

		$this->Form->advancedForm( $this->files );

		?>
	</div>
	<div id="tabs-style">
		<?

		$this->Form->styleForm( $this->styleContent, $this->preStyle );

		?>
	</div>
	<div id="tabs-restore">
		<? 
		$form = $this->Form->restoreForm(); 
		foreach($this->restoreData as $data){

			$row = array('directory' => basename($data), 'name' => str_replace('_', ' ', basename($data)));
			$form->setData($row);
		}
		$form->toString();
		?>
	</div>
	<div id="tabs-help">
		<? $this->view('Help'); ?>
	</div>
</div>

<script type="text/javascript">
$('.deleteFile').confirm("<?=( $this->Language('Delete file?') );?>");

$('input[name="reUpdateDesign"]').click(function(){

	if( $('input[name="from_url"]').val().length == 0 ){

		$('input[name="from_url"]').css({'border' : '1px solid red'});
		$('input[name="from_url"]').focus();
		/*
		$('html, body').animate({
			scrollTop: 0
		}, 1000);
		*/
		return false;
	}
	return true;
});
</script>