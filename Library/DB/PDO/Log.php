<?
namespace Library\DB\PDO;

trait Log{

	static function indexLog( $table, $queryType, $indexParams ){

		if( empty($indexParams) )
			return;

		$indexQueryLogFile = _DIR .'/tmp/DB/index_log/' . $table .'/'. $queryType .'_'. md5(json_encode($indexParams)).'.php';
		if( is_file($indexQueryLogFile) )
			return;

		if( !is_dir( dirname($indexQueryLogFile) ) )
			mkdir( dirname($indexQueryLogFile), 0755, true );

		file_put_contents($indexQueryLogFile, $queryType . "\n\n" . json_encode($indexParams));
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