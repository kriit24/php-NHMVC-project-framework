<?
\Model\Dashboard\Index::singleton()->title(
	$this->Language('{name}'),
	array('links')
);

$formClass = $this->getClassFrom( $this, 'Form\Index' );
$formClass->addForm();
?>