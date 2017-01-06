<?
namespace Command\Form;

class Index extends Controller{

	public function __construct(){

		//$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
		$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );

		$this->Form = new Form;
		$this->Validate = new Validate;
	}

	protected function Index_Admin(){

		if( $this->Validate->isValidAddColumnAction() )
			$this->POST(Form::FORM['addColumn'])->action();

		if( $this->Validate->isValidUpdateColumnAction() )
			$this->POST(Form::FORM['updateColumn'])->action();

		if( $this->Validate->isValidAddFormAction() )
			$this->POST(Form::FORM['add'])->action();

		$this->GET('action=deleteColumn')->action();
		$this->GET('action=clearForm')->action();

		$this->view('Index_Admin');
	}
}

?>