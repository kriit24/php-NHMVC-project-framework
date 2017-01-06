<?
namespace Helper\Autocomplete\Action;

abstract class Translate{

	public static function init(){

		if( !$_GET['term'] )
			return array();

		$language = new \Table\language;

		$column = array('name' => 'label');
		$where = array('name LIKE ?' => "%".$_GET['term']."%", 'language = ?' => _LANG);

		if( $_GET['getBy'] == 'value' ){

			$column = array('value' => 'label');
			$where = array('value LIKE ?' => "%".$_GET['term']."%", 'language = ?' => _LANG);
		}

		$rows = $language->Select()
			->column( $column )
			->where( $where )
			->fetchAll();
		return $rows;
	}
}

?>