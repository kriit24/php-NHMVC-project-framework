<?
namespace Model\User\Action;

abstract class addUser{

	public static function init(){

		$user = new \Table\user;
		$client = new \Table\client;

		$data = $user->prepareUserData($_POST, $userName);
		$user->Insert($data);

		$client->user_id( $user->Insertid() );
		$client->Insert($_POST);

		new \Library\Component\Message( _tr( 'User added: ' . $data['name'] . '<br>Password: ' . $data['password_again'] ) );
		return true;
	}
}

?>