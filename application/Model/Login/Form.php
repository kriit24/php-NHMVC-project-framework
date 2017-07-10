<?
namespace Model\Login;

class Form extends \Library{

	public function LoginForm(){

		$form = new \Library\Form( 'row' );
		$form->addElem('form', '', array('class' => 'login-content-box'));
		$form->addElem('hidden', 'action', 'login')->append('form');
		$form->addElem('hidden', 'session_id', session_id())->append('form');
		if( $this->getError( 'login-error' ) ){

			$form->addElem('span', 'show_error', array(
				'value' => $this->getError( 'login-error' ),
				'class' => 'login-error'
			));
		}
		$form->addElem('text', 'user', array('value' => $_POST['user']));
		$form->addElem('password', 'password');
		$form->addElem('submit', 'login', $this->Language('Login'));

		$form->setData(array());

		$form->toString( array('table' => array('class' => 'login-table')) );
	}
}

?>