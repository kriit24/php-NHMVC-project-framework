<?
namespace Model\Role;

class Form extends \Library{

	const SUBMIT = array(
		'add' => 'addRole',
		'update' => 'updateRole'
	);

	public function AddRoleForm(){

		$form = new \Library\Form( 'list' );

		$form->addElem('form', '', array(
			'class' => 'add-role-2',
			'style' => ($_POST[Form::SUBMIT['add']] ? '' : 'display:none;')
		));

		$form->addElem('text', 'name', array(
			'label' => $this->Language('Name')
		));
		$form->addElem('text', 'level', array(
			'label' => $this->Language('Level')
		));
		$form->addElem('text', 'type', array(
			'label' => $this->Language('Type')
		));
		$form->addElem('textarea', 'description', array(
			'label' => $this->Language('Description')
		));
		$form->addElem('submit', Form::SUBMIT['add'], array(
			'class' => 'btn btn-primary',
			'value' => $this->Language('Add role')
		));
		
		//use next lines in view
		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->setData( $_POST );
		$form->toString();
	}

	public function RoleForm(){

		$form = new \Library\Form( 'row' );
		$form->addElem('form', '');
		$form->addElem('data', 'name', array(
			'label' => $this->Language('Name')
		));
		$form->addElem('data', 'level', array(
			'label' => $this->Language('Level')
		));
		$form->addElem('data', 'type', array(
			'label' => $this->Language('Type')
		));
		$form->addElem('data', 'description', array(
			'label' => $this->Language('Description')
		));
		$form->addElem('data', Form::SUBMIT['update']);
		return $form;
	}

	public function RoleEditForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('data', 'name');
		$form->addElem('text', 'level');
		$form->addElem('text', 'type');
		$form->addElem('textarea', 'description');
		$form->addElem('submit', Form::SUBMIT['update'], 'Update');
		$form->addElem('a', '', array(
			'href' => './',
			'value' => $this->Language('Cancel'),
			'class' => 'edit',
			'style' => 'margin-left:5px;'
		))->after(Form::SUBMIT['update']);
		return $form;
	}
}

?>