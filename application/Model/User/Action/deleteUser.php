<?
namespace Model\User\Action;

abstract class deleteUser{

	public static function init($id){

		\Table\client::singleton()->Delete(array('user_id' => $id));
		\Table\user::singleton()->Delete(array('id' => $id));
	}
}
?>