<?
namespace Model\User;

class Validator extends \Library{

	private $roles = array();

	public function __construct(){

		$this->roles = \Register::get('roles');
	}

	public function userExists(){

		$userName = trim($_POST['first_name'].'.'.$_POST['last_name']);

		if( \Table\user::singleton()->fetchNumrows(array('name' => $userName)) ){

			$this->error('User allready exists');
			return false;
		}
		return true;
	}

	public function emailExists(){

		$client = new \Table\client;

		if( $_GET['id'] ){

			if( $client->fetch(array('email = ?' => $_POST['email'], 'user_id != ?' => $_GET['id'])) ){

				$this->error('Email allready exists');
				return false;
			}
		}
		else{

			if( $client->fetch(array('email = ?' => $_POST['email'])) ){

				$this->error('Email allready exists');
				return false;
			}
		}
		return true;
	}

	public function isValidUser(){

		$validate = new \Library\Validate;
		$validate->in_array('role_id', $this->arrayValues($this->roles, 'id'));
		$validate->is_set('first_name');
		$validate->is_set('last_name');
		$validate->is_email('email');
		$validate->is_set('password');
		$validate->is_password('password', 6);
		$validate->is_set('password_again');
		$validate->is_equal('password', 'password_again')->message('Passwords not equal');
		$validate->validator('user', array(
			$this, 'userExists'
		));
		$validate->validator('email', array(
			$this, 'emailExists'
		));
		return $validate->isValid(Form::SUBMIT['add'], $_POST);
	}

	public function isValidUserEdit(){

		$validate = new \Library\Validate;
		$validate->in_array('role_id', $this->arrayValues($this->roles, 'id'));
		$validate->is_email('email');
		if( $_POST['password'] ){

			$validate->is_password('password', 6);
			$validate->is_set('password_again');
			$validate->is_equal('password', 'password_again')->message('Passwords not equal');
		}
		$validate->validator('email', array(
			$this, 'emailExists'
		));
		return $validate->isValid(Form::SUBMIT['update'], $_POST);
	}
}

?>