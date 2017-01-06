<?
namespace Command\Form;

class Controller extends \Library{

	public function addColumn(){

		Action\addColumn::init($_POST);
	}

	public function updateColumn(){

		Action\updateColumn::init($_POST['update']);
	}

	public function addForm(){

		list($msg, $err) = Action\addForm::init($_POST, \Session::formColumns());

		if( $err ){

			$this->error($err);
		}
		else{

			$this->message($msg);
			\Session::clear( 'formColumns' );
		}
	}

	function deleteColumn(){

		Action\deleteColumn::init($_GET['id']);
		die(\Library\Http::redirect($this->url(array('route' => 'Command', 'command' => 'Form'))));
	}

	function clearForm(){

		\Session::clear( 'formColumns' );
		die(\Library\Http::redirect($this->url(array('route' => 'Command', 'command' => 'Form'))));
	}
}

?>