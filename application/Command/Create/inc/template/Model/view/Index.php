<?
$formClass = new \{route}\{name}\Form\Index();
?>

<div class="ibox float-e-margins">	
	<div class="ibox-title border-dark">
		<h5><?=$this->Language( '{uname}' )?></h5>
	</div>
</div>

<?
$attr = array();
$form = $formClass->ListForm();
while($row = $this->{table}->fetch()){

	/*$attr['tbody']['tr'][] = array(
		'class' => 'link',
		'rel' => $this->url( $_GET, array('method' => 'View', 'id' => $row['id']) )
	);*/

	$form->setData($row);
}
$form->toString( $attr );


?>