<?

//PLAIN FETCH Query
$sql = new Library\Sql;
$sql->Query("SELECT * FROM client");
echo $sql->getQuery();
while($row = $sql->fetch()){
	
	pre($row);
}

?>