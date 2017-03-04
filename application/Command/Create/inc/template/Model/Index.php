<?
namespace {namespace};

class Index extends Controller{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );

		$this->Validate = new Validate;

		//$this->tableName = new \Table\tableName;
	}

	protected function Index(){

		if( $this->Validate->validateIndexData() )
			$this->POST('add')->action();

		$this->getData()->view('Index');
	}

	protected function Index_Admin(){

		if( $this->Validate->validateIndexData() )
			$this->POST('add')->action();

		$this->getData()->view('Index_Admin');
	}

	/*public function Dashboard(){

		$this->view('Dashboard');
	}*/
}

?>