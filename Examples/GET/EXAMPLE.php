<?

class Controller{

	function edit(){
	}

	function delete(){
	}
}

class Model extends Controller{

	function index(){

		//it will execute in class method IF ($_GET['action'] == 'edit')
		$this->GET('action=edit')->action();

		//it will execute in class method IF ($_GET['delete'])
		$this->GET('delete')->action();
	}
}

?>