<?

class Controller{

	function edit(){
	}

	function delete(){
	}
}

class Model extends Controller{

	function index(){

		//it will execute in class method IF ($_POST['action'] == 'edit')
		$this->POST('action=edit')->action();

		//it will execute in class method IF ($_POST['delete'])
		$this->POST('delete')->action();
	}
}

?>