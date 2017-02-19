<?
namespace Library\DB\PDO;

trait Statement{

	function StatementReConstruct(){

		unset($this->stmtArray);
		$this->stmt = null;
		$this->preDefinedColumns = array();
		$this->params = array();
		$this->row = array();
		$this->preQuery = '';
		$this->Query = '';
		$this->methods = array();
		$this->rebuildStatement = false;

		if( empty($this->_validFields) )
			new \Library\Component\Error( '<b style="color:red;">' . $this->_name .' Valid Fields is empty</b>', '', false, false );
	}

	function preDefineColumn($column, $value){

		$this->preDefinedColumns[$column] = $value;
	}

	function Select(){

		$this->StatementReConstruct();
		$this->stmtArray['SELECT'] = '';
		$this->Column( ($this->_aliasFields ? array_replace($this->_validFields, $this->_aliasFields) : $this->_validFields) );
		$this->From($this->_name);
		return $this;
	}

	function Insert($values = array()){

		$values = array_merge($this->preDefinedColumns, $values);
		if( $this->_trigger['INSERT'] && $triggerValues = call_user_func_array( array($this, $this->_trigger['INSERT']), array($values)) )
			$values = array_merge($values, $triggerValues);
		$values = $this->validFields($values);
		$values = $this->prepareParams($values);

		$functionValues = array_filter($values, function($v, $k){ return $this->prepareSqlFunction($v); }, ARRAY_FILTER_USE_BOTH);
		$functionValues2 = $functionValues;
		array_walk($functionValues2, function(&$value, $key){$value = ':' . $key;});
		$functionValues = array_combine(
			$functionValues2,
			$functionValues
		);

		$this->StatementReConstruct();
		$this->stmtArray['INSERT INTO'] = $this->_name;
		$this->stmtArray['COLUMN'] = '('.implode(', ', array_keys($values)).')';
		$this->stmtArray['VALUES'] = str_replace(array_keys($functionValues), $functionValues, '(:'.implode(', :', array_keys($values)).')' );
		$this->params = $this->prepareInsert($values, array_keys($functionValues));
		$this->Query();
		return $this->insertId();
	}

	function Update($values, $where){

		$values = array_merge($this->preDefinedColumns, $values);
		if( $this->_trigger['UPDATE'] && $triggerValues = call_user_func_array( array($this, $this->_trigger['UPDATE']), array($values, $where)) )
			$values = array_merge($values, $triggerValues);
		$values = $this->validFields($values);
		$values = $this->prepareParams($values);

		$this->StatementReConstruct();
		$this->stmtArray['UPDATE'] = $this->_name;
		$numerickeys = array_intersect_key( $values, array_flip(array_filter(array_keys($values), 'is_int')));
		$numerickeys2 = $numerickeys;
		array_walk($numerickeys2, function(&$value, $key){$value = $key . ' = :' . $key;});
		$numerickeys = array_combine(
			$numerickeys2,
			$numerickeys
		);

		list($set, $values) = $this->prepareSet(implode(' = ?, ', array_keys($values)).' = ?', $values);
		
		$this->stmtArray['SET'] = str_replace(array_flip($numerickeys), $numerickeys, $set );
		$this->params = array_filter($values, function($k){ return !is_numeric($k); }, ARRAY_FILTER_USE_KEY);
		$this->where($where);
		$this->Query();
		return $this;
	}

	function Delete($where = ''){

		$where = array_merge($this->preDefinedColumns, $where);

		$this->StatementReConstruct();
		$this->stmtArray['DELETE'] = '';
		$this->From($this->_name);
		$this->where($where);
		$this->Query();
		return $this;
	}

	function validFields($values){

		$ret = array();
		foreach($values as $k => $v){

			list($column, ) = explode(' ', (is_numeric($k) ? $v : $k));
			if( in_array($column, $this->_validFields) )
				$ret[$k] = $v;
		}
		return $ret;
	}

	function Column(){

		$args = func_get_args();
		$col = '';
		foreach($args as $arg){

			$columns = $arg;

			if( is_array($columns) ){

				foreach($columns as $k => $v){

					if( is_array($v) ){

						foreach($v as $k1 => $v1){

							if( !is_numeric($k1) )
								$col .= $col ? ','.$k.'.'.$k1.' AS '.$v1 : $k.'.'.$k1.' AS '.$v1;
							else
								$col .= $col ? ','.$k.'.'.$v1 : $k.'.'.$v1;
						}
					}
					else{

						if( !is_numeric($k) )
							$col .= $col ? ','.$k.' AS '.$v : $k.' AS '.$v;
						else
							$col .= $col ? ','.$v : $v;
					}
				}
			}
			else
				$col .= $col ? ','.$columns : $columns;
		}

		$this->stmtArray['COLUMN'] = $col;
		return $this;
	}

	function From($from, $as = ""){

		$this->stmtArray['FROM'] = ($from ? $from : $this->_name) . ($as ? ' AS '.$as : '');
		if( $as ){

			if( $this->stmtArray['COLUMN'] && $this->stmtArray['COLUMN'] == $this->getColumns() ){

				$columns = '';

				foreach(explode(',', $this->stmtArray['COLUMN']) as $col){

					$col = trim($col);
					if( !preg_match('/\./i', $col) )
						$col = $as.'.'.$col;
					$columns .= ($columns ? ',' : '').$col;
				}
				$this->stmtArray['COLUMN'] = $columns;
			}
		}
		return $this;
	}

	function Join($table, $as = '', $on = ''){

		$this->stmtArray['JOIN'][] = $table . ($as ? ' AS '.$as : '') . ($on ? ' ON '.$on : '');
		return $this;
	}

	function leftJoin($table, $as = '', $on = ''){

		$this->stmtArray['LEFT JOIN'][] = $table . ($as ? ' AS '.$as : '') . ($on ? ' ON '.$on : '');
		return $this;
	}

	function rightJoin($table, $as = '', $on = ''){

		$this->stmtArray['RIGHT JOIN'][] = $table . ($as ? ' AS '.$as : '') . ($on ? ' ON '.$on : '');
		return $this;
	}

	function innerJoin($table, $as = '', $on = ''){

		$this->stmtArray['INNER JOIN'][] = $table . ($as ? ' AS '.$as : '') . ($on ? ' ON '.$on : '');
		return $this;
	}

	function Where($where, $params = array()){
		
		if( is_Array($where) || !empty($params) ){

			if( is_array($where) && empty($params) && (!$this->stmtArray['JOIN'] && !$this->stmtArray['LEFT JOIN'] && !$this->stmtArray['RIGHT JOIN'] && !$this->stmtArray['INNER JOIN']) )
				$where = $this->validFields($where);

			list($where, $params) = $this->prepareWhere($where, (is_Array($params) ? $params : array($params)) );
		}
		if( !empty($where) ){

			$this->stmtArray['WHERE'][] = $where;
			if( is_array($params) )
				$this->params = array_merge($this->params, $params);
		}
		return $this;
	}

	function _getById($value){

		//$this->stmtArray['WHERE'][] = "id = :id";
		//$this->params = array_merge($this->params, array('id' => $value));
		die('INSTEAD GETBYID USE ->id('.$value.')');
	}

	function Order($order){

		$this->stmtArray['ORDER BY'] .= $this->stmtArray['ORDER BY'] ? ', '.$order : $order;
		return $this;
	}

	function Group($group){

		$this->stmtArray['GROUP BY'] .= $this->stmtArray['GROUP BY'] ? ', '.$group : $group;
		return $this;
	}

	function limit($start, $limit = 0){

		$this->stmtArray['LIMIT'] = $start . ($limit ? ','.$limit : '');
		return $this;
	}

	function having($having){

		$this->stmtArray['HAVING'] = $having;
	}
}

?>