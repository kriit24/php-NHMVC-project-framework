<?
namespace Helper\Autocomplete\Action;

abstract class Translate{

	public static function init(){

		if( !$_GET['term'] )
			return array();

		$translate = new \Table\translate;

		$column = array('name' => 'label');
		$where = array('name LIKE ?' => "%".$_GET['term']."%", 'language = ?' => _LANG);

		if( $_GET['getBy'] == 'value' ){

			$column = array('value' => 'label');
			$where = array('value LIKE ?' => "%".$_GET['term']."%", 'language = ?' => _LANG);
		}

		$rows = $translate->Select()
			->column( $column )
			->where( $where )
			->fetchAll();
		return $rows;
	}
}

?>