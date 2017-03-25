<?
namespace Library\DB\PDO;

trait Fetch{

	private $rebuildStatement = false;
	private $isInWhile = false;

	private function extension($row, $multiArray = false){

		if( $row ){

			if( $this->onComplete ){

				if( $multiArray ){

					foreach($row as $k => $v){

						if( $returnRow = $this->executeMethod($v, $this->onComplete) );
							$row[$k] = $returnRow;
					}
				}
				else{

					if( $returnRow = $this->executeMethod($row, $this->onComplete) );
						$row = $returnRow;
				}
			}
		}
		else
			$row = false;
		return $row;
	}

	private function buildQueryStatement($where = ''){

		if( $this->rebuildStatement )
			$this->StatementReConstruct();

		if( empty($this->stmtArray) && !$this->Query ){
			
			if( $where )
				$this->Select()->where($where);
			else
				$this->Select();

			$this->Query();
			$this->rebuildStatement = ($this->isInWhile ? false : true);
			$this->isInWhile = true;
		}
		else{

			if( !$this->Query ){

				if( $where )
					$this->where( $where );
				$this->Query();
			}
			else{

				if( $where ){

					$this->where( $where );
					$this->Query();
				}
			}
			$this->rebuildStatement = false;
		}
	}

	function fetchMode($fetchMode){

		$this->fetchMode = $fetchMode;
		return $this;
	}

	function fetchAll($where = ''){

		if( !$this->isConnected() )
			return false;

		$this->buildQueryStatement( $where );
		$rows = $this->stmt->fetchAll( $this->fetchMode );
		$this->rebuildStatement = true;
		return $this->extension( $rows, true );
	}

	function fetchColumn($column, $where = ''){

		if( !$this->isConnected() )
			return false;

		$this->buildQueryStatement( $where );
		$ret;
		$this->stmt->bindColumn( $column, $ret );
		$this->stmt->fetch( $this->fetchMode );
		$ret = $this->extension( $ret );
		$this->rebuildStatement = true;
		return $ret;
	}

	function fetch($where = ''){

		if( !$this->isConnected() )
			return false;

		$this->buildQueryStatement( $where );
		$row = $this->stmt->fetch( $this->fetchMode );
		return $this->extension( $row );
	}

	
	public function fetchObject($where = ''){

		if( !$this->isConnected() )
			return false;

		$this->buildQueryStatement( $where );
		$row = $this->stmt->fetch( $this->fetchMode );
		$this->row = $this->extension( $row );
	}

	function fetchNumrows($where = ''){

		if( !$this->isConnected() )
			return false;

		$this->buildQueryStatement( $where );
		return $this->numrows();
	}

	private function executeMethod($row, $onComplete){

		$ret = $row;
		foreach( $onComplete as $method ){

			$reflectionMethod = new \ReflectionMethod($method[0], $method[1]);
			if( !$reflectionMethod->isPublic() )
				die($method[1] . ' is not public');

			$ret2 = call_user_func_array(array($method[0], $method[1]), array($ret, $method[2]));
			if( !empty($ret2) )
				$ret = $ret2;
		}
		return $ret;
	}
}

?>