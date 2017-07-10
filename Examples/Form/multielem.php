<?
$row = array();

$form = new \Library\Form( 'list' );
$form->addElem('form', '', array(
	'action' => $''
));

$elems = array('first_name', 'last_name', 'password' => 'password', 'county' => 'select', 'city', 'street', 'house', 'apartment', 'zip', 'email', 'phone', 'company', 'company_register');
$attr = array(
	'first_name' => array(
		'autocomplete' => 'off'
	)
);

$form->addElem($elems, '', $attr);

$form->addElem('submit', Form::SUBMIT['update'], array(
	'value' => _tr(Form::SUBMIT['update'])
));

//use next lines in view
$form->errorLabel( $this->getError() );
$form->messageLabel( $this->getMessage() );

$form->setData( $row );
$form->toString();
?>