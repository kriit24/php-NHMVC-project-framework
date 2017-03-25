<?
namespace {namespace};

class Index extends \Library{

	public function AddForm(){

		$form = new \Library\Form('list');

		$form->addElem('form', '', array(
			'' => '',
		));

		{column_elems_form}

		$form->addElem('submit', 'add', array(
			'value' => $this->Language('Add'),
			'' => '',
		));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData($_POST);
		$form->toString();
	}

	public function ListForm(){

		$form = new \Library\Form('row');

		{column_elems_data}

		return $form;
	}
}

?>