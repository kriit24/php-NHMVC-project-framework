<?
namespace Model\Privilege;

class Controller extends \Library{

	const SCANDIR = _DIR.'/application';
	var $privileges = array();

	public function __construct(){

		$this->privilege = new \Table\privilege;
		$this->role = new \Table\role;
	}

	public function getRoles(){

		$this->role->Select()->where( array('level <= ? ' => \Session::userData()->level, 'level != 10') );
		return $this;
	}

	public function getPrivilege(){

		$rows = $this->privilege->Select()
			->column(array('id', 'role_id', '(SELECT name FROM role WHERE id = privilege.role_id)' => 'role_name', 'route', 'class', 'method'))
			->order("role_id, route, class, method")->fetchAll();
		
		$privileges = array();
		if( !empty($rows) ){

			foreach($rows as $row){

				$privileges[$row['role_id']][$row['route']][$row['class']][$row['method']] = true;
			}
		}
		$this->privileges = $privileges;

		return $this;
	}

	public function getClassListing( $route = null ){

		$scandir = self::SCANDIR.'/'.$route;
		$classRows = $this->scandir( $scandir, false, '', true );
		$ret = array();
		foreach($classRows as $className){

			$rows = $this->getMethodListing($route, $className);
			if( !empty($rows) && count($rows) > 0 ){

				$ret[] = array('class' => $className, 'methods' => $rows);
			}
		}
		return $ret;
	}

	public function getMethodListing($route, $className){

		$class = '\\'.$route.'\\'.$className.'\\_Abstract';
		$register = $class::register();
		$array = array();
		
		if( $register['admin'] ){

			foreach($register['admin'] as $method => $prop){

				if( !in_array($method, $array) )
					$array[] = $method;
			}
		}
		if( $register['public'] ){

			foreach($register['public'] as $method => $prop){

				if( !in_array($method, $array) )
					$array[] = $method;
			}
		}
		foreach($register as $method => $prop){

			if( !in_array($method, array('public', 'admin', 'method_name_without_template')) && !in_array($method, $array) )
				$array[] = $method;
		}
		return $array;
	}

	/*
	ACTIONS
	*/

	function updatePrivilege(){

		if( empty($_POST['privilege']) )
			return;

		$privilege = new \Table\privilege;

		foreach($_POST['privilege'] as $role_id => $routes){

			if( !$role_id )
				continue;

			$privilege->Delete( array('role_id' => $role_id) );

			if( empty($routes) )
				continue;

			foreach($routes as $route => $models){

				if( empty($models) )
					continue;

				if( is_Array($models) ){

					foreach($models as $model => $methods){

						if( empty($methods) )
							continue;

						foreach($methods as $method => $true){

							$privilege->Insert( array('role_id' => $role_id, 'route' => $route, 'class' => $model, 'method' => $method) );
						}
					}
				}
			}
		}
	}
}

?>