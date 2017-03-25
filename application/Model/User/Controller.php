<?
namespace Model\User;

class Controller extends \Library{

	public function setRoles(){

		\Register::set('roles', \Table\role::singleton()->getForSelect());
	}

	public function getUser( $id ){

		$this->user->userJoinClient()
			->where(array('u.id' => $id))
			->complete(array($this->user, 'unsetPassword'));
		return $this;
	}

	public function getUsers(){

		$this->user->getByFilter($_GET, 100);
		return $this;
	}

	public function updateAccount(){

		if( $_GET['id'] == \Session::userData()->user_id )
			$this->updateUser();
	}
}

?>