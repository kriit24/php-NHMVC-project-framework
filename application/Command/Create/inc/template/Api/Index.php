<?
namespace {namespace};

class Index extends Controller{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );

		//$this->Form = new Form;
		//$this->Validate = new Validate;
		parent::__construct();
	}

	protected function Index(){

		/*
		if( $this->Validate->isValidIndex() )
			$this->POST('add')->action();
		*/
		$this->POST('add')->action();

		$this->getData()->view('Index');
	}
}

?>