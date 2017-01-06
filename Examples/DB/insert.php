<?

//INSERT array
$client->Insert(array('first_name' => 'name'));
$insertId = $client->insertId();
echo $client->getQuery();


//INSERT predefined column
$client->first_name('some');
$client->last_name('sur');
$client->country('estonia');
$client->county('harjumaa');
$client->city('tallinn');
$client->street('Lounatuule tee');
$client->house('68');
$client->is_deleted( $_POST['is_deleted'] ?: \NULL );//it will leave data as is
$client->account_expires_at( $_POST['account_expires_at'] ?: 'NULL' );//will set data to NULL

$client->Insert();
$insertId = $client->insertId();
echo $client->getQuery();


//INSERT array
$client->Insert(array('first_name' => 'name', 'phone' => 'RAND(5)'));
$insertId = $client->insertId();
echo $client->getQuery();

?>