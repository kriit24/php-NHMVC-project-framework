<?
$formClass = new \{route}\{name}\Form\Index();
?>

<div class="ibox float-e-margins">	
	<div class="ibox-title dropdown border-dark" for="new-{name}">
		<h5><?=$this->Language( '{uname}' )?></h5>

		<div class="ibox-tools">
			<a class="collapse-link">
				<i class="fa fa-chevron-down"></i>
			</a>
		</div>
	</div>

	<div class="ibox-content border new-{name}" style="<?=( $this->getError() ? 'display:block;' : 'display:none;' );?>padding:0px;">
		<? $formClass->AddForm(); ?>
	</div>
</div>

<?
$attr = array();
$form = $formClass->ListForm();
while($row = $this->{table}->fetch()){

	/*$attr['tbody']['tr'][] = array(
		'class' => 'dialog',
		'rel' => $this->url( $_GET, array('method' => 'Edit', 'id' => $row['id']) )
	);*/

	$form->setData($row);
}
$form->toString( $attr );


?>