<?
namespace Library\DB\Cache;

trait Query{

	private function getCache(){

		return \Library\Component\Cache::get( $this->statement['FROM'] );
	}

	private function setCache( $data ){

		return \Library\Component\Cache::set( $this->statement['FROM'], $data );
	}

	public function Query(){

		$method = $this->statement['STMT'] .'Query';
		return $this->$method();
	}

	private function ExistsQuery(){

		return \Library\Component\Cache::exists( $this->statement['FROM'] );
	}

	private function SelectQuery(){

		$data = $this->getCache();
		if( empty($data) )
			return array();

		return $this->orderBy( $this->getByWhere( $data ) );
	}

	private function InsertQuery(){

		$data = $this->getCache();
		if( empty($data) ){

			$insertId = 1;
			$set = array(array_merge( $this->statement['DATA'], array('id' => $insertId) ));
		}
		else{

			$lastId = $data[ count($data)-1 ]['id'];
			$insertId = $lastId + 1;

			array_push($data, array_merge( $this->statement['DATA'], array('id' => $insertId) ));
			$set = $data;
		}
		$this->setCache( $set );
		return $insertId;
	}

	private function UpdateQuery(){

		if( !$this->statement['WHERE'] )
			die('Update query must have WHERE');

		$data = $this->getCache();
		$rows = $this->getByWhere( $data, true );

		if( !empty($rows) ){

			$updated = array();
			foreach($rows as $key => $row){

				$data[$key] = array_merge($row, $this->statement['DATA']);
				$updated[] = $row['id'];
			}
			$this->setCache( $data );
			return $updated;
		}
		return;
	}

	private function ReplaceQuery(){

		if( !$this->statement['WHERE'] )
			die('Replace query must have WHERE');

		$data = $this->getCache();
		$rows = $this->getByWhere( $data, true );

		if( !empty($rows) ){

			$updated = array();
			foreach($rows as $key => $row){

				$data[$key] = $this->statement['DATA'];
				$updated[] = $row['id'];
			}
			$this->setCache( $data );
			return $updated;
		}
		return;
	}

	private function DeleteQuery(){

		if( !$this->statement['WHERE'] )
			die('Delete query must have WHERE');

		$data = $this->getCache();
		$rows = $this->getByWhere( $data, true );

		if( !empty($rows) ){

			foreach($rows as $key => $row){

				unset($data[$key]);
			}
			$data = array_merge($data, array());
			$this->setCache( $data );
		}
		return;
	}

	private function TruncateQuery(){

		\Library\Component\Cache::delete( $this->statement['FROM'] );
	}

	private function getByWhere( $data, $byKey = false ){

		if( empty($data) )
			return array();

		if( !$this->statement['WHERE'] )
			return $data;

		$tmp = array();
		foreach($data as $key => $row){

			if( $ret = array_intersect_assoc($row, $this->statement['WHERE'])){

				if( count($ret) == count($this->statement['WHERE']) ){

					if( $byKey )
						$tmp[$key] = $row;
					else
						$tmp[] = $row;
				}
			}
		}
		return $tmp;
	}

	private function orderBy( $data ){

		if( empty($this->statement['ORDER']) )
			return $data;

		$orderData = array();
		foreach($data as $key => $row){

			$rowData = array('array_key' => $key);
			foreach($this->statement['ORDER'] as $key) 
				$rowData[$key] = trim(mb_strtolower($row[$key]));

			$orderData[] = $rowData;
		}

		$orderBy = array($orderData);
		foreach($this->statement['ORDER'] as $k => $v){

			$column = is_numeric($k) ? $v : $k;
			$order = is_numeric($k) ? \SORT_ASC : $v;
			$orderBy[] = $column;
			$orderBy[] = $order;
			//$orderBy[] = SORT_STRING;
		}
		//pre($orderBy);

		$retData = call_user_func_array( array(new \Library\ArrayIterator, 'MultiSort'), $orderBy );
		if( !empty($retData) ){

			$tmpData = array();
			foreach($retData as $v)
				$tmpData[] = $data[ $v['array_key'] ];

			$data = $tmpData;
		}
		//pre($orderBy);
		//die(pre($retData));

		return $data;
	}
}

?>