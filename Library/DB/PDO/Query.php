<?
namespace Library\DB\PDO;

trait Query{

	function Query($Query = '', $params = array()){

		if( $Query && empty($this->stmtArray) )
			$this->StatementReConstruct();

		$this->PDO = $this->getConnection( $this->_connName );
		if( !$this->PDO )
			return $this;

		if( \Conf\Conf::_DEV_MODE ){

			$type = Log::getQueryType($this->stmtArray);
		}

		list($Query, $params) = $this->buildQuery($Query, $params);
		$this->Query = $Query;
		$this->params = $params;

		if( \Conf\Conf::_DEV_MODE && in_array($type, array('SELECT', 'UPDATE', 'DELETE')) ){

			Log::queryLog($this->_name, $type, $Query, $params);
		}

		//check query if UPDATE OR DELETE query dont have WHERE parameters
		if( $this->prepareCheckQuery($Query) )
			return $this;
		$this->stmt = $this->PDO->prepare($Query);

		@$this->stmt->execute($params);

		//ERROR HANDLING
		if( $this->stmt->errorInfo()[0] && PDO::ERROR_INFO[$this->stmt->errorInfo()[0]] )
			new \Library\Component\Error( PDO::ERROR_INFO[$this->stmt->errorInfo()[0]], $this->prepareGetQuery($Query, $params) );

		if( $this->stmt->errorInfo()[0] != 00000 && !PDO::ERROR_INFO[$this->stmt->errorInfo()[0]] )
			new \Library\Component\Error( implode(';', $this->stmt->errorInfo()), $this->prepareGetQuery($Query, $params) );

		if( $this->stmt->errorInfo()[2] )
			new \Library\Component\Error( $this->stmt->errorInfo()[2], $this->prepareGetQuery($Query, $params) );
		return $this;
	}

	function getQuery(){

		if( $this->Query ){

			$Query = $this->Query;
			$params = $this->params;
		}
		else{

			$Query = $this->build();
			$params = $this->params;
		}

		return $this->prepareGetQuery($Query, $params);
	}

	function numrows(){

		if( !$this->isConnected() )
			return;

		if( !$this->stmt && $this->stmtArray ){

			$this->buildQueryStatement( $where );
			$this->rebuildStatement = false;
			return $this->stmt->rowCount();
		}
		if( !$this->stmt && !$this->stmtArray ){

			return;
		}

		return $this->stmt->rowCount();
	}

	function insertId(){
		
		if( $this->PDO == NULL ){

			$row = $this->Query("SHOW TABLE STATUS FROM `".\Conf\Conf::_DB_CONN['_default']['_database']."` WHERE `name` LIKE '".$this->_name."'")->fetch();
			return $row['Auto_increment'];
		}

		return $this->PDO->lastInsertId();
	}
}

?>