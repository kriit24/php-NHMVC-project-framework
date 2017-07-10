<?
\Model\Dashboard\Index::singleton()->title(
	_tr('Build')
);

$this->Form->buildForm();

if( $_POST['model_name'] )
	$this->script('getModelNames( $("select[name=\"create\"]"), "'.$_POST['model_name'].'" );');

?>