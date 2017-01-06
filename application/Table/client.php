<?
namespace Table;

class client extends \Library\Sql{

	const USER_FIELDS = 'c.first_name, c.last_name, c.email';

	protected $_name = 'client';
	protected $_validFields = array('id', 'user_id', 'first_name', 'last_name', 'personal_code', 'country', 'county', 'city', 'street', 'house', 'apartment', 'address', 'zip', 'email', 'web', 'phone', 'company', 'company_register', 'is_deleted', 'changed_at');
	protected $_integerFields = array('id', 'user_id', 'zip', 'is_deleted');

	public function clientExists($array){

		$this->Select()
			->where($array)
			->fetch();
		if( $this->Numrows() > 0 )
			return true;
		return false;
	}

	public function addClient( $user, $role, $data, $role_type ){

		$row = $role->Select()
			->column("id")
			->where("type = '".$role_type."' ")
			->fetch();

		$user->Insert(array_merge($data, array('role_id' => $role)));
		$this->Insert(array_merge($data, array('user_id' => $user->Insertid())));
	}
}

?>