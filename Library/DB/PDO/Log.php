<?
namespace Library\DB\PDO;

trait Log{

	static function queryLog( $table, $queryType, $Query, $params ){

		if( empty($Query) )
			return;

		if( self::explainLog($table, $queryType, $Query, array(), true) ){

			$sql = new \Library\Sql();

			$PDO = $sql->getConnection( $sql->_connName );
			if( $sql->isConnected() ){

				$stmt = $PDO->prepare("EXPLAIN EXTENDED " . $Query);
				$stmt->execute($params);
				$r = $stmt->fetchAll( $sql->fetchMode );
				$ExplainQuery = $sql->prepareGetQuery($Query, $params);

				self::explainLog($table, $queryType, $Query, $r, false);
				self::noIndexUsedLog($table, $queryType, $ExplainQuery, $r);
			}
		}
	}

	static function explainLog( $table, $queryType, $Query, $data, $return = false ){

		$indexQueryLogFile = _DIR .'/tmp/DB/index_log/' . $table .'/'. $queryType .'_'. md5($Query).'.php';

		if( is_file($indexQueryLogFile) )
			return;

		if( $return )
			return true;

		if( !is_dir( dirname($indexQueryLogFile) ) )
			mkdir( dirname($indexQueryLogFile), 0755, true );

		file_put_contents($indexQueryLogFile, $queryType . "\n\n" . json_encode($data, JSON_PRETTY_PRINT));
	}

	static function noIndexUsedLog( $table, $queryType, $Query, $data ){

		$noIndex = array();
		foreach($data as $row){

			if( $row['key'] === \NULL ){

				$noIndex = $data;
				break;
			}
		}

		if( !empty($noIndex) ){

			$indexQueryLogFile = _DIR .'/tmp/DB/index_notused_log/' . $table .'/'. $queryType .'_'. md5($Query).'.php';
			if( !is_dir( dirname($indexQueryLogFile) ) )
				mkdir( dirname($indexQueryLogFile), 0755, true );

			file_put_contents($indexQueryLogFile, $queryType . "\n\n" . "EXPLAIN EXTENDED " . $Query . "\n\n" . json_encode($noIndex, JSON_PRETTY_PRINT));
		}
	}

	static function getQueryType( $stmtArray ){

		$type = isset($stmtArray['SELECT']) ? 'SELECT' : '';
		if( isset($stmtArray['INSERT']) )
			$type = 'INSERT';
		if( isset($stmtArray['UPDATE']) )
			$type = 'UPDATE';
		if( isset($stmtArray['DELETE']) )
			$type = 'DELETE';

		return $type;
	}
}
?>