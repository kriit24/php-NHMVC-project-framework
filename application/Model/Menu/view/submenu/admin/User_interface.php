<div class="dropdown-submenu">
	<? if( \Library\Permission::get( 'Model', 'Privilege', 'Index_Admin' ) ){ ?>
	<a class="dropdown-item" href="<?=$this->url(array('model' => 'Privilege'));?>"><?=_tr('Privileges');?></a>
	<? } ?>

	<? if( \Library\Permission::get( 'Model', 'User', 'Index_Admin' ) ){ ?>
	<a class="dropdown-item" href="<?=$this->url(array('model' => 'User'));?>"><?=_tr('User');?></a>
	<? } ?>

	<? if( \Library\Permission::get( 'Model', 'Role', 'Index_Admin' ) ){ ?>
	<a class="dropdown-item" href="<?=$this->url(array('model' => 'Role'));?>"><?=_tr('Role');?></a>
	<? } ?>
</div>