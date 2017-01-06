<?
namespace Model\Privilege;

class Index extends Controller{

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
		//$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );

		$this->Form = new Form;

		$this->privilege = new \Table\privilege;
		$this->role = new \Table\role;
	}

	protected function Index_Admin(){

		$this->Form->setRole(
			$this->getRole()
		);

		if( $this->Form->isValidaddPrivilege() )
			$this->POST(Form::SUBMIT['add'])->action();
		$this->POST(Form::SUBMIT['update'])->action();
		$this->GET('action=deletePrivilege')->action();

		$this->getPrivilege()->view('Index');
	}

	protected function getClass(){

		echo $this->getClassListing();
	}

	protected function getMethod(){

		echo $this->getMethodListing();
	}
}

?>