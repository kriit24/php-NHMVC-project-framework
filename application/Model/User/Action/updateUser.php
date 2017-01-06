<?
namespace Model\User\Action;

abstract class updateUser{

	public static function init($data, $id){

		$data['password'] = $data['password'] ? md5($data['password']) : \NULL;
		$data['password_expires_at'] = $data['password_expires_at'] ?: 'NULL';
		$data['account_expires_at'] = $data['account_expires_at'] ?: 'NULL';
		$data['is_enabled'] = $data['is_enabled'] ?: 0;
		\Table\user::singleton()->Update($data, array('id' => $id));
		\Table\client::singleton()->Update($data, array('user_id' => $id));

		if( $id == \Session::userData()->user_id ){

			$row = \Table\user::singleton()->Select()
			->column(array(
				"u.*",
				'u.id' => 'user_id',
				'u.name' => 'user_name',
				'CASE WHEN u.password_expires_at <= NOW() AND u.password_expires_at IS NOT NULL THEN TRUE ELSE FALSE END' => 'is_password_expired', 
				'r.level',
				'r.type',
				'c.*',
				'c.id' => 'client_id'
			))
			->from('user', 'u')
			->join('client', 'c', 'c.user_id = u.id')
			->join('role', 'r', 'r.id = u.role_id')
			->where(array('u.id' => $id))
			->fetch();

			\Session::userData( $row );
		}
	}
}
?>