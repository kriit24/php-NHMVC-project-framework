<?
namespace {namespace};

class Index extends Controller{

	public function __construct(){

		//$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
		//$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );

		//$this->Form = new Form;
		//$this->Validate = new Validate;

		//$this->tableName = new \Table\tableName;
	}

	protected function Index(){

		if( $this->Validate->isValidIndex() )
			$this->POST(Form::SUBMIT['add'])->action();

		$this->getData()->view('Index');
	}
}

?>