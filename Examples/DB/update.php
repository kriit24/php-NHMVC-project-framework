<?

//UPDATE
$client->Update(
	array('zip' => '76706', 'company' => 'my company', 'company_register' => $_POST['client_language'] ?? null),
	array('id' => 1)
);
echo $client->getQuery();

//PER DEFINED COLUMN
$client->first_name('some');
$client->last_name('sur');
$client->country('estonia');
$client->county('harjumaa');
$client->city('tallinn');
$client->street('Lounatuule tee');
$client->house('68');
$client->is_deleted( $_POST['is_deleted'] ?: \NULL );//it will leave data as is
$client->account_expires_at( $_POST['account_expires_at'] ?: 'NULL' );//will set data to NULL

$client->Update($_POST, array('id' => 1));

//UPDATE
$client->Update(
	array('zip' => '76706', 'company' => 'my company', 'company_register' => $_POST['client_language'] ?? null, 'phone = RAND(5)'),
	array('id' => 1)
);
echo $client->getQuery();

?>