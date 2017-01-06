<?
namespace Library\DB\PDO;

trait Log{

	static function log($table, $Query){

		$check_Query = explode(' ', trim($Query));
		$action = strtoupper($check_Query[0]);

		$data = addslashes($Query);
		$q = "INSERT INTO log (`table_name`, `action`, `data`, `type`, `created_by`, `created_by_id`, `created_at`) VALUES ('".$table."', '".$action."', '".$data."', 'LOG', '".\Library\Session::userData()->user_name."', ".\Library\Session::userData()->user_id.", NOW())";
		$this->Query($q);
	}

	static function archive($table, $table_id, $Query){

		$check_Query = explode(' ', trim($Query));
		$action = strtoupper($check_Query[0]);
		$table_id = self::getTableId($Query);

		if( in_array($action, array('INSERT')) ){

			$data = addslashes(json_encode(array('GET' => $_GET, 'POST' => $_POST, 'Query' => addslashes($Query))));
			$q = "INSERT INTO log (`table_name`, `table_id`, `action`, `data`, `type`, `created_by`, `created_by_id`, `created_at`) VALUES ('".$table."', '".$table_id."', '".$action."', '".$data."', 'ARCHIVE', '".\Library\Session::userData()->user_name."', ".\Library\Session::userData()->user_id.", NOW())";
			//die($q);
			$this->Query($q);
		}
		if( in_array($action, array('UPDATE')) ){

			$qStmt = $this->Query("SELECT * FROM ".$table." WHERE id = ".$table_id);
			$qData = array();
			while($row = $qStmt->fetch()){

				$qData[] = $row;
			}

			$data = addslashes(json_encode(array('GET' => $_GET, 'POST' => $_POST, 'Query' => addslashes($Query))));
			$q = "INSERT INTO log (`table_name`, `table_id`, `action`, `data`, `type`, `created_by`, `created_by_id`, `created_at`) VALUES ('".$table."', '".$table_id."', '".$action."', '".$data."', 'ARCHIVE', '".\Library\Session::userData()->user_name."', ".\Library\Session::userData()->user_id.", NOW())";
			//die($q);
			$this->Query($q);
		}
		if( in_array($action, array('DELETE')) ){

			$qStmt = $this->Query("SELECT * FROM ".$table." WHERE id = ".$table_id);
			$qData = array();
			while($row = $qStmt->fetch()){

				$qData[] = $row;
			}

			$data = addslashes(json_encode(array('GET' => $_GET, 'POST' => $_POST, 'DATA' => json_encode($qData), 'Query' => addslashes($Query))));
			$q = "INSERT INTO log (`table_name`, `table_id`, `action`, `data`, `type`, `created_by`, `created_by_id`, `created_at`) VALUES ('".$table."', '".$table_id."', '".$action."', '".$data."', 'ARCHIVE', '".\Library\Session::userData()->user_name."', ".\Library\Session::userData()->user_id.", NOW())";
			$this->Query($q);
		}
	}

	private static function getTableId($Query){

		preg_match('/WHERE([[:space:]_]+)id([[:space:]=_]+)([0-9_]+)/s', $Query, $match);
		if( is_numeric($match[3]) )
			return $match[3];
		return false;
	}
}
?>