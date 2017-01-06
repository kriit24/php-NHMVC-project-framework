<?
namespace Model\User;

class Index extends Controller{

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
		//$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );

		$this->setRoles();

		$this->Form = new Form;
		$this->Validator = new Validator;
		$this->Paginator = new \Helper\Paginator;
		$this->Filter = new \Helper\Filter;

		$this->user = new \Table\user;
		$this->role = new \Table\role;
		$this->client = new \Table\client;
	}

	protected function Edit(){

		if( $this->Validator->isValidUserEdit() )
			$this->POST(Form::SUBMIT['update'])->action();

		$this->getUser( $_GET['id'] )->view('Edit');
	}

	protected function Index_Admin(){

		if( $this->Validator->isValidUser() )
			$this->POST(Form::SUBMIT['add'])->action();
		$this->GET('action=deleteUser')->action();

		$this->getUsers()->view('Index');
	}

	protected function Account(){

		$this->POST(Form::SUBMIT['updateAccount'])->action();

		$this->getUser( \Session::userData()->user_id )->view('Account');
	}
}

?>