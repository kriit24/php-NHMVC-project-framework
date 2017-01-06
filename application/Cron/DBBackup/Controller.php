<?
namespace Cron\DBBackup;

class Controller{

	public function backupDir($dir){

		if( !is_dir($dir) )
			mkdir($dir);
		return $dir;
	}

	public function Control(){

		$_bakupDir = $this->backupDir( _DIR.'/tmp/DB' );
		$_bakupDir = $this->backupDir( $_bakupDir .'/'. date('d.m.Y') );

		$db = new \Library\Sql;
		$db2 = new \Library\Sql;
		$db->query("SHOW TABLES");
		while($row = $db->fetch()){

			$value = array_values($row);
			$table = $value[0];
			if( !is_file($_bakupDir.'/'.$table.'.sql') ){

				$row2 = $db2->query("SHOW CREATE TABLE `".$table."`")->fetch();
				$value2 = array_values($row2);

				$db2->query("SELECT * FROM `".$table."`");
				$content = $value2[1]."\n\n\n";
				$content .= "INSERT INTO `" . $table . "` ";
				$i = 1;
				while($row = $db2->fetch()){

					if( $i == 1 ){

						$content .= "(";
						foreach ($row as $field => $value)
							$content .= " `" . $field . "`, ";
						$content = trim($content, ", ");
						$content .= ")";
						$content .= " VALUES\n";
					}

					$content .= "(";
					foreach ($row as $field => $value)
						$content .= "'" . $value . "', ";
					$content = trim($content, ", ");
					$content .= "), \n";
					$i++;
				}
				$content = trim($content, ", \n")."\n";

				file_put_contents($_bakupDir.'/'.$table.'.sql', $content);
				break;
			}
		}
	}
}

?>