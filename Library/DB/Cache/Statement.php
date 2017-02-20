<?
namespace Library\DB\Cache;

trait Statement{

	private $statement = array();

	public function Exists(){

		$this->statement['STMT'] = 'EXISTS';
		$this->statement['FROM'] = $this->_name;

		$rows = $this->Query();
		$this->clearStatement();
		return $rows;
	}

	public function Select( $where = array(), $order = array() ){

		$this->statement['STMT'] = 'SELECT';
		$this->statement['FROM'] = $this->_name;
		$this->statement['WHERE'] = $where;
		$this->statement['ORDER'] = $order;

		$rows = $this->Query();
		$this->clearStatement();
		return $rows;
	}

	public function Insert( $data ){

		$this->statement['STMT'] = 'INSERT';
		$this->statement['FROM'] = $this->_name;
		$this->statement['DATA'] = $data;

		$insertId = $this->Query();
		$this->clearStatement();
		return $insertId;
	}

	public function Update( $data, $where ){

		$this->statement['STMT'] = 'UPDATE';
		$this->statement['FROM'] = $this->_name;
		$this->statement['DATA'] = $data;
		$this->statement['WHERE'] = $where;

		$updated = $this->Query();
		$this->clearStatement();
		return $updated;
	}

	public function Replace( $data, $where ){

		$this->statement['STMT'] = 'REPLACE';
		$this->statement['FROM'] = $this->_name;
		$this->statement['DATA'] = $data;
		$this->statement['WHERE'] = $where;

		$updated = $this->Query();
		$this->clearStatement();
		return $updated;
	}

	public function Delete( $where ){

		$this->statement['STMT'] = 'DELETE';
		$this->statement['FROM'] = $this->_name;
		$this->statement['WHERE'] = $where;

		$this->Query();
		$this->clearStatement();
		return;
	}

	public function Truncate(){

		$this->statement['STMT'] = 'TRUNCATE';
		$this->statement['FROM'] = $this->_name;

		$this->Query();
		$this->clearStatement();
		return;
	}

	private function clearStatement(){

		$this->statement = array();
	}

}

?>