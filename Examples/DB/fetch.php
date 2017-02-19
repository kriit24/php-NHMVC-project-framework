<?

//default fetch is ASSOC
$client->Select()
->fetch();
echo $client->numrows();

//set fetchmode
$client->Select()
->fetchMode(\PDO::FETCH_BOTH)
->fetch();


$client->Select();
$client->first_name('some');
$client->last_name('sur');
$client->fetch();


$client->fetch(array('id' => 1));//regular where method
$client->fetchObject(array('id' => 1));//fetch rows into object $client->row
$client->fetchAll(array('id' => 1));//regular where method
$client->fetchColumn('column', array('id' => 1));//regular where method
$client->fetchNumrows(array('id' => 1));//fetch only numrows

$client->Select()->column('company')->fetch( array('id' => 1) );

?>