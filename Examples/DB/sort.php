<?


$client->Select()
->sort(array('first_name', 'is_temporary'), $_GET['sort'])//this is order by array, follow query build order
->fetch();


?>