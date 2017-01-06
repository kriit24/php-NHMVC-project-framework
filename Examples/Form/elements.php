<?
$form = new \Library\Form( 'list'/*row*/ );
//list - create list table
//row - create row table

//DESCRIPTION
$form->addElem('form');//first elem must be form if needed

$form->addElem('text'/*type*/, 'name'/*name*/, array()/*attributes*/);
$form->addElem('text'/*type*/, 'name'/*name*/, 'value'/*attribute value*/);
$form->addElem('text'/*type*/, 'name'/*name*/, array('value' => 'val')/*attribute value*/);

$form->setData( array(array('key' => 'value'))/*data*/ );
//ATTR methods
$form->addElem()->attr();
$form->attr(array('class' => 'some')/*attributes*/, 'name'/*element name*/);

/*TABLE*/
$attrList['table'] = array('class' => 'some');

/*THEAD*/
$attr = array();
$attr['tr'] = array('class' => 'tr-class');//all <tr> elements
$attr['tr'][0] = array('class' => 'tr-class');//first tr element
$attr['tr']['th'] = array('class' => 'th-class');//all tr element th elements
$attr['tr'][0]['th'] = array('class' => 'th-class');//first tr element th elements
$attr['tr'][0]['th'][0] = array('class' => 'th-class');//first tr element first th element
$attrList['thead'] = $attr;

/*TBODY*/
$attr = array();
$attr['tr'] = array('class' => 'tr-class');//all <tr> elements
$attr['tr'][0] = array('class' => 'tr-class');//first tr element
$attr['tr']['td'] = array('class' => 'td-class');//all tr element td elements
$attr['tr'][0]['td'] = array('class' => 'td-class');//first tr element td elements
$attr['tr'][0]['td'][0] = array('class' => 'td-class');//first tr element first td element
$attrList['tbody'] = $attr;

$form->toString( $attrList );//create form and table elements


//END DESCRIPTION

//EXAMPLES

$form->addElem('form');

$form->addElem('text', 'name', array(
	'label' => 'Name',
	'class' => 'some'
))->attr(array('style' => 'none'));

$form->addElem('select', 'role_id', array(
	'label' => 'Role',
	'label-attr' => array('class' => 'role'),
	'option' => array(array(1, 's'), array(2, 'v'), array(3, 'x')),
	'option-attr' => array('class' => 'option'),
	'option-attr(1)' => array('style' => 'option'),
	'option-attr(2)' => array('style' => 'option 2'),
))
->selected(2);

$form->addElem('select', 'role_id2', array(
	'label' => 'Role 2',
	'label-attr' => array('class' => 'role'),
	'optgroup' => array(
		'optgroup 1' => array(array(1, 's'), array(2, 'v'), array(3, 'x')),
		'optgroup 2' => array(array(10, 's'), array(20, 'v'), array(30, 'x'))
	),
	'optgroup-attr' => array('class' => 'optgroup'),
	'optgroup-attr(1)' => array('style' => 'optgroup'),
	'optgroup-attr(2)' => array('style' => 'optgroup 2'),
	'option-attr' => array('class' => 'option'),
	'option-attr(1)' => array('style' => 'option'),
	'option-attr(2)' => array('style' => 'option 2'),
))
->selected(20);

$form->addElem('radio', 'radio_elem', array(
	'label' => 'Radio',
	'value' => 1,
	'force-value' => 1//this value will force all other values int this element
))->checked($_POST['radio_elem']);

$form->addElem('radio', 'radio_elem', array(
	'value' => 2,
	'force-value' => 2//this value will force all other values int this element
))->checked($_POST['radio_elem']);

$form->addElem('checkbox', 'some[]', array(
	'label' => 'Some',
	'value' => 1
))->checked($_POST['some'][0])->after('Yes');

$form->addElem('checkbox', 'some[]', array(
	'label' => 'Some',
	'value' => 2
))->checked($_POST['some'][1])->after('No');

//OR

$form->checked($_POST['some'][$key], 'some[]');

$form->addElem('file', 'file')->after('some text');

$form->addElem('submit', 'add', 'Add privileges');

//a AND span elements is only HTML elements what dont need to be closed
$form->addElem('a', 'cancel', array(
	'href' => 'so',
	'value' => 'Cancel'
))->after('add');

$form->addElem('a', 'remove', array(
	'href' => 're',
	'value' => 'Remove'
))->after('add');

$form->addElem('a', 'some', array(
	'href' => '?a={some}'/*{tag_will_be_replaced}*/
));


$form->addElem('span', '', 'Remove');

//TWO elements dont need to be closed "a" AND "span"

//ADD AFTER </table> elements
$form->footer( array(
	$form->addElem('div', '', array('style' => 'text-align:right;margin-top:10px;padding-right:10px;'), true),//element must be returned
	$form->addElem('submit', Form::SUBMIT['confirm'], array('value' => $this->Language( 'Kinnita saatmiseks' )), true),//element must be returned
	$form->addElem('submit', Form::SUBMIT['delete'], array('value' => $this->Language( 'Kustuta arved' ), 'class' => 'confirm'), true),//element must be returned
	$form->addElem('/div', '', array(), true)//element must be closed and returned
) );

//for more elements see Library\Extension\Form\Form.php
//for more html elements see Library\Extension\Form\Html.php

?>