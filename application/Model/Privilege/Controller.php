<?
namespace Model\Privilege;

class Controller extends \Library{

	const SCANDIR = _DIR.'/application';

	public function getPrivilege(){

		$this->privilege->Select()
			->column(array('id', 'role_id', '(SELECT name FROM role WHERE id = privilege.role_id)' => 'role_name', 'route', 'class', 'method'))
			->order("role_id, route, class, method");
		return $this;
	}

	private function getPrivilegeByFilter( $filter ){

		return $this->getPrivilege()->privilege->where($filter)->fetchAll();
	}

	public function getRole(){

		return $this->role->getForSelect( array('level != 10') );
	}

	public function getClassListing(){

		$scandir = self::SCANDIR.'/'.$_GET['routename'];
		$classRows = $this->scandir( $scandir, false, '', true );
		$ret = array();
		foreach($classRows as $className){

			$_GET['classname'] = $className;
			$rows = json_decode($this->getMethodListing(), true);
			if( !empty($rows) && count($rows) > 0 )
				$ret[] = $className;
		}
		return json_encode( $ret );
	}

	public function getMethodListing(){

		$rows = $this->getPrivilegeByFilter( array('role_id = ?' => $_GET['role_id'], 'route = ?' => $_GET['routename'], 'class = ?' => $_GET['classname']) );
		$methods = array();
		foreach($rows as $row){

			$methods[] = $row['method'];
		}

		$dir = self::SCANDIR.'/'.$_GET['routename'].'/'.$_GET['classname'];

		if( is_file($dir.'/index.php') )
			require_once $dir.'/index.php';
		if( is_file($dir.'/Index.php') )
			require_once $dir.'/Index.php';

		$class = '\\'.$_GET['routename'].'\\'.$_GET['classname'].'\\Index';
		$array = array();
		$reflection = \Library::reflection($class);
		foreach( $reflection->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED) as $v ){

			if( preg_match('/'.$_GET['classname'].'/i', str_replace('\\', '\\\\', $v->class)) && preg_match('/Index/i', str_replace('\\', '\\\\', $v->class)) && !in_array($v->name, array('__construct', '__destruct')) && !in_array($v->name, $methods) ){

				$array[] = $v->name;
			}
		}
		return json_encode( $array );
	}

	/*
	ACTIONS
	*/

	public function deletePrivilege(){

		$this->privilege->Delete(array('id' => $_GET['id']));
	}

	public function addPrivilege(){

		$this->privilege->fetch( $_POST );
		if( $this->privilege->Numrows() == 0 )
			$this->privilege->Insert($_POST);
	}

	public function clonePrivilege(){

		$privilege2 = clone $this->privilege;
		$privilege3 = clone $this->privilege;

		$this->privilege->Select()
			->where(array('role_id' => $_POST['from']));
		while($row = $this->privilege->fetch()){

			unset($row['id']);
			$row['role_id'] = $_POST['to'];
			$privilege2->fetch( $row );
			if( $privilege2->Numrows() == 0 ){
				
				$privilege3->Insert($row);
			}
		}
	}

	public function updatePrivilege(){

		$this->privilege->Update($_POST, array('id' => $_GET['id']));
	}
}

?>