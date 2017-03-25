<?
\Model\Dashboard\Index::singleton()->title(
	$this->Language('Build')
);

$this->Form->buildForm();

if( $_POST['model_name'] )
	$this->script('getModelNames( $("select[name=\"create\"]"), "'.$_POST['model_name'].'" );');

?>