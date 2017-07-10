<?
namespace {namespace};

class Index_Admin extends \Library{

	public function AddForm(){

		$form = new \Library\Form('list');

		$form->addElem('form', '', array(
			'' => '',
		));

		$form = $this->Form( $form, 'text' );

		$form->addElem('submit', 'add{uname}', array(
			'value' => _tr('Add'),
			'' => '',
		));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData($_POST);
		$form->toString();
	}

	public function UpdateForm(){

		$form = new \Library\Form('list');

		$form->addElem('form', '', array(
			'' => '',
		));

		$form = $this->Form( $form, 'text' );

		$form->addElem('submit', 'update{uname}', array(
			'value' => _tr('Update'),
			'' => '',
		));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData($_POST);
		$form->toString();
	}

	public function IndexForm(){

		$form = new \Library\Form('row');

		{column_elems_form}

		return $form;
	}
}

?>