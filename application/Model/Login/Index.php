<?
namespace Model\Login;

class Index extends Controller{

	public function __construct(){

		$this->inc( __DIR__ . '/inc/script.js' );
		$this->inc( __DIR__ . '/inc/style.css' );
		if( !\Session::userData()->logged )
			$this->inc( '/Template/css/clear-content-bar.css' );

		$this->Form = new \Library\Form('');
	}

	protected function LoggedBoxLeftPublic(){

		$this->view( $this->getView() . '-left-public' );
	}

	protected function LoggedBoxRightPublic(){

		$this->view( $this->getView() . '-right-public' );
	}

	protected function LoggedBoxLeftAdmin(){

		$this->view( $this->getView() . '-left' );
	}

	protected function LoggedBoxRightAdmin(){

		$this->view( $this->getView() . '-right' );
	}

	protected function LoginBoxAdmin(){

		$this->view( 'Login-box' );
	}
}

?>