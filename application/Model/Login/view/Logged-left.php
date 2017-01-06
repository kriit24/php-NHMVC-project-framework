<div class="logged left">
	<div><?=( \Session::userData()->first_name.' '.\Session::userData()->last_name );?></div>
	<div><a href="#" class="dropdown" id="logged-label"><?= ucfirst(strtolower( \Session::userData()->type ));?> <i class="fa fa-sort-desc"></i></a></div>
</div>
<div class="dropdown-menu logged-label">
	<a class="dropdown-item dialog" href="<?=$this->url( array('model' => 'User', 'method' => 'Account') )?>"><?=$this->Language( 'Account' )?></a>
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" href="?action=logout"><?=$this->Language('Logout');?></a>
</div>