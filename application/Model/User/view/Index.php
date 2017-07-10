<?
\Model\Dashboard\Index::singleton()->title(
	_tr('User'), 
	array('<a href="" class="add-user btn btn-primary">'._tr('Add user').'</a>')
);
//new user
$this->Form->newUserForm();
$form = $this->Form->UserForm();


$this->Paginator->paginate($this->user);

while( $row = $this->user->fetch() ){

	$form->attr(array('href' => $this->url('?action=deleteUser&id='.$row['id'])), 'delete');
	$attr['tbody']['tr'][] = array('class' => 'dialog', 'data-href' => $this->url( array('model' => 'User', 'method' => 'Edit', '?id='.$row['id']) ), 'title' => $row['user_name']);

	$form->setData($row);
}
$form->toString( $attr );

$this->Filter->header(
	$this->Form->userFilter()
);
$this->Paginator->paginate($this->user);

$this->script('$(".delete").confirm("'._tr( 'Delete user ?' ).'");');

?>