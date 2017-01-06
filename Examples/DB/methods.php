<?

//DB library methods included
//SELECT
$db->method(array(\Library\Sql, 'addslashes'));
$db->method(array(\Library\Sql, 'stripslashes'));
$db->method(array(\Library\Sql, 'htmlspecialchars'));

//INSERT, UPDATE
$data = $db->addslashes($_POST);
$db->Update( $data, array('id' => 1) );

?>