<?
$formClass = new \{route}\{name}\Form\Index();

new \Helper\Label( '{uname}', array($formClass, 'AddForm') );

$attr = array();
$form = $formClass->IndexForm();
//while($row = $this->{table}->fetch()){

	/*$attr['tbody']['tr'][] = array(
		'class' => 'dialog',
		'rel' => $this->url( $_GET, array('method' => 'Edit', 'id' => $row['id']) )
	);*/

	$form->setData($row);
//}
$form->toString( $attr );


?>