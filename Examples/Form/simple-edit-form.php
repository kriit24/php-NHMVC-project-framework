FORM

<?
namespace Model\Privileges;

class Form extends \Library{

	const SUBMIT = array(
		'update' => 'update',
	);

	function dataForm(){

		$form = new \Library\Form( 'row' );
		$form->addElem('data', 'name', array(
			'label' => 'Name'
		));
		return $form;
	}

	function editForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form');
		$form->addElem('text', 'name');
		$form->addElem('submit', Form::SUBMIT['update'], 'Update');
		return $form;
	}
}

?>


VIEW

<?

$form = $this->Form->dataForm();
$formEdit = $this->Form->editForm();

while($row = $this->table->Assoc()){

	if( $_GET['id'] == $row['id'] )
		$form->setData( $formEdit->getRow($row) );
	else
		$form->setData( $row );
}

$form->toString();

?>