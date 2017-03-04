<?

//DB library methods included
//SELECT
$db->complete(array(\Library\Sql, 'addslashes'));
$db->complete(array(\Library\Sql, 'stripslashes'));
$db->complete(array(\Library\Sql, 'htmlspecialchars'));

//INSERT, UPDATE
$data = $db->addslashes($_POST);
$db->Update( $data, array('id' => 1) );

?>