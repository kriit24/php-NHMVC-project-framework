<?
$formClass = new \{route}\{name}\Form\Index();
?>

<div class="ibox float-e-margins">	
	<div class="ibox-title dropdown border-dark" for="new-table-data">
		<h5><?=$this->Language( '{uname}' )?></h5>

		<div class="ibox-tools">
			<a class="collapse-link">
				<i class="fa fa-chevron-down"></i>
			</a>
		</div>
	</div>

	<div class="ibox-content border new-table-data" style="<?=( $this->getError() ? 'display:block;' : 'display:none;' );?>padding:0px;">
		<? $formClass->AddForm(); ?>
	</div>
</div>

<?
$form = $formClass->ListForm();
while($row = $this->{table}->fetch()){

	$form->setData($row);
}
$form->toString();


?>