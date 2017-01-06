<?
namespace Model\User\Action;

abstract class addUser{

	public static function init($data, $userName){

		$user = new \Table\user;
		$client = new \Table\client;

		$data = $user->prepareUserData($_POST, $userName);
		$user->Insert($data);

		$client->user_id($user->Insertid());
		$client->Insert($_POST);
		return true;
	}
}

?>