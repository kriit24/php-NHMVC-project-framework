<?
namespace Model\User;

class Form extends \Library{

	private $roles = array();

	const FORM_ELEM = array(
		'role_id' => 'select',
		'first_name' => 'text',
		'last_name' => 'text',
		'email' => 'email',
		'password' => 'password',
		'password_again' => 'password',
		'is_enabled' => 'checkbox',
		'password_expires_at' => 'text',
		'account_expires_at' => 'text',
	);
	const FORM_ATTR = array(
		'role_id' => array(
			'label' => 'Role',
		),
		'is_enabled' => array(
			'label' => 'Enabled',
			'value' => '1',
		),
		'password_expires_at' => array(
			'class' => 'datepicker form-control'
		),
		'account_expires_at' => array(
			'class' => 'datepicker form-control'
		),
	);
	const SUBMIT = array(
		'add' => 'addUser',
		'update' => 'updateUser',
		'updateAccount' => 'updateAccount',
	);

	public function __construct(){

		$this->roles = \Register::get('roles');
	}

	public function newUserForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'class' => 'add-user', 
			'style' => ($_POST['addUser'] ? '' : 'display:none;')
		));

		$attr = self::FORM_ATTR;
		$attr['role_id']['option'] = $this->roles;
		$attr['is_enabled']['checked'] = 'checked';

		$form->addElem(self::FORM_ELEM, '', $attr);

		$form->selected( $_POST['role_id'], 'role_id' );

		$form->addElem('submit', Form::SUBMIT['add'], array(
			'class' => 'btn btn-primary',
			'value' => $this->Language('Add user')
		));

		unset($_POST['password']);
		unset($_POST['password_again']);

		//use next lines in view

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->validators(
			\Library\Validate::get(Form::SUBMIT['add'])
		);
		$form->setData( $_POST );
		$form->toString();
	}

	public function editUser($data){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array('action' => $this->url( '?id='.$_GET['id'] )));
		$form->addElem('data', 'name', array(
			'label' => $this->Language('Name')
		));
		
		$attr = self::FORM_ATTR;
		$attr['role_id']['option'] = $this->roles;

		$form->addElem(self::FORM_ELEM, '', $attr);

		$form->selected( $data['role_id'], 'role_id' );
		$form->checked( $data['is_enabled'], 'is_enabled' );

		$form->addElem('submit', Form::SUBMIT['update'], array(
			'class' => 'btn btn-primary',
			'value' => $this->Language('Update user')
		));

		$form->errorLabel( $this->getError() );
		$form->messageLabel( $this->getMessage() );

		$form->validators(
			\Library\Validate::get(Form::SUBMIT['update'])
		);
		$form->setData( $data );
		$form->toString();
	}

	public function UserForm(){

		$form = new \Library\Form( 'row' );
		//$form->addElem('data', array('first_name', 'last_name', 'user_name', 'email', 'type', 'delete' => ''));
		$form->addElem('data', 'first_name', array(
			'label' => $this->Language('First name'),
			'label-attr' => array('class' => 'first_name')
		));

		$form->addElem('data', 'last_name', array(
			'label' => $this->Language('Last name'),
			'label-attr' => array('class' => 'last_name')
		));

		$form->addElem('data', 'user_name', array(
			'label' => $this->Language('User name'),
			'label-attr' => array('class' => 'user_name')
		));

		$form->addElem('data', 'email', array(
			'label' => $this->Language('Email'),
			'label-attr' => array('class' => 'email')
		));

		$form->addElem('data', 'type', array(
			'label' => $this->Language('Type'),
			'label-attr' => array('class' => 'type')
		));

		$form->addElem('a', 'delete', array(
			'class' => 'delete',
			'value' => $this->Language( 'Delete' )
		));
		return $form;
	}

	public function AccountForm( $row ){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'action' => $this->url( array('model' => 'User', 'method' => 'Account', 'id' => $row['id']) )
		));
		$form->addElem('hidden', 'is_enabled', array(
			'value' => '1'
		))->append('form');
		
		$form->addElem( array('first_name', 'last_name', 'name' => 'data', 'password' => 'password', 'email') , '', array(
		));

		$form->addElem('submit', self::SUBMIT['updateAccount'], array(
			'class' => 'btn btn-primary',
			'value' => $this->Language('Update account')
		));

		$form->setData( $row );
		$form->toString();
	}

	public function userFilter(){

		$form = new \Library\Form( 'list' );
		$form->addElem('text', 'first_name', array('label' => $this->Language('First name')));
		$form->addElem('text', 'last_name', array('label' => $this->Language('Last name')));
		$form->addElem('text', 'user_name', array('label' => $this->Language('User name')));
		$form->addElem('text', 'email', array('label' => $this->Language('Email')));
		$form->addElem('select', 'type', array(
			'option' => $this->arrayValues($this->roles, 'name'),
			'label' => $this->Language('Type')
		));
		return $form;
	}
}

?>