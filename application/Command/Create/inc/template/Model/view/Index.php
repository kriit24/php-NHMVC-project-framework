<?
$formClass = new \{route}\{name}\Form\Index();

new \Helper\Label( '{uname}' );

$attr = array();
$form = $formClass->IndexForm();
//while($row = $this->{table}->fetch()){

	/*$attr['tbody']['tr'][] = array(
		'class' => 'link',
		'rel' => $this->url( $_GET, array('method' => 'View', 'id' => $row['id']) )
	);*/

	$form->setData($row);
//}
$form->toString( $attr );


?>