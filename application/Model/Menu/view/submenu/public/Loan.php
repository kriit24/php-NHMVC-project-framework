<div class="dropdown-submenu">
	<?
	if( \Library\Permission::get( 'Model', 'Loan', 'Product' ) ){
	?>
		<a class="dropdown-item" href="<?=$this->url(array('model' => 'Loan', 'method' => 'Product'));?>"><?=_tr('Laenu tooted');?></a>
	<?
	}
	?>
</div>