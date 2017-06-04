<?
namespace Library\DB;

class Filter{

	static function getWhere($where, $data){

		foreach($where as $k => $v){

			if( !is_numeric($k) ){

				if( !$data[ $k ] ){

					unset( $where[ $k ] );
				}
				else{

					unset( $where[ $k ] );
					$where[] = $v;
				}
			}
		}

		return $where;
	}
}

?>