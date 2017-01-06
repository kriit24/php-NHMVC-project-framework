<?
namespace Model\User;

class Controller extends \Library{

	public function setRoles(){

		\Register::set('roles', \Table\role::singleton()->getForSelect());
	}

	public function getUser( $id ){

		$this->user->userJoinClient()
			->where(array('u.id' => $id))
			->method(array($this->user, 'unsetPassword'));
		return $this;
	}

	public function getUsers(){

		$this->user->getByFilter($_GET, 100);
		return $this;
	}

	public function addUser(){

		$userName = trim($_POST['first_name'].'.'.$_POST['last_name']);

		if( Action\addUser::init($_POST, $userName) ){

			$this->message(
				'User inserted<br>'.
				'Username: {username}<br>'.
				'Password: {password}',
				array('username' => $userName, 'password' => $_POST['password'])
			);
		}
	}

	public function updateUser(){

		Action\updateUser::init($_POST, $_GET['id']);
	}

	public function updateAccount(){

		if( $_GET['id'] == \Session::userData()->user_id )
			$this->updateUser();
	}

	public function deleteUser(){

		Action\deleteUser::init($_GET['id']);
	}
}

?>