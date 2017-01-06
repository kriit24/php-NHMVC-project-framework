<?

//this is only example for possible solutions
$rows = $client->Select()
->where('id = 1')
->where('id = ?', array(5))
->where(array('id = ?', 'first_name = ?'), array(6, 'first'))//value parameter 6 will be overwrite value 5 - regular array
->where(array('user_id IN( SELECT id FROM user WHERE id = 1)', 'first_name' => 'John'))//first_name
->where(array("user_id IN(?)"), array("SELECT id FROM user"))
->where(array('last_name = ' => "'second'"))
->where(array('first_name = :name'), array('name' => 'third'))
->where(array('last_name' => ':name2'), array('name2' => 'fourth'))
->where(array('user_id IN(:user_id)'), array('user_id' => '0,1,2'))
->where(array('id IN(?)' => '4'))
->where("changed_at <= DATE_FORMAT(?, '%d.%m.%Y')", '30.07.2016')
->where("changed_at <= DATE_FORMAT(?, '%d.%m.%Y') OR (first_name = 'f' OR (first_name = 's' AND last_name = 'b')) AND kuku = :jaja AND id IN(?)", array('30.07.2017', '99', 'jaja' => 'vastus'))
->where("changed_at BETWEEN ? AND ? AND juku = ?", array('30.08.2016', '30.08.2017', 'suka'))
->where(array('first_name LIKE %?', 'last_name LIKE %?%', 'middle_name LIKE ?%'), array('John', 'John some', 'Middle John'))
->where("changed_at BETWEEN :b1 AND :b2", array('b1' => '30.09.2016', 'b2' => '30.09.2017'))
->where("changed_at <= DATE_FORMAT(?, '%d.%m.%Y') OR (first_name = ? OR (first_name = ? AND last_name = ?) AND personal_code = :jaja AND id IN(?))", array('30.07.2017', '99', 'jaja' => 'vastus', 'some', 'mixit' => 'eee', 'id' => 1))
->where("changed_at BETWEEN DATE_FORMAT(?, '%d.%m.%Y') AND DATE_FORMAT(?, '%d.%m.%Y') AND changed_at BETWEEN DATE_FORMAT(?, '%d.%m.%Y') AND DATE_FORMAT(?, '%d.%m.%Y')", array('00.00.0000', '31.12.2019', '00.00.0000', '20.12.2018'))
->where("user_id = 1")
->where("(first_name LIKE ?% OR last_name LIKE %?)", array('criti', 'va'))
->where("(first_name LIKE ? OR last_name LIKE ?)", array('%criti', 'va%'))
->where(array('user_id IN(?)'), array('(SELECT id FROM user WHERE id = 1)'))
->where(array('user_id IN(?)' => '(SELECT id FROM user WHERE id = 1)'))
->first_name('last')
->id(2)
->fetchAll();

echo $client->getQuery();
pre($rows);

//FETCH Query - JOIN
$row = $client->Select()
	->column("c.*")
	->from('client AS c, user AS u')
	->where('u.id = c.user_id')
	->fetch();
echo $client->getQuery();
pre($row);


//FETCH Query - JOIN 2
$row = $client->Select()//FROM clien AS c
	->from('client', 'c')
	->join('user', 'u', 'u.id = c.user_id')//JOIN user AS u ON u.client_id = c.id
	->where('c.id = 1')//WHERE c.id = 1
	->fetch();
echo $client->getQuery();
pre($row);

//FETCH and use order
$row = $client->Select()
	->where("email = 'some' AND id != 2")
	->order("id DESC")
	->fetch();
echo $client->getQuery();
pre($row);

//FETCH with subquery
$rows = $client->Select()
->where('user_id IN( SELECT id FROM user )')
->where(array('first_name' => 'John'))
->fetchAll();
echo $client->getQuery();
pre($rows);

//FETCH by prepared Query
$rows = $client->Select()
	->where("(first_name = :name OR email = :email) AND id = :id", array('name' => 'John', 'id' => 2))//if email not presented then it will not use it on sql Query
	->fetchAll();
echo $client->getQuery();
pre($rows);

//USE METHOD
class s {

	function functionName($row){//this method will be called before fetch returns $row data

		$row['name'] = 'some';
		return $row;
	}
}

$row = $client->Select()
->method(array(new s, 'functionName'))//SEE "methods.php" for Sql methods included
->fetch();
echo $client->getQuery();
pre($row);




$_GET['user_id_min'] = 1;
$_GET['user_id_max'] = 2;

$row = $client->Select()
->where(
	"first_name = ? 
	AND last_name = ? 
	AND (
		user_id BETWEEN :user_id_min AND :user_id_max
		OR is_temporary BETWEEN :is_temporary_1 AND :is_temporary_2
	)
	AND changed_at BETWEEN :ch_at_1 AND :ch_at_2", 
	array('first_name' => 'John', 'ch_at_1' => '20.06.1978', 'ch_at_2' => '00.22.2546')
)
->filter(array('first_name', 'last_name', 'is_deleted IN(?)', 'user_id BETWEEN :user_id_min AND :user_id_max', 'company'), $_GET)//WHERE - data from helper
->sort(array('first_name', 'is_temporary'), $_GET['sort'])//ORDER BY - data from helper
->paginator(5)//LIMIT - it will set limit for paginator (helper)
->fetch();
echo $client->getQuery();
pre($row);

?>