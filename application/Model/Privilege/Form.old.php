<?
namespace Model\Privilege;

class Form extends \Library{

	const ROUTE = array('Api', 'Model');
	const SUBMIT = array(
		'add' => 'addPrivilege',
		'clone' => 'clonePrivilege',
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
		$form->addElem('span', '', array(
			'value' => 'This change will overwrite method default privileges',
			'class' => 'privilege-announce'
		))->append('form');
		$form->addElem('span', '', array(
			'value' => 'This changes will not overwrite SUPERADMIN privileges',
			'class' => 'privilege-announce'
		))->append('form');

		$form->addElem('select', 'role_id', array(
			'label' => $this->Language('Role'),
			'option' => $this->roles
		))->selected($_POST['role_id']);

		$form->addElem('select', 'route', array(
			'label' => $this->Language('Route'),
			'option' => self::ROUTE
		))->selected($_POST['route']);

		$form->addElem('select', 'class', array(
			'label' => $this->Language('Class')
		));

		$form->addElem('select', 'method', array(
			'label' => $this->Language('Method')
		));

		$form->addElem('submit', Form::SUBMIT['add'], 'Add Privilege');

		if( $_POST[Form::SUBMIT['add']] ){

			$form->errorLabel( $this->getError() );
			$form->messageLabel( $this->getMessage() );
		}

		$form->setData( $_POST );
		$form->toString();
	}

	public function clonePrivilegeForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'style' => ($_POST[Form::SUBMIT['clone']] ? '' : 'display:none;'), 
			'class' => 'clone-privilege'
		));
		$form->addElem('select', 'from', array(
			'label' => $this->Language('Role'),
			'option' => $this->roles
		))->selected($_POST['from']);

		$form->addElem('select', 'to', array(
			'label' => $this->Language('Role'),
			'option' => $this->roles
		))->selected($_POST['to']);

		$form->addElem('submit', Form::SUBMIT['clone'], 'Clone Privilege');

		if( $_POST[Form::SUBMIT['cone']] ){

			$form->errorLabel( $this->getError() );
			$form->messageLabel( $this->getMessage() );
		}

		$form->setData( $_POST );
		$form->toString();
	}

	public function PrivilegeForm(){

		$form = new \Library\Form( 'row' );
		$form->addElem('select', 'role_id', array(
			'label' => $this->Language('Role'),
			'option' => $this->roles
		));

		$form->addElem('data', 'route', array(
			'label' => $this->Language('Route')
		));
		$form->addElem('data', 'class', array(
			'label' => $this->Language('Class')
		));
		$form->addElem('data', 'method', array(
			'label' => $this->Language('Method')
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

	public function validateCloneData(){

		$validate = new \Library\Validate();
		$validate->is_set('from');
		$validate->is_set('to');
		return $validate->isValid(Form::SUBMIT['clone'], $_POST);
	}
}

?>