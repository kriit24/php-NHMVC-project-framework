<?
namespace Library\DB\REDIS;

trait Query{

	public $RedisName = '_REDIS';

	public function construct( $database = null ){

		if( \Conf\Conf::_DB_CONN ){

			$this->RedisName .= '_' . \Conf\Conf::_DB_CONN['_default']['_database'];
		}
		if( $database ){

			$this->RedisName .= '_' . $database;
		}
	}

	function set($name, $value){

		if( in_array(gettype($value), array('array', 'object')) )
			$value = json_encode($value);
		$this->Redis->set($name . $this->RedisName, $value);
	}

	function append($name, $value){

		$ret = $this->get($name);
		if( !empty($ret) )
			$ret = array_merge($ret, $value);
		else
			$ret = $value;
		$this->set($name, $ret);
	}

	function get($name){

		$value = $this->Redis->get($name . $this->RedisName);
		if( in_array(gettype($val = json_decode($value, true)), array('array', 'object')) )
			$value = $val;
		return $value;
	}

	function delete($name){
		
		$this->Redis->delete($name . $this->RedisName);
	}
}
?>