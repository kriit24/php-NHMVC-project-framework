<?
namespace Library;

/**
* allows table rows queue change
* 
*/
class dragAndDrop{

	use Extension\dragAndDrop, \Library\Component\Singleton;

	function __construct(){

		$this->Sql = new Sql;
	}

	/**
	* on drop action
	* @param String $table - database table_name
	* @param String $parentColumnName = '' - table parent column_name
	*/
	function onDrop($table, $parentColumnName = ''){

		$this->_onDrop($table, $parentColumnName);
	}
}

?>