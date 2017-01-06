<?
namespace Model\Role;

class Controller extends \Library{

	public function getData(){

		$this->role->Select();
		return $this;
	}

	public function addRole(){

		$this->role->fetch($_POST);

		if( $this->role->Numrows() == 0 ){

			$id = $this->role->fetchColumn('id', "level = ".$_POST['level']);

			$this->role->Insert($_POST);
			$newRoleId = $this->role->Insertid();

			if( !$id )
				return true;

			$rows = $this->privilege->fetchAll("role_id = ".$id);
			foreach($rows as $row){

				unset($row['id']);
				$row['role_id'] = $newRoleId;
				$this->privilege2->Insert($row);
			}
		}
		else{

			$this->error('Role allready exists');
		}
		return $this;
	}

	public function updateRole(){

		$this->role->Update($_POST, array('id' => $_GET['id']));
	}
}

?>