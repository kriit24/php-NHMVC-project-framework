<?
namespace Model\Privilege;

class Form extends \Library{

	const ROUTE = array('Api', 'Model');
	const SUBMIT = array(
		'add' => 'addPrivilege',
		'update' => 'updatePrivilege'
	);
	var $roles = array();

	public function setRole( $role ){

		$this->roles = $role;
	}

	public function addPrivilegeForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'style' => ($_POST[Form::SUBMIT['add']] ? '' : 'display:none;'), 
			'class' => 'add-privilege'
		));
		$form->addElem('select', 'role_id', array(
			'label' => 'Role',
			'option' => $this->roles
		))->selected($_POST['role_id']);

		$form->addElem('select', 'route', array(
			'label' => 'Route',
			'option' => self::ROUTE
		))->selected($_POST['route']);

		$form->addElem('select', 'class', array(
			'label' => 'Class'
		));
		$form->addElem('select', 'method', array(
			'label' => 'Method'
		));
		$form->addElem('submit', Form::SUBMIT['add'], 'Add Privilege');

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData( $_POST );
		$form->toString();
	}

	public function PrivilegeForm(){

		$form = new \Library\Form( 'row' );
		$form->addElem('select', 'role_id', array(
			'label' => 'Role',
			'option' => $this->roles
		));

		$form->addElem('data', 'route', array(
			'label' => 'Route'
		));
		$form->addElem('data', 'class', array(
			'label' => 'Class'
		));
		$form->addElem('data', 'method', array(
			'label' => 'Method'
		));
		$form->addElem('submit', Form::SUBMIT['update'], 'Update');
		$form->addElem('a', 'delete', array(
			'value' => 'Delete',
			'class' => 'delete'
		));
		return $form;
	}

	public function isValidaddPrivilege(){

		$validate = new \Library\Validate();
		$validate->in_array('role_id', $this->arrayValues($this->roles, 'id'))->message('Not in list');
		$validate->in_array('route', self::ROUTE)->message('Not in list');
		$validate->is_set('class')->message('Class is not set');
		$validate->is_set('method')->message('Method is not set');
		return $validate->isValid(Form::SUBMIT['add'], $_POST);
	}
}

?>