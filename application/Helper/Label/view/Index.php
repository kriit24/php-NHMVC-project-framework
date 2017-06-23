<?
$uniqid = uniqid();
?>
<div class="ibox float-e-margins">
	<a href="javascript:history.go(-1);" style="margin-top:15px;position:absolute;z-index:10;font-size:20px;"><i class="fa fa-arrow-left"></i></a>
	<div class="ibox-title border-dark <?=($this->appendHtml ? 'dropdown' : '');?>" for="<?=($uniqid);?>">
		<h5 style="padding-left:10px;"><?=$this->Language( $this->label )?></h5>

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
		<? if( gettype($this->appendHtml) == 'array' ) call_user_func($this->appendHtml); else echo $this->appendHtml; ?>
	</div>
</div>