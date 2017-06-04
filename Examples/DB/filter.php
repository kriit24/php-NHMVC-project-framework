<?


$q = $client->Select();

if( $_GET['filter'] ){

	$q->filter(array(
		'name LIKE %:name%',
		'value LIKE %:value2%',
		'model LIKE %:model2%',
		//set key name if complex condition
		'board_member' => " CASE WHEN lr.is_board_member = 1 THEN CONCAT( c.first_name, ' ', c.last_name) ELSE '' END LIKE %:board_member% ",
		'surety' => " CASE WHEN lr.is_surety = 1 THEN CONCAT(c.first_name, ' ', c.last_name) ELSE '' END LIKE %:surety% ",
	), $_GET);
}

$q->where(array('is_deleted' => 0))//add where condition to sql query
->fetch();


?>