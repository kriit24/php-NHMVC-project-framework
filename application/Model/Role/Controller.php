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

			while($row = $this->privilege->fetch("role_id = ".$id)){

				unset($row['id']);
				$row['role_id'] = $newRoleId;
				$this->privilege2->Insert($row);
			}
		}
		return $this;
	}

	public function updateRole(){

		$this->role->Update($_POST, array('id' => $_GET['id']));
	}
}

?>