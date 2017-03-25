<?
namespace Model\User\Action;

abstract class addUser{

	public static function init(){

		$userName = trim($_POST['first_name'].'.'.$_POST['last_name']);
		$data = $_POST;

		$user = new \Table\user;
		$client = new \Table\client;

		$data = $user->prepareUserData($_POST, $userName);
		$user->Insert($data);

		$client->user_id($user->Insertid());
		$client->Insert($_POST);
		
		\Library::singleton()->message(
			'User inserted<br>'.
			'Username: {username}<br>'.
			'Password: {password}',
			array('username' => $userName, 'password' => $_POST['password'])
		);
	}
}

?>