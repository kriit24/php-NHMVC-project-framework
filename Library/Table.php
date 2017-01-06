<?
namespace Library;

class Table{

	static function table($name){

		$className = '\\'. \Conf\Conf::_DB_ROOT_DIR .'\\'. $name;
		return new $className;
	}
}
?>