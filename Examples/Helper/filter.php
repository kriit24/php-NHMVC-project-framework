<?PHP

//if($_SESSION['UserData']['type'] != 'SUPERADMIN')
//die('<center>Uuendamisel</center>');


set_include_path(__DIR__);
define('_APPLICATION_ENV', 'public');
define('_APPLICATION_PATH', 'application');

require 'application/Conf/Conf.php';
require 'Library/Loader/Abstract.php';

new class(false) extends Application{};

?>
<!--
THIS IS ONLY FOR EXAMPLE
-->
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css" type="text/css"/>
<link rel="stylesheet" href="/application/Template/project/public/css/form.css" type="text/css"/>
<link rel="stylesheet" href="/application/Template/project/public/css/fixes.css" type="text/css"/>
<link rel="stylesheet" href="/application/Helper/Filter/inc/style.css" type="text/css"/>


<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/application/Template/project/js/project.js" type="text/javascript"></script>
<script src="/application/Template/project/js/project.dialog.js" type="text/javascript"></script>
<script src="/application/Helper/Filter/inc/script.js" type="text/javascript"></script>
<!--
END FOR EXAMPLE
-->
<?

//$a = "id,user_id,first_name,a.last_name,changed_at";

//echo preg_replace('/([a-zA-Z\__]+)\,|\,([a-zA-Z\__]+)/s', 'c.\\1,', $a).'<br>';
//echo preg_replace('/^((?!.).)*$/s', 'c.\\1', $a).'<br>';
//exit;

$language = new \Table\language;
$user = new \Table\user;
$client = new \Table\client;

class Form{

	function filterForm(){

		$form = new Library\Form();
		$form->addElem('text', 'first_name');
		$form->addElem('text', 'last_name');
		$form->addElem('radio', 'is_temporary[]', 0);
		$form->addElem('data', 'temporary', ' No')->after('is_temporary[]');
		
		$form->addElem('radio', 'is_temporary[]', 1);
		$form->addElem('data', 'temporary', ' Yes')->after('is_temporary[]');

		//BETWEEN
		$form->addElem('text', 'changed_at[]', array(
			'class' => 'datepicker'
		));
		$form->addElem('data', 'changed', ' Start')->after('changed_at[]');

		$form->addElem('text', 'changed_at[]', array(
			'class' => 'datepicker'
		));
		$form->addElem('data', 'changed', ' End')->after('changed_at[]');

		$city = array(
			array('', 'vali'),
			array('tallinn', 'tallinn'),
			array('tartu', 'tartu'),
			array('pärnu', 'pärnu'),
			array('viljandi', 'viljandi'),
		);
		$form->addElem('select', 'city', array(
			'option' => $city
		));
		return $form;
	}

	function someFilter(){

		$form = new Library\Form();
		$form->addElem('text', 'first_name');
		$form->addElem('text', 'last_name');
		return $form;
	}

	function clientLists(){

		$form = new Library\Form();
		$form->addElem('data', 'first_name', array(
			'label' => 'First name',
			'label-attr' => array('class' => 'first_name')//important
		));
		$form->addElem('data', 'last_name', array(
			'label' => 'Last name',
			'label-attr' => array('class' => 'last_name')//important
		));
		return $form;
	}
}

$Filter = new \Helper\Filter;
$Form = new Form;

//SQL QUERY
$rows = $client->Select()
->filter(array(
	'first_name LIKE %?%',
	'last_name LIKE %?%',
), $_GET);
echo $client->getQuery();

//SHOW
echo '<p style="color:red;">SHOW</p>';
$Filter->show(
	$Form->filterForm()//form object
);

//GROUP
echo '<p style="color:red;">GROUP</p>';
$Filter->group(
	array(
		$Form->filterForm(),//form1 object
		$Form->someFilter()//form2 object
	)
);

//HEADER
echo '<p style="color:red;">HEADER</p>';
$formClient = $Form->clientLists();
while($row = $client->fetch()){

	$formClient->setData($row);
}
$formClient->toString();

$Filter->header(
	$Form->someFilter()//form object
);

?>