<?PHP
namespace Library;

/**
* this is private for core
*/
class Auth extends Component\isPrivate{

	function __construct( $user = '', $password = '' ){

		if( $_GET['action'] == 'logout' ){

			\Library\Session::clear('userData');
			\Library\Session::clear('initiated');
			setcookie('PHPSESSID', 'false', time()+(60*60*24*1), '/');
			setcookie('SESSION_LOGGED', 'false', time()+(60*60*24*1), '/');
			die(header('Location: ./'));
		}

		if( $_GET['user'] && $_GET['password'] && \Conf\Conf::_DEV_MODE ){

			$this->Privilege($_GET['user'], $_GET['password']);
		}
		else if( $_POST['user'] && $_POST['password'] ){

			if( session_id() == $_POST['session_id'] ){

				session_regenerate_id();
				$this->Privilege($_POST['user'], $_POST['password']);
			}
		}
		else if( $user && $password ){

			$this->Privilege($user, $password);

		}
		else if( \Conf\Conf::_DB_CONN == false ){

			$this->defaultPrivilege(array(
				'name' => 'NOT-INSTALLED',
				'level' => 0,
				'type' => 'NOT-INSTALLED'
			));
		}
		else{
			
			$this->defaultPrivilege();
		}
	}

	private function Privilege( $loginUser = '', $loginPassword = '' ){

		if( $_GET['action'] != 'login' && $_POST['action'] != 'login' )
			return false;

		$user = new \Table\user;
		$client = new \Table\client;
		$role = new \Table\role;
		$log = new \Table\log;

		if($loginUser && $loginPassword){

			$loginUser = strip_tags($loginUser);
			$loginPassword = strip_tags($loginPassword);

			$row = $user->Select()
			->column(array(
				"u.*",
				'u.id' => 'user_id',
				'u.name' => 'user_name',
				'CASE WHEN u.password_expires_at <= NOW() AND u.password_expires_at IS NOT NULL THEN TRUE ELSE FALSE END' => 'is_password_expired', 
				'r.level',
				'r.type'
			))
			->from('user', 'u')
			->join('role', 'r', 'r.id = u.role_id')
			->where("u.name = :uName AND u.password = :uPassword AND u.is_enabled = :enabled AND ( u.account_expires_at > :accountExpires OR u.account_expires_at IS NULL OR u.account_expires_at = :isZero) AND (u.password_expires_at > :passwordExpires OR u.password_expires_at IS NULL OR u.password_expires_at = :isZero)", 
			array(
				'uName' => $loginUser,
				'uPassword' => md5($loginPassword),
				'enabled' => 1,
				'accountExpires' => 'NOW()',
				'passwordExpires' => "DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 3 DAY), '%Y-%m-%d')",
				'isZero' => '0000-00-00',
			))
			->fetch();

			if( $user->Numrows() > 0 ){

				$row2 = $client->Select()
					->column(array('*', 'id' => 'client_id'))
					->where(array('user_id' => $row['id']))
					->fetch();
				if( $client->Numrows() > 0 ){

					if(is_array($row) && is_array($row2)){

						$row = array_merge( $row, $row2);
					}
					$row['name'] = $row2['first_name'].' '.$row2['last_name'];
				}
				$row['privileges'] = $this->getPrivileges( $row['role_id'] );

				\Library\Session::clear('userData');
				\Library\Session::userData($row);
				\Library\Session::userData(array('logged' => true));
				setcookie('SESSION_LOGGED', 'true', time()+(60*60*24*1), '/');

				$log->Insert(
					array(
						'table_name' => 'user', 
						'table_id' => \Library\Session::userData()->user_id, 
						'created_by' => \Library\Session::userData()->user_name,
						'created_by_id' => \Library\Session::userData()->user_id, 
						'created_at' => 'NOW()', 
						'action' => 'LOGIN'
					)
				);
			}
			else
				new \Library\Component\Error('login-error', 'Username or password dont match', true);
		}
	}

	private function defaultPrivilege( $row = array() ){

		if( \Library\Session::userData() )
			return true;

		if( empty($row) ){

			$role = new \Table\role;
			$row = $role->Select()
				->column(array('*', 'id' => 'role_id'))
				->where(array('type' => 'USER'))
				->fetch();

			$row['privileges'] = $this->getPrivileges( $row['role_id'] );
		}

		\Library\Session::userData($row);
	}

	private function getPrivileges($role_id){

		$ret = array();

		$privilege = new \Table\privilege;
		$privilege->Select()
			->column(array('*', '(SELECT type FROM role WHERE id = privilege.role_id)' => 'role_type'));
		while($row = $privilege->fetch()){

			$ret[$row['class']][$row['method']][] = $row['role_type'];
		}
		return $ret;
	}
}

?>