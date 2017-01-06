	<style>
	body > .container-fluid:first-child{
		display:table;
		height:100%;
	}

	body > .container-fluid > .container-fluid.content-bar{
		display:table;
		height:98%;
	}

	body > .container-fluid > .container-fluid.content-bar > .row{
		display:table-row;
		text-align:center;
		padding:0px;
	}
	body > .container-fluid > .container-fluid.content-bar > .row > .col{
		display:table-cell;
		padding:0px;
		min-width:300px;
	}
	.wrong-login input[type="text"], .wrong-login input[type="password"]{
		border:1px solid red;
	}
	</style>
	
	<div class="login-box">

		<h1 class="logo-name"><img src="/Template/admin/images/logo-icon.png" alt="IT Project Partner NHMVC"> <span style="vertical-align:bottom;">Project NHMVC</span></h1>

		<form class="m-t<?=($_POST['action'] == 'login' && !\Session::userData()->logged ? ' wrong-login' : '');?>" role="form" method="POST">
			<input type="hidden" name="action" value="login"/>
			<input type="hidden" name="session_id" value="<?=session_id();?>"/>
			<div class="form-group">
				<input type="text" name="user" class="form-control" placeholder="<?=$this->Language( 'UserName' );?>" required="">
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="<?=$this->Language( 'Password' );?>" required="">
			</div>
			<input type="submit" name="login" class="btn btn-primary block full-width m-b" value="<?=$this->Language( 'LOG IN' );?>">
		</form>
	</div>