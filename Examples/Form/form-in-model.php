MODEL FORM

<?
namespace Model\Privileges;

class Form extends \Library{

	const ROUTE = array('Api', 'Model');
	const SUBMIT = array(
		'add' => 'addPrivilege',
		'update' => 'updatePrivilege'
	);
	var $roles = array();

	function setRole( $role ){

		$this->roles = $role;
	}

	function addPrivilegeForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array()/*ATTRIBUTES*/);
		$form->addElem('data', 'delete', array(
			'label' => 'Delete',//if no labels then <thead> will not be created, if no label then current column label is empty,
			'label-attr' => array(),
			'value' => '<a href="?action=delete">Delete</a>'
		));
		$form->addElem('select', 'role_id', array(
			'label' => 'Role',
			'option' => $this->roles,
			'option-attr' : array()
		));
		$form->addElem('select', 'route', array(
			'label' => 'Route',
			'option' => Form::ROUTE,
			'option-attr' : array()
		));
		$form->addElem('select', 'class', array(
			'label' => 'Class'
		));

		$form->addElem('submit', Form::SUBMIT['add'], 'Add privileges');

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		//use next lines in view
		$form->setData( $_POST );
		$form->toString();
	}

	function PrivilegesForm(){

		$form = new \Library\Form( 'row' );
		$form->addElem('select', 'role_id', array(
			'label' => 'Role',
			'option' => $this->roles
		));
		$form->addElem('data', 'route', array(
			'label' => 'Route'
		));
		$form->addElem('data', 'class', array(
			'label' => 'Calss'
		));
		$form->addElem('data', 'method', array(
			'label' => 'Method'
		));
		$form->addElem('submit', Form::SUBMIT['update'], 'Update');
		return $form;
	}
}

?>


VIEW

<?

$this->Form->addPrivilegeForm();
$form = $this->Form->PrivilegesForm();

while($row = $this->privilege->Assoc()){

	$form->setData( $row );
}
$form->toString();

?>