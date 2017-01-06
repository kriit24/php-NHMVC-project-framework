<?
//create validators - view example: validate
//link validators into form

$validators = \Library\Validate::get('form submit element name');
//$validate->isValid('THIS NAME', $_POST);//array with data

//to set global validators, 
$form = new \Library\Form();
$form->validators( $validators );

//to set personal validators
$form = new \Library\Form();
$form->addElem( 'text', 'name', array(
	'validators' => $validators
));

?>