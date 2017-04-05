<?
namespace {namespace};

class Index extends Controller{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );

		$this->Validate = new Validate;
		parent::__construct();
	}

	protected function Index(){

		$this->POST('add{uname}')->action();

		$this->getData()->view('Index');
	}

	protected function Index_Admin(){

		$this->POST('add{uname}')->action();

		$this->getData()->view('Index_Admin');
	}

	/*public function Dashboard(){

		$this->view('Dashboard');
	}*/
}

?>