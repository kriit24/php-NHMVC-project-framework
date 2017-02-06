<?
namespace Library\DB\PDO;

trait Fetch{

	private $rebuildStatement = false;

	private function extension($row, $multiArray = false){

		if( $this->methods && $row ){

			if( $multiArray ){

				foreach($row as $k => $v){

					if( $returnRow = $this->executeMethod($v, $this->methods) );
						$row[$k] = $returnRow;
				}
			}
			else{

				if( $returnRow = $this->executeMethod($row, $this->methods) );
					$row = $returnRow;
			}
		}
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
			$this->rebuildStatement = true;
		}
		else{

			if( !$this->Query )
				$this->Query();
			$this->rebuildStatement = false;
		}
	}

	function fetchMode($fetchMode){

		$this->fetchMode = $fetchMode;
		return $this;
	}

	function fetchAll($where = ''){

		$this->buildQueryStatement( $where );
		$rows = $this->stmt->fetchAll( $this->fetchMode );
		return $this->extension( $rows, true );
	}

	function fetchColumn($column, $where = ''){

		$this->buildQueryStatement( $where );
		$ret;
		$this->stmt->bindColumn( $column, $ret );
		$this->stmt->fetch( $this->fetchMode );
		$ret = $this->extension( $ret );
		return $ret;
	}

	function fetch($where = ''){

		$this->buildQueryStatement( $where );
		$row = $this->stmt->fetch( $this->fetchMode );
		return $this->extension( $row );
	}

	
	public function fetchObject($where = ''){

		$this->buildQueryStatement( $where );
		$row = $this->stmt->fetch( $this->fetchMode );
		$this->row = $this->extension( $row );
	}

	function fetchNumrows($where = ''){

		$this->buildQueryStatement( $where );
		return $this->numrows();
	}

	private function executeMethod($row, $methods){

		$ret = $row;
		foreach( $methods as $method ){

			$reflectionMethod = new \ReflectionMethod($method[0], $method[1]);
			if( !$reflectionMethod->isPublic() )
				die($method[1] . ' is not public');

			$ret = call_user_func_array(array($method[0], $method[1]), array($ret, $method[2]));
		}
		return $ret;
	}
}

?>