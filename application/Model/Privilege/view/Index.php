<?
Model\Dashboard\Index::singleton()->title(
	_tr('Privilege'), 
	array('<a href="" class="add-privilege btn btn-primary">'._tr('Add privilege').'</a>')
);

$this->Form->addPrivilegeForm();
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
	'$(".delete").confirm("'._tr('Confirm delete').'")',
));

?>