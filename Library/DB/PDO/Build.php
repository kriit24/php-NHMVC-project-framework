<?
namespace Library\DB\PDO;

trait Build{

	private function buildEach($key, $params){

		$string = '';
		foreach($params as $v){

			$string .= ($string ? ' ' : '') . ($key ? $key.' ' : '') . $v;
		}
		return $string;
	}

	private function buildWhere($where){

		$string = '';
		foreach($where as $v){

			$expression = $v;
			$string .= $string ? ' AND ' : '';
			$string .= $expression;
		}
		return ($string ? 'WHERE ' : '') . $string;
	}

	private function build(){

		if( !$this->stmtArray )
			return $this->Query;

		$Query = '';
		
		//pre($this->stmtArray);
		//pre($this->params);
		//pre($this->stmtQueye);
		//pre($this->prepareParams);

		foreach($this->stmtQueye as $key){

			if( isset($this->stmtArray[$key]) ){

				$v = $this->stmtArray[$key];
				if( is_Array($v) ){

					switch( $key ){

						case 'WHERE':
							$Query .= ($Query ? ' ' : ' ') . $this->buildWhere($v);
						break;
					}
					if( preg_match('/JOIN/i', $key) )
						$Query .= ($Query ? ' ' : ' ') . $this->buildEach($key, $v);
				}
				else{

					$Query .= ($Query ? ' ' : ' ') . (!in_array($key, array('COLUMN')) ? $key.' ' : '') . ($v);
				}
			}
		}
		//echo $Query.'<br>';
		return $Query;
	}

	function buildQuery($Query, $params){

		if( !$Query ){

			$Query = $this->build();
			unset($this->stmtArray);
		}
		if( !$params )
			$params = $this->params;

		return array($Query, $params);
	}
}
?>