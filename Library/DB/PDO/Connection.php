<?PHP
namespace Library\DB\PDO;

/**
* PDO class
*/
class Connection{

	public function connect( $_CONN, $name = '' ){

		if( !\Library\Component\Register::getRegister('DB_CONN') )
			\Library\Component\Register::register('DB_CONN', array(), \Library\Component\Register::IS_ARRAY);

		if( !empty($_CONN) ){

			if( !$name )
				$name = $this->_connName;

			//list($_HOST, $_PORT, $_DATABASE, $_USER, $_PASSWORD, $_DRIVER) = $_CONN;

			//if( !isset($connection) || !$connection instanceof \PDO ){

				try {

					$_DSN = $_CONN['_driver'].':host='.$_CONN['_host'].($_CONN['_port'] && $_CONN['_port'] != 3306 ? ':'.$_CONN['_port'] : '').';dbname='.$_CONN['_database'];

					$connection = new \PDO($_DSN, $_CONN['_user'], $_CONN['_password'], array(
						\PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION group_concat_max_len = 1000000;",
						\PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode = '';",
						\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
					));
					$connection->exec("SET SESSION group_concat_max_len = 1000000");
					$connection->exec("set names utf8");
				}
				catch (\PDOException $e) {

					new \Library\Component\Error('Cant connect', 'Connection '.$e->getMessage());
				}
			//}
			if( isset($connection) && $connection instanceof \PDO ){

				\Library\Component\Register::setRegister('DB_CONN', array($name => $connection));
			}
		}
	}

	/**
	* creat the sql connection
	*/
	protected function setConn(){

		if( !\Conf\Conf::_DB_CONN )
			return;

		foreach(\Conf\Conf::_DB_CONN as $name => $connString)
			$this->connect( \Conf\Conf::_DB_CONN[$name], $name );
	}

	protected function getConn( $name = '' ){

		if( !$name )
			$name = $this->_connName;

		if( !\Library\Component\Register::getRegister('DB_CONN') )
			return;

		if( !\Library\Component\Register::getRegister('DB_CONN')[$name] )
			return;

		if( !\Library\Component\Register::getRegister('DB_CONN')[$name] instanceof \PDO )
			return;

		return \Library\Component\Register::getRegister('DB_CONN')[$name];
	}

	public function isConnected(){

		$name = $this->_connName;
		if( isset(\Library\Component\Register::getRegister('DB_CONN')[$name]) && \Library\Component\Register::getRegister('DB_CONN')[$name] instanceof \PDO )
			return true;
		return false;
	}

	/**
	* close sql connection
	*/
	static function close(){
	}
}

?>