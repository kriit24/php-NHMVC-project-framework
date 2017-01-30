<?PHP
namespace Library\DB\REDIS;

/**
* REDIS class
*/
class Connection{

	public function connect( $_CONN ){

		if( !\Library\Component\Register::getRegister('DB_CONN') )
			\Library\Component\Register::register('DB_CONN', array(), \Library\Component\Register::IS_ARRAY);

		//if( !$connection_REDIS instanceof \Redis && class_exists( '\\Redis', false ) ){

			if( !empty($_CONN) && $_CONN['_host'] ){

				try {

					//list($_HOST, $_PORT, $_PASSWORD) = $CONNECTION_STR;

					$redis = new \Redis();
					$redis->connect($_CONN['_host'], ($_CONN['_port'] ? $_CONN['_port'] : 6379));
					$redis->auth($_CONN['_password']);
					$connection = $redis;
				}
				catch (\RedisException $e) {

					new \Library\Component\Error('Cant connect', 'Connection '.$e->getMessage());
				}
				if( isset($connection) && $connection instanceof \Redis ){

					\Library\Component\Register::setRegister('DB_CONN', array('REDIS' => $connection));
					$this->Redis = $this->getConnection();
				}
			}
		//}
	}

	/**
	* creat the sql connection
	*/
	protected function setConn(){

		if( !\Conf\Conf::_DB_REDIS )
			return;

		$this->connect( \Conf\Conf::_DB_REDIS );
	}

	protected function getConn(){

		if( !\Library\Component\Register::getRegister('DB_CONN') )
			return;

		if( !\Library\Component\Register::getRegister('DB_CONN')['REDIS'] )
			return;

		if( !\Library\Component\Register::getRegister('DB_CONN')['REDIS'] instanceof \Redis )
			return;

		return \Library\Component\Register::getRegister('DB_CONN')['REDIS'];
	}

	public function isConnected(){

		if( isset(\Library\Component\Register::getRegister('DB_CONN')['REDIS']) && \Library\Component\Register::getRegister('DB_CONN')['REDIS'] instanceof \Redis )
			return true;
		return false;
	}
}

?>