<?
namespace Library\DB;

class Filter{

	static function getWhere($where){

		return $where;
		/*if( !is_array($where) )
			return $where;

		$retWhere = array();
		foreach($where as $k => $v){

			$column = !is_numeric($k) ? $k : $v;
			$value = !is_numeric($k) ? $v : ':'.$v;
			if( !preg_match('/(=|<|>|BETWEEN|IN\()/', $column.$value) )
				$column .= ' =';
			$retWhere[$column] = $value;
		}
		return $retWhere;*/
	}
}

?>