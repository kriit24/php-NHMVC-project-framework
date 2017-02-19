<?

//PRE DEFINE ALIAS COLUMNS
class tableName extends \Library\Sql{

	protected $_aliasFields = array("DATE_FORMAT(created_at, '%d.%m.%Y')" => 'created_at_str');

	function SelectAll(){

		pre(
			$this->Select()->fetch()	
		);

		/*
		RETURN
		array(
			'id' => 1,
			'column' => 'value',
			'created_at' => '2017-01-01',
			'created_at_str' => '01.01.2017'
		);
		*/
	}
}

?>