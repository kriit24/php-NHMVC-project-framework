<?
namespace Library\Extension;

class Sql extends \Library\DB\PDO\PDO{

	public function phpFunction( $function, $data ){

		if( !is_array($data) )
			return call_user_func($function, $data);

		foreach($data as $k => $v){

			if( is_array($v) )
				$data[$k] = $this->phpFunction( $function, $v );
			else
				$data[$k] = call_user_func($function, $v);
		}
		return $data;
	}
}

?>