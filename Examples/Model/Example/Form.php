<?
namespace Model\Example;

class Form extends \Library{

	const SUBMIT = array(
		'add' => 'addMethod',
		'update' => 'updateMethod'
	);
	const URL = array(
		'cancel' => array('model' => 'Example', 'method' => 'Example')
	);

	function ExampleForm(){

		$form = new \Library\Form;
		$form->text(array('name', 'method'), $_POST);
		$addSubmit = FORM::SUBMIT['add'];
		$form->submit($addSubmit, 'Add method');
		$form->data('link');

		$form->$addSubmit()->after( '&nbsp;&nbsp;&nbsp;<a href="'.$this->url(FORM::URL['cancel']).'">Cancel</a>' );
		$form->link('<a href="./">Cancel</a>');

		//use next lines in view
		$form->form();

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->list();
		$form->end();
	}

	function isValidExample(){

		$validate = new \Library\Validate;
		$validate->is_set('name')->message(' is required');
		$validate->is_set('method')->message(' is required');
		return $validate->isValid(FORM::SUBMIT['add'], $_POST);
	}
}

?>