<?
namespace Library\DB;

class Sort{

	static function getSort($data){

		if( !$data )
			return '';
		$ret = '';
		foreach($data as $k => $v){

			$ret .= $ret ? ', '.$k.' '.$v : $k.' '.$v;
		}
		return $ret;
	}
}

?>