<?
namespace Model\Privilege;

class Index extends Controller{

	public function __construct(){

		$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
		$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );

		$this->Form = new Form;

		parent::__construct();
	}

	protected function Index_Admin(){

		$this->POST('updatePrivilege')->action();

		$this->getRoles()->getPrivilege()->view('Index_Admin');
	}
}

?>