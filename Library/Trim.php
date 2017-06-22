<?
namespace Library;

class Trim{

	public function trim( $textOrArray ){

		if( !is_Array($textOrArray) )
			$textOrArray = array($textOrArray);

		$ret = array();
		foreach($textOrArray as $k => $text){

			$ret[$k] = trim($text);
		}
		return $ret;
	}
}
?>