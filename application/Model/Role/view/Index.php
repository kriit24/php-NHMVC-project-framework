<?
\Model\Dashboard\Index::singleton()->title(
	$this->Language('Role'), 
	array('<a href="#" id="add-role-2" class="btn btn-primary add-role">'.$this->Language('Add role').'</a>')
);

$this->Form->AddRoleForm();
$form = $this->Form->RoleForm();
$roleEditForm = $this->Form->RoleEditForm();

while($row = $this->role->fetch()){

	if( $row['id'] == $_GET['id'] ){

		$form->setData( $roleEditForm->getRow($row) );
		$attr['tbody']['tr'][] = array();
	}
	else{

		$form->setData( $row );
		$attr['tbody']['tr'][] = array('class' => 'edit', 'rel' => $this->url('?action=edit&id='.$row['id']) );
	}
}
$form->toString( $attr );

?>