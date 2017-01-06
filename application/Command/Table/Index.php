<?
namespace Command\Table;

class Index extends Controller{

	const ACTIONS = array('Create', 'Install', 'Update');

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );
	}

	protected function Index_Admin(){

		$this->create( ucfirst($_GET['action']) );

		$this->indexData()->view('Index');
	}

	protected function Check(){
		
		//we dont need this in live server
		if( \Conf\Conf::_DEV_MODE == true && $_GET['command'] != 'Table' )
			$this->checkInstalledTables()->view('Check-tables');
	}

	public static function shell($command){

		$c = new Controller;
		$c->command = $command;

		$c->view('shell');

		$_GET['action'] = $c->command['-a'];
		$_GET['table'] = $c->command['-t'];

		$c->create( $c->command['-a'] );
		$c->view('shell-msg');
	}
}

?>