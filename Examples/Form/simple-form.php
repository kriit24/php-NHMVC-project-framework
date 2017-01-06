FORM

<?
namespace Model\Privileges;

class Form extends \Library{

	const SUBMIT = array(
		'add' => 'addPrivilege',
	);
	const roles = array('role 1', 'role 2');

	function addPrivilegeForm(){

		$form = new \Library\Form( 'list'/*row*/ );
		$form->addElem('form');
		$form->addElem('text', 'name', array(
			'label' => 'Name'
		));
		$form->addElem('select', 'role_id', array(
			'label' => 'Role',
			'option' => self::roles
		))
		->selected($_POST['role_id']);
		$form->addElem('submit', Form::SUBMIT['add'], 'Add privileges');

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData($_POST);
		$form->toString();
	}
}

?>


VIEW

<?

$this->Form->addPrivilegeForm();

?>