<?
Model\Dashboard\Index::singleton()->title(
	$this->Language('Privilege'), 
	array('<a href="" class="add-privilege btn btn-primary">'.$this->Language('Add privilege').'</a>', '<a href="" class="clone-privilege btn btn-primary" style="margin-left:10px;">'.$this->Language('Clone privilege').'</a>')
);

$this->Form->addPrivilegeForm();
$this->Form->clonePrivilegeForm();
$form = $this->Form->PrivilegeForm();

while($row = $this->privilege->fetch()){

	$form->setData( $row );
	$form->selected($row['role_id'], 'role_id');

	$form->attr(array('href' => $this->url( array('action' => 'deletePrivilege', 'id' => $row['id']) )), 'delete');
}
$form->toString();

$this->script(array(
	'classUrl' => $this->url(array('model' => 'Privilege', 'method' => 'getClass')),
	'methodUrl' => $this->url(array('model' => 'Privilege', 'method' => 'getMethod')),
	'$(".delete").confirm("'.$this->Language('Confirm delete').'")',
));

?>