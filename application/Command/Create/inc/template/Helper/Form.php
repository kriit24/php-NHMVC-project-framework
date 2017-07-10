<?
namespace {namespace};

class Form extends \Library{

	const SUBMIT = array(
		'add' => 'add{uname}',
		'update' => 'update{uname}'
	);

	public function IndexForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'action' => ''
		));

		$form->addElem('text', 'name', array(
			'label' => 'Name',
			'value' => $_POST['name']
		));

		$form->addElem('submit', Form::SUBMIT['add'], array(
			'value' => _tr(Form::SUBMIT['add'])
		));

		$form->addElem('a', '', array(
			'href' => './',
			'value' => _tr('Cancel')
		))->after(Form::SUBMIT['add']);

		//use next lines in view
		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData( $_POST );
		$form->toString();
	}
}

?>