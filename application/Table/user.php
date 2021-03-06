<?
namespace Table;

class user extends \Library\Sql{

	protected $_name = 'user';
	protected $_validFields = array('id', 'role_id', 'name', 'password', 'tmp_password', 'type', 'is_enabled', 'password_expires_at', 'account_expires_at', 'activation_hash');
	protected $_integerFields = array('id', 'role_id', 'is_enabled');

	public function __construct(){

		$this->role = new \Table\role;
	}


	public function userExists($where){

		$this->Select()
			->where(array_keys($where), $where)
			->fetch();
		if( $this->Numrows() > 0 )
			return true;
		return false;
	}

	public function unsetPassword($row){

		unset($row['password']);
		return $row;
	}

	public function prepareUserData($data){

		$roleType = $this->role->Select()
			->where("id = ".$data['role_id'])
			->fetchColumn('name');

		return array_merge($data, array(
			'name' => $data['first_name'] . '.' . $data['last_name'],
			'password' => md5($data['password']),
			'password_expires_at' => !$data['password_expires_at'] ? null : date('Y-m-d', strtotime($data['password_expires_at'])),
			'account_expires_at' => !$data['account_expires_at'] ? null : date('Y-m-d', strtotime($data['account_expires_at'])),
			'type' => $roleType
		));
	}

	public function userJoinClient(){

		return $this->Select()
		->column(
			array('u.*', "'******'" => 'password', 'u.name' => 'user_name'),
			array('c' => \Table\client::singleton()->getColumns( array('id') ))
		)
		->from($this->_name, 'u')
		->join('client', 'c', 'c.user_id = u.id');
	}

	public function getByFilter($data, $paginator = 50){

		return $this->userJoinClient()
		->filter(array(
			'first_name LIKE %:first_name%',
			'last_name LIKE %:last_name%',
			'name LIKE %:user_name%',
			'email LIKE %:email%',
			'type = :type',
		), $data)
		->paginator($paginator);
	}
}

?>