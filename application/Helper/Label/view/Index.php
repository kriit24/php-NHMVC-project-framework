<?
$uniqid = uniqid();
?>
<div class="ibox float-e-margins">	
	<div class="ibox-title border-dark <?=($this->appendHtml ? 'dropdown' : '');?>" for="<?=($uniqid);?>">
		<h5><a href="javascript:history.go(-1)" style="margin-right:20px;"><i class="fa fa-arrow-left"></i></a><?=$this->Language( $this->label )?></h5>

		<?
		if( $this->appendHtml ){
			?>
		<div class="ibox-tools">
			<a class="collapse-link">
				<i class="fa fa-chevron-down"></i>
			</a>
		</div>
		<?
		}
		?>
	</div>

	<div class="ibox-content border <?=($uniqid);?>" style="<?=( !empty($this->getError()) || !empty($this->getError()) ? 'display:block;' : 'display:none;' );?>padding:0px;">
		<? $this->appendHtml; ?>
	</div>
</div>