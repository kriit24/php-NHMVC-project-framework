<?
namespace Model\Role;

class Index extends Controller{

	public function __construct(){

		$this->inc( __DIR__ . '/inc/script.js' );
		$this->inc( __DIR__ . '/inc/style.css' );

		$this->Form = new Form;
		$this->Validate = new Validate;

		$this->role = new \Table\role;
		$this->privilege = new \Table\privilege;
		$this->privilege2 = new \Table\privilege;
	}

	protected function Index_Admin(){

		if( $this->Validate->isValidRole() )
			$this->POST(Form::SUBMIT['add'])->action();
		if( $this->Validate->isValidUpdateRole() )
			$this->POST(Form::SUBMIT['update'])->action();

		$this->getData()->view('Index');
	}
}

?>