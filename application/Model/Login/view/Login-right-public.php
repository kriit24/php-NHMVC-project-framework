<form class="login-form" role="form" method="POST">
	<input type="hidden" name="action" value="login"/>
	<input type="hidden" name="session_id" value="<?=session_id();?>"/>
	<input type="text" name="user" class="form-control" placeholder="<?=_tr( 'UserName' );?>" required="">
	<input type="password" name="password" class="form-control" placeholder="<?=_tr( 'Password' );?>" required="">
	<input type="submit" name="login" class="btn btn-primary block full-width m-b" value="<?=_tr( 'LOG IN' );?>">
</form>