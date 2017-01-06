<?
namespace Command\Table\Action;

abstract class install{

	public static function init(){

		if( !is_file(dirname(__DIR__, 3).'/'.\Conf\Conf::_DB_ROOT_DIR.'/'.$_GET['table'].'.php') ){

			$library = new \Library;
			$sql = new \Library\Sql;
			$database = \Conf\Conf::_DB_CONN['_default']['_database'];

			$sql->query("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE' AND Tables_in_".$database." = '".$_GET['table']."'")->fetch();
			if( $sql->Numrows() == 0 )
				return array('err' => 'Table does not exists');

			$fields = '';
			$integerFields = '';
			$sql->query("SHOW COLUMNS FROM ".$_GET['table']);
			while($row = $sql->fetch()){

				$fields .= $fields ? ",'".$row['Field']."'" : "'".$row['Field']."'";
				if( preg_match('/int\(/i', $row['Type']) )
					$integerFields .= $integerFields ? ", '".$row['Field']."'" : "'".$row['Field']."'";
			}

			$content = file_get_contents(dirname(__FILE__, 2).'/inc/template.tpl');
			$content = $library->replace($content, array('name' => $_GET['table'], 'fields' => $fields, 'indeger_fields' => $integerFields));
			file_put_contents(dirname(__DIR__, 3).'/'.\Conf\Conf::_DB_ROOT_DIR.'/'.$_GET['table'].'.php', $content);
			return array('msg' => 'Table installed');
		}
		return array('err' => 'Table allready installed');
	}
}
?>