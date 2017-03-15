<div class="dropdown-submenu">
	<?
	if( \Library\Permission::get( 'Model', 'Loan', 'Product' ) ){
	?>
		<a class="dropdown-item" href="<?=$this->url(array('model' => 'Loan', 'method' => 'Product'));?>"><?=$this->Language('Laenu tooted');?></a>
	<?
	}
	?>
</div>