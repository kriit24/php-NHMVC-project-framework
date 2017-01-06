<?
namespace Library\Component;

class arrayIterator{

	static function asArray($list){

		if(!is_Array($list) && strlen($list) > 0){

			$tmp[] = $list;
			$list = $tmp;
		}
		return $list;
	}
}

?>