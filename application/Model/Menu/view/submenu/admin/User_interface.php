<div class="dropdown-submenu">
	<? if( \Library\Permission::get( 'Model', 'Privilege', 'Index_Admin' ) ){ ?>
	<a class="dropdown-item" href="<?=$this->url(array('model' => 'Privilege'));?>"><?=$this->Language('Privileges');?></a>
	<? } ?>

	<? if( \Library\Permission::get( 'Model', 'User', 'Index_Admin' ) ){ ?>
	<a class="dropdown-item" href="<?=$this->url(array('model' => 'User'));?>"><?=$this->Language('User');?></a>
	<? } ?>

	<? if( \Library\Permission::get( 'Model', 'Role', 'Index_Admin' ) ){ ?>
	<a class="dropdown-item" href="<?=$this->url(array('model' => 'Role'));?>"><?=$this->Language('Role');?></a>
	<? } ?>
</div>