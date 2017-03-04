<?PHP
namespace Library\DB\PDO;

/**
* PDO class
*/
class PDO extends Connection{

	const ERROR_INFO = array(
		'HY093' => 'Execute parameter missing'
	);

	public $_connName = '_default';
	private $PDO;
	private $stmt = null;
	private $preDefinedColumns = array();
	private $stmtArray = array();
	private $stmtQueye = array(
		'SELECT', 'INSERT INTO', 'UPDATE', 'DELETE', 'COLUMN', 'FROM', 'JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'SET', 'VALUES', 'WHERE', 'ORDER BY', 'GROUP BY', 'HAVING', 'LIMIT'
	);
	private $params = array();
	private $columnParams = array();
	private $prepareParams = array();
	private $Query = '';
	private $methods = array();
	private $fetchMode = false;
	public $row = array();

	use Query, Prepare, Statement, Build, Fetch, Cache, Log;

	public function setConnection(){

		return $this->setConn();
	}

	public function getConnection( $connName = '' ){

		return $this->getConn( $connName );
	}

	function getStmtArray(){

		return $this->stmtArray;
	}

	public function getColumns( $exclude = array() ){

		return array_diff($this->_validFields, $exclude);
	}

	public function getAlias( $exclude = array() ){

		return array_diff($this->_aliasFields, $exclude);
	}

	function getParams(){

		return $this->params;
	}

	function setStmtArray($stmtArray){

		$this->stmtArray = $stmtArray;
	}

	function setParams($params){

		$this->params = $params;
	}

	function complete($complete){

		$this->onComplete[] = $complete;
		return $this;
	}

	//simple statement parameters like HAVING (count() > 1)
	//$this->having("count(1) > 1");
	//OR
	//$this->having("COUNT(1) > :param", array('param' => 1));
	
	//ALSO PREPARE COLUMN
	//$this->column_name('value');
	function __call($method, $args){

		//IF METHOD IS COLUMN
		if( in_array($method, $this->_validFields) ){

			$column = $method;
			$value = $args[0];

			if( isset($this->stmtArray['SELECT']) )
				$this->where(array($column => $value));
			else
				$this->preDefineColumn($column, $value);
		}
		else if( method_exists($this, '_'.$method) ){

			$this->{'_'.$method}($args[0]);
		}
		else{

			//IF METHOD IS QUERY EXTENSION
			$this->stmtArray[strtoupper($method)] = $args[0];
			if( !in_array(strtoupper($method), $this->stmtQueye) )
				array_push($this->stmtQueye, strtoupper($method));
			if( !empty($args[1]) )
				$this->params = array_merge($this->params, $args[1]);
		}
		return $this;
	}
}

?>