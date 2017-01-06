<?PHP

$form = new \Library\Form();

$form->addElem('table');
$form->addElem('tr');

$form->addElem('th');
$form->addElem('data', '', 'some');
$form->addElem('/th');

$form->addElem('td');
$form->addElem('text', 'some', 'see');
$form->addElem('/td');

$form->addElem('/tr');
$form->addElem('/table');

$form->toString();

?>