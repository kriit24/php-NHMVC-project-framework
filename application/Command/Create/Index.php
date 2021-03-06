<?
namespace Command\Create;

class Index extends Controller{

	public function __construct(){

		$this->inc( __DIR__ . '/inc/script.js' );

		$this->Form = new Form;
		$this->Validate = new Validate;
	}

	protected function Index_Admin(){

		if( $this->Validate->isValidAddMethodAction() )
			$this->POST('addMethod')->action();

		$this->view('Index_Admin');
	}

	protected function getModels(){

		$this->getModelsByFolder()->view('getModelsByFolder');
	}

	protected function getColumns(){

		$this->getColumnsData();
	}

	protected function saveColumns(){

		$this->saveColumnsData();
	}

	public static function shell($command){

		$c = new Controller;
		$c->command = $command;

		$c->view('shell');

		$_POST['folder'] = $c->command['-d'];
		$_POST['create'] = $c->command['-t'];
		$_POST['name'] = $c->command['-c'];
		$_POST['model_name'] = $c->command['-m'];

		//pre($_POST);

		$c->create( $_POST['create'] );
		$c->view('shell-msg');
	}
}

?>