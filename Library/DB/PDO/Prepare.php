<?
namespace Library\DB\PDO;

trait Prepare{

	private $i = 0;

	function prepareWhere($where, $params){
		
		if( is_array($where) ){

			$whereString = '';
			foreach($where as $k => $v){

				$column = !is_numeric($k) ? $k : $v;
				$value = !is_numeric($k) ? $v : ($params[$v] ? $params[$v] : $params[$k]);

				list($retColumn, $retValue) = $this->prepareCVExpression($column, $value);

				$whereString .= $whereString ? " AND " : '';
				$whereString .= $retColumn;
				if( isset($retValue) ){

					$retColumn = $this->prepareWhereString($retColumn);
					$paramName = $this->prepareColumnName($retColumn) ?? $column;
					$params[$paramName] = $retValue;
				}
			}
			return $this->prepareWhere($whereString, $params);
		}
		$where = $this->prepareWhereString($where);

		list($where, $params) = $this->prepareWhereParams($where, $params);
		list($where, $params) = $this->prepareLikeParams($where, $params);
		list($where, $params) = $this->prepareWhereExpression($where, $params);

		return array($where, $params);
	}

	private function prepareColumnName($string){

		preg_match('/\:([a-zA-Z0-9\.\__]+)/s', $string, $match);
		return $match[1];
	}

	private function prepareCVExpression($column, $value){

		if( preg_match('/\:([a-zA-Z0-9\.\__]+)/i', $column) || preg_match('/\?/i', $column) )
			return array($column, $value);
		
		if( preg_match('/[[:space:]]/i', $column) && $column && !isset($value) )
			return array($column, $value);

		if( preg_match('/[[:space:]]/i', $column) && $column && isset($value) )
			return array($column . ' ' . $value, '');
		
		if( !preg_match('/[[:space:]]/i', $column) && $column && isset($value) && preg_match('/\:/i', $value) )
			return array($column . ' = '.$value, '');

		if( !preg_match('/[[:space:]]/i', $column) && $column && isset($value) )
			return array($column . ' = :'.$column, $value);

		if( $column && !isset($value) )
			return array($column, $value);
	}

	private function prepareWhereString($where){

		//LIKE
		foreach(array('\%\?\%', '\%\?', '\?\%', '\?') as $v){

			$where = preg_replace('/([a-zA-Z0-9\_\._]+)([LIKE[:space:]_]+)'.$v.'/s', '\\1\\2'.str_replace(array('\%', '\?'), array('%', ':\\1'), $v), $where);
		}
		//BETWEEN
		$where = preg_replace('/^([a-zA-Z0-9\_\.\:\s_]+)BETWEEN(.*?)\?(.*?)AND(.*?)\?/s', '\\1BETWEEN \\2:\\1\\3AND\\4:\\1', $where);
		$where = preg_replace('/([AND|OR]+)\s+([a-zA-Z0-9\_\.\:_]+)\s+BETWEEN(.*?)\?(.*?)AND(.*?)\?/s', ' \\1 \\2 BETWEEN \\3:\\2\\4AND\\5:\\2', $where);
		//SQL FUNCTION
		$where = preg_replace('/([a-zA-Z0-9\_\._]+)([[:space:]\<\=\>_]+)([A-Z0-9\__]+)\(\?\)/s', '\\1\\2\\3(:\\1)', $where);
		$where = preg_replace('/([a-zA-Z0-9\_\._]+)([[:space:]\<\=\>_]+)([A-Z0-9\__]+)\(\?([a-zA-Z0-9[:space:],.%\'_]+)\)/s', '\\1\\2\\3(:\\1\\4)', $where);
		//ALL OTHERS
		$where = preg_replace('/([a-zA-Z0-9\_\._]+)\s+([<>=!_]+)\s+\?/s', '\\1 \\2 :\\1', $where);
		return $where;
	}

	private function prepareWhereParams($where, $params){

		if( !preg_match('/\:([a-zA-Z0-9\_\._]+)/i', $where) )
			return array($where, $params);

		$columnKey = 0;
		preg_match_all('/\:([a-zA-Z0-9\_\._]+)/s', $where, $matches);
		if( is_array($matches[1]) ){

			foreach($matches[1] as $k => $v){

				$columnName = trim($v);
				$column = trim($v);
				$newColumnName = str_replace('.', '_', $columnName.'_'.$this->i);

				if( !isset($params[$column]) && isset($params[$columnKey]) ){

					$column = $columnKey;
					$columnKey ++;
				}
				if( isset($params[$column]) ){

					$params[$newColumnName] = $params[$column];
					unset($params[$column]);
				}

				if( $this->prepareSqlFunction($params[$newColumnName]) ){

					$where = preg_replace('/\:\b'.$columnName.'\b/', $params[$newColumnName], $where, 1);
				}
				else{

					$where = preg_replace('/\:\b'.$columnName.'\b/', ':'.$newColumnName, $where, 1);
				}
				$this->i++;
			}
		}
		return array($where, $params);
	}

	private function prepareLikeParams($where, $params){

		if( !preg_match('/\:([a-zA-Z0-9\_\._]+)/i', $where) )
			return array($where, $params);

		$pos = 0;
		$columnKey = 0;
		foreach(array('\%\:([a-zA-Z0-9\__]+)\%', '\%\:([a-zA-Z0-9\__]+)', '\:([a-zA-Z0-9\__]+)\%') as $v){

			$pos++;
			if( preg_match_all('/([a-zA-Z0-9\__]+)([LIKE[:space:]_]+)'.$v.'/s', $where, $matches) ){

				//if some problems then remove that if AND update filter
				if( count($matches[3]) > 1 ){

					foreach($matches[3] as $col){

						$column = $col;
						if( !$params[$column] && $params[$columnKey] )
							$column = $columnKey;

						if( $params[$column] ){

							switch ($pos){

								case 1:
									$params[$column] = '%'.$params[$column].'%';
								break;

								case 2:
									$params[$column] = '%'.$params[$column];
								break;

								case 3:
									$params[$column] = $params[$column].'%';
								break;
							}
						}
						$columnKey ++;
					}
				}
				else{
				
					$column = $matches[3][0];
					if( !$params[$column] && $params[$columnKey] )
						$column = $columnKey;

					if( $params[$column] ){

						switch ($pos){

							case 1:
								$params[$column] = '%'.$params[$column].'%';
							break;

							case 2:
								$params[$column] = '%'.$params[$column];
							break;

							case 3:
								$params[$column] = $params[$column].'%';
							break;
						}
					}
					$columnKey ++;
				}
			}
			$where = preg_replace('/([a-zA-Z0-9\_\._]+)([LIKE[:space:]_]+)'.$v.'/s', '\\1\\2:\\3', $where);
		}
		return array($where, $params);
	}

	private function prepareWhereExpression($where, $params){

		if( !preg_match('/\:([a-zA-Z0-9\_\._]+)/i', $where) )
			return array($where, array());

		$retParams = array();
		$whereArray = array();
		$isBetween = false;

		if( preg_match('/([a-zA-Z0-9\_\.[:space:]_]+)BETWEEN(.*?)\:([a-zA-Z0-9\_\?\.[:space:]_]+)/i', $where) ){

			//between
			foreach(preg_split('/AND | OR /s', $where, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE) as $v){
				
				if( $isBetween ){

					$whereArray[$lastKey] .= 'AND '.$v;
					$where = str_replace($whereArray[$lastKey], '{'.$lastKey.'}', $where);
					$isBetween = false;
					$lastKey = '';
				}

				if( preg_match('/([a-zA-Z0-9\_\.[:space:]_]+)BETWEEN(.*?)\:([a-zA-Z0-9\_\.[:space:]_]+)/i', $v) ){

					$key = md5(ltrim($v));
					$lastKey = $key;
					$whereArray[$key] = ltrim($v);
					$isBetween = true;
				}
			}
		}

		foreach(preg_split('/AND |OR /s', $where) as $v){

			$regexp = '(([a-zA-Z0-9\_\._]+)([a-zA-Z0-9[:space:]\:\_\-\=\>\<\+\*\/\\\%\"\'\!\(\?\.\,_]+)\:([a-zA-Z0-9\_\.\s_]+)\,([a-zA-Z0-9[:space:]\:\_\-\=\>\<\+\*\/\\\%\"\'\!\(\?\.\,_]+)\))';
			$regexp .= '|';
			$regexp .= '(([a-zA-Z0-9\_\._]+)([a-zA-Z0-9[:space:]\:\_\-\=\>\<\+\*\/\\\%\"\'\!\(\?\.\,_]+)\:([a-zA-Z0-9\_\._]+))';

			preg_match_all('/'.$regexp.'/s', $v, $matches);
			if( !empty($matches[0]) ){

				foreach($matches[0] as $v1){

					if( preg_match('/\(/i', $v1) && !preg_match('/\)/i', $v1) )
						$v1 .= ')';

					$key = md5($v1);
					$whereArray[$key] = $v1;
					$where = str_replace($v1, '{'.md5($v1).'}', $where);
				}
			}
		}

		if( !empty($whereArray) ){

			foreach($whereArray as $k => $v){

				preg_match_all('/([a-zA-Z0-9\_\._]+)([a-zA-Z0-9[:space:]\:\_\-\=\>\<\+\*\/\\\%\"\'\!\(\?\.\,_]+)\:/s', $v, $matches_1);
				preg_match_all('/\:([a-zA-Z0-9\.\__]+)/s', $v, $matches_2);
				if( !empty($matches_2[1]) ){

					foreach($matches_2[1] as $k1 => $column){

						if( preg_match('/BETWEEN/i', $v) )
							$tableColumn = $matches_1[1][0];
						else
							$tableColumn = $matches_1[1][$k1];

						if( !isset($params[$column]) ){

							$where = str_replace($k, '', $where);
						}
						else{

							$where = str_replace('{'.$k.'}', $v, $where);
							$retParams[$column] = $params[$column];
							$this->columnParams[$column] = $tableColumn;
						}
					}
				}
			}
		}

		foreach(array('AND', 'OR') AS $sep){

			$where = preg_replace('/'.$sep.'([[:space:]_]+)\{\}/s', '', $where);
			$where = preg_replace('/\{\}([[:space:]_]+)'.$sep.'/s', '', $where);
			$where = preg_replace('/'.$sep.'([[:space:]_]+)\(([\{\}[:space:]_]+)\)/s', '', $where);
		}
		$where = trim(preg_replace('/\{\}/s', '', $where));

		return array($where, $retParams);
	}

	function prepareInsert($values, $functionValues){

		foreach($functionValues as $column){

			$column = str_replace(':', '', $column);
			unset($values[$column]);
		}
		return $values;
	}

	function prepareSet($set, $params){

		$retSet = array();
		$retParams = array();

		//this is temporary solution
		//X-END-Z
		//problem is that preg_match_all gets all what named password - password and password_expires_at (this it gets partially)
		//role_id = :role_idX-END-Z/, password = :passwordX-END-Z/, password_expires_at = :password_expires_atX-END-Z/, account_expires_at = :account_expires_atX-END-Z/

		$set = preg_replace('/([a-zA-Z0-9\_\._]+)([<>=![:space:]_]+)\?/s', '\\1\\2:X-START-Z\\1X-END-Z/', $set);
		preg_match_all('/([a-zA-Z0-9\_\._]+)([a-zA-Z0-9[:space:]\:\_\-\=\>\<\+\*\/\\\%\"\'\!\(\?\.\,_]+)\:X-START-Z\\1X-END-Z/s', $set, $matches);
		if( !empty($matches[0]) ){

			foreach($matches[0] as $v1){

				$v1 = str_replace('X-START-Z', '', $v1);
				$v1 = str_replace('X-END-Z', '', $v1);

				if( preg_match('/\(/i', $v1) && !preg_match('/\)/i', $v1) )
					$v1 .= ')';

				$pattern = $v1;
				$column = $this->prepareColumnName($pattern);
				if( $params[$column] && $this->prepareSqlFunction($params[$column]) ){

					$retSet[] = str_replace(':'.$column, $params[$column], $pattern);
				}
				else{

					$retSet[] = $pattern;
					$retParams[$column] = $params[$column];
				}
			}
		}

		return array(implode(', ', $retSet), $retParams);
	}

	function prepareParams($params){

		$ret = array();
		foreach($params as $k => $v){

			if( $v !== \NULL )
				$ret[$k] = $v;
			if( strtoupper($v) === 'NULL' )
				$ret[$k] = \NULL;
		}
		return $ret;
	}

	private function prepareCheckQuery($Query){

		$checkQuery = explode(' ', trim($Query));
		$action = trim(strtoupper($checkQuery[0]));
		if($action == 'UPDATE' || $action == 'DELETE'){

			//if( preg_match('/UPDATE([[:space:]_]+)/i', strtoupper($Query)) || preg_match('/DELETE([[:space:]_]+)FROM/i', strtoupper($Query)) ){

				if( 
					!preg_match('/WHERE(.*?)([a-zA-Z\_[:space:]_]+)\=|\<|\>/i', strtoupper($Query)) && 
					!preg_match('/WHERE(.*?)IN\(/i', strtoupper($Query)) && 
					!preg_match('/WHERE(.*?)LIKE/i', strtoupper($Query)) &&
					!preg_match('/WHERE(.*?)BETWEEN/i', strtoupper($Query)) 
				){

					/*if( $returnError )
						return 'WHERE MISSING';*/
					new \Library\Component\Error('<b style="color:red;">Update and Delete Query Must have WHERE</b>', $Query);
					return true;
				}
				if(
					$action == 'UPDATE' && 
					!preg_match('/SET([a-zA-Z0-9\_[:space:]_]+)(\=|\<|\>)(.*?)WHERE/i', strtoupper($Query))
				){

					/*if( $returnError )
						return 'SET MISSING';*/
					new \Library\Component\Error('<b style="color:red;">Update Query Must have SET - rebuild columns at /command/table</b>', $Query);
					return true;
				}
			//}
		}
		return false;
	}

	public function prepareGetQuery($Query, $params){

		if( empty($params) )
			return $Query;

		$replaceParams = array();
		foreach($params as $key => $value){

			$tableColumn = $this->columnParams[$key];
			$replaceParams[':'.$key] = ( in_array($tableColumn, $this->_integerFields) ? $value : "'".$value."'" );
		}
		return strtr($Query, $replaceParams);
	}

	private function prepareSqlFunction($value){

		preg_match('/(.*?)\(/s', $value, $match);
		$start = $match[1];

		/*
		echo 'START='.$start .' RET='. (gettype($value) == 'string' && $start == strtoupper($start) && strlen($start) > 1 && substr($value, -1) == ')' && !preg_match('/\s/i', trim($start)) && preg_match('/([A-Z\_[:space:]_]+)\(/i', $value));
		echo '<br>';
		*/

		if( $this->prepareSqlStatement($value) )
			return true;

		if( strtoupper(explode(' ', $start)[0]) == 'CASE' )
			return true;

		if(gettype($value) == 'string' && $start == strtoupper($start) && strlen($start) > 1 && substr($value, -1) == ')' && !preg_match('/\s/i', trim($start)) && preg_match('/([A-Z\_[:space:]_]+)\(/i', $value))
			return true;
		return false;
	}

	private function prepareSqlStatement($value){

		if( preg_match('/^\(/i', $value) && preg_match('/\)$/i', $value) && (preg_match('/\(SELECT/i', $value) || preg_match('/\(\sSELECT/i', $value)) )
			return true;
		return false;
	}
}
?>