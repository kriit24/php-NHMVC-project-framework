<?
namespace {namespace};

class Index extends \Library{

	public function AddForm(){

		$form = new \Library\Form('list');

		$form->addElem('form', '', array(
			'' => '',
		));

		$form = $this->Form( $form, 'text' );

		$form->addElem('submit', 'add{uname}', array(
			'value' => $this->Language('Add'),
			'' => '',
		));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData($_POST);
		$form->toString();
	}

	public function IndexForm(){

		$form = new \Library\Form('row');

		$form = $this->Form( $form, 'data' );

		return $form;
	}
}

?>