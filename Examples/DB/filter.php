<?


$client->Select()
->filter(array(
	'name LIKE %:name%',
	'value LIKE %:value2%',
	'model LIKE %:model2%',
), $_GET)//this is where array, follow query build order
->where(array('is_deleted' => 0))//add where condition to sql query
->fetch();


?>