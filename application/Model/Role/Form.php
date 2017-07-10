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
			'label' => _tr('Name')
		));
		$form->addElem('text', 'level', array(
			'label' => _tr('Level')
		));
		$form->addElem('text', 'type', array(
			'label' => _tr('Type')
		));
		$form->addElem('textarea', 'description', array(
			'label' => _tr('Description')
		));
		$form->addElem('submit', Form::SUBMIT['add'], array(
			'class' => 'btn btn-primary',
			'value' => _tr('Add role')
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
			'label' => _tr('Name')
		));
		$form->addElem('data', 'level', array(
			'label' => _tr('Level')
		));
		$form->addElem('data', 'type', array(
			'label' => _tr('Type')
		));
		$form->addElem('data', 'description', array(
			'label' => _tr('Description')
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
			'value' => _tr('Cancel'),
			'class' => 'edit',
			'style' => 'margin-left:5px;'
		))->after(Form::SUBMIT['update']);
		return $form;
	}
}

?>