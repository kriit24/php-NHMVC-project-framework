<?

$select = '<div class="select-language-box">' . $this->Form->SelectLanguage() . '</div>';
//$select = $this->Form->SelectLanguage();

\Model\Dashboard\Index::singleton()->title(
	$this->Language('Translate'),
	array($select)
);

$form = $this->Form->TranslateForm();

$this->Paginator->paginate(
	$this->language
);

while($row = $this->language->fetch()){

	$form->setData( $row );
	$attr['tbody']['tr'][] = array('class' => 'dialog', 'rel' => $this->url(array('model' => 'Translate', 'method' => 'Edit', '?id='.$row['id'])), 'title' => 'Edit' );
}
$form->toString( $attr );

$this->Paginator->paginate(
	$this->language
);


$this->Filter->header(
	$this->Form->TranslateFilter()
);


$this->script('$(".truncate").confirm("'.$this->Language('Truncate language table ?').'");');

?>