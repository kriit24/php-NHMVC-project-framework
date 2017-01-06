<div class="dropdown dropdown-group">

	<div class="dropdown-menu dropdown-minimenu"><h6 class="dropdown-header"><a class="btn btn-primary"><i class="fa fa-bars"></i></a></h6></div>
	<div class="dropdown-maximenu">

	<?
		echo '<div class="dropdown-menu'.(!$_GET['route'] ? ' active' : '').'">
			<h6 class="dropdown-header"><a href="/">'.$this->Language('Firstpage').'</a></h6>';
		echo '</div>';

		echo '<div class="dropdown-menu'.(in_array(strtolower($_GET['view']), array('api')) ? ' active' : '').'">
			<h6 class="dropdown-header"><a href="'.$this->url(array('model' => 'Page', 'view' => 'Api')).'">'.$this->Language('Api').'</a></h6>';
		//$this->Submenu('public/User_interface');
		echo '</div>';

		if( $this->permission(array('ADMIN', 'SUPERADMIN')) ){
		
			echo '<div class="dropdown-menu">
				<h6 class="dropdown-header"><a href="/admin" target="_blank">'.$this->Language('Admin').'</a></h6>';
			echo '</div>';
		}
	?>
	</div>
</div>