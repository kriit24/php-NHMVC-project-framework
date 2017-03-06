<?
namespace Library\DB\PDO;

trait Query{

	function Query($Query = '', $params = array()){

		$this->PDO = $this->getConnection( $this->_connName );

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

		if( !$this->stmt && $this->stmtArray ){

			$numrows = $this->fetchNumrows();
			$this->rebuildStatement = false;
			return $numrows;
		}
		if( !$this->stmt && !$this->stmtArray ){

			return;
		}

		return $this->stmt->rowCount();
	}

	function insertId(){

		return $this->PDO->lastInsertId();
	}
}

?>