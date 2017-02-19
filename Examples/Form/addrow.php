<?

//ADD ROW

$form1 = \Library\Form;
$form2 = \Library\Form;

$form1->addElem();
$form2->addElem();

//if u want add html elems into subform - $form2 -> then u have to end elem tag 
/*
$form2->addElem('form');
$form2->addElem('text');
$form2->addElem('/form');
*/

while($data = $row){

	$form1->setData($row);

	if( $_GET['id'] == $row['id'] ){

		while($data2 = $row2){

			$form2->setData($row2);
		}
		$form1->setData($form2);
	}
}
$form1->toString();


//REPLACE ROW

$form1 = \Library\Form;
$form2 = \Library\Form;

$form1->addElem();
$form2->addElem();

//if u want add html elems into subform - $form2 -> then u have to end elem tag 
/*
$form2->addElem('form');
$form2->addElem('text');
$form2->addElem('/form');
*/
//ADD OBJECT ROW
//RESULT - <tr><td> form2 element </td></tr>
while($data = $row){

	if( $_GET['id'] == $row['id'] ){

		while($data2 = $row2){

			$form2->setData($row2);
		}
		//set data as form2 object
		$form1->setData($form2);
	}
	else{

		$form1->setData($row);
	}
}
$form1->toString();

//ADD STRING ROW

$form1 = \Library\Form;
$form2 = \Library\Form;

$form1->addElem();


//RESULT - <tr><td> form2 string </td></tr>
while($data = $row){

	if( $_GET['id'] == $row['id'] ){

		//generate on each while new form2 elements
		$form2->addElem();

		while($data2 = $row2){

			$form2->setData($row2);
		}
		//set data as form2 string
		$form1->setData($form2->getString());
	}
	else{

		$form1->setData($row);
	}
}
$form1->toString();

?>