<?
namespace Command\Table\Action;

abstract class rebuild{

	public static function init(){

		$sucess = false;

		if( is_file(dirname(__DIR__, 3).'/'.\Conf\Conf::_DB_ROOT_DIR.'/'.$_GET['table'].'.php') ){

			$sql = new \Library\Sql;
			$fileSystem = new \Library\FileSystem;

			$fields = '';
			$integerFields = '';
			$sql->query("SHOW COLUMNS FROM ".$_GET['table']);
			while($row = $sql->fetch()){

				$fields .= $fields ? ", '".$row['Field']."'" : "'".$row['Field']."'";
				if( preg_match('/int\(/i', $row['Type']) )
					$integerFields .= $integerFields ? ", '".$row['Field']."'" : "'".$row['Field']."'";
			}

			$readLines = false;
			$firstLine = false;
			$lines = $fileSystem->fileToLines(dirname(__DIR__, 3).'/'.\Conf\Conf::_DB_ROOT_DIR.'/'.$_GET['table'].'.php');
			foreach($lines as $k => $line){

				if( preg_match('/\$\_validFields/i', $line) ){

					$readLines = true;
					$firstLine = true;
				}
				if( preg_match('/\$\_integerFields/i', $line) ){

					$readLines = true;
				}

				if( $readLines ){

					if( $firstLine ){

						$lines[$k] = "\t".'protected $_validFields = array('.$fields.');'."\n";
						$lines[$k] .= "\t".'protected $_integerFields = array('.$integerFields.');'."\n";
					}
					else{

						unset($lines[$k]);
					}
					if( preg_match('/\;/i', $line) )
						$readLines = false;
					$firstLine = false;
				}
			}
			file_put_contents(dirname(__DIR__, 3).'/'.\Conf\Conf::_DB_ROOT_DIR.'/'.$_GET['table'].'.php', implode($lines));
			return array('msg' => 'Table updated');
		}
		return array('err' => 'Table FILE does not exists, use install');
	}
}
?>