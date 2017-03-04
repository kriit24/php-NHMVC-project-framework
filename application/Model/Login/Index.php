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

	protected function BoxLeftPublic(){

		$this->view( $this->getView() . '-left-public' );
	}

	protected function BoxRightPublic(){

		$this->view( $this->getView() . '-right-public' );
	}

	protected function BoxLeft(){

		$this->view( $this->getView() . '-left' );
	}

	protected function BoxRight(){

		$this->view( $this->getView() . '-right' );
	}

	protected function LoginBox(){

		$this->view( 'Login-box' );
	}
}

?>