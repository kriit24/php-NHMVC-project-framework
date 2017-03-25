<?
$form = new \Library\Form();

$uniqid = uniqid('label_', true);

$form->addElem('span', 'header', array(
	'value' => '<label>' . $this->label . '<span style="float:right;">' . ($this->appendHtml ? $this->appendHtml : '') . '</span></label>',
	'class' => 'label',
	'style' => $this->style,
	'id' => $uniqid
));

$form->toString();
?>