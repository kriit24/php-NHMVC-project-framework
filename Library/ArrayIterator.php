<?
namespace Library;

class ArrayIterator extends Component\arrayIterator{

	use \Library\Component\Singleton;

	static function asArray($list){

		return parent::asArray($list);
	}

	static function isAssociative(array $array){

	    //return array_keys(array_merge($array)) !== range(0, count($array) - 1);
	    return array_keys($array) !== range(0, count($array) - 1);
	}

	function arrayValues($array, $key){

		$val = array();
		foreach($array as $k => $v){

			if( is_Array($key) ){

				$push = array();
				foreach($key as $keyV){

					if( $v[$keyV] )
						$push[] = $v[$keyV];
					else
						die('Array key does not exists: '.$keyV);
				}

				if( !empty($push) )
					array_push($val, $push);
			}
			else{

				if( isset($v[$key]) ) 
					array_push($val, $v[$key]);
				else
					die('Array key does not exists: '.$key);
			}
		}
		return $val;
	}

	public static function diff( $arr1, $arr2 ){

		return array_diff( $arr1, $arr2 );
	}

	function arrayValueToKey($array){

		return array_combine (array_values($array), $array);
	}

	function arrayKeyToValue($array){

		return array_combine (array_keys($array), $array);
	}

	public static function countDimension($Array, $count = 0){

	   if( is_array($Array) )
			return self::countDimension(current($Array), ++$count);
	   else
			return $count;
	}

	//$allRows = $this->MultiSort($allRows, 'column1', SORT_DESC, 'column2', SORT_ASC);
	function MultiSort(){

		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {

			if (is_string($field)) {

				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
			}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
}
?>