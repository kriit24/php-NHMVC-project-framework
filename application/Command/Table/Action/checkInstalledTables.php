<?
namespace Command\Table\Action;

abstract class checkInstalledTables{

	static $error = array('installed' => false, 'column_matches' => false);

	public static function get($return){

		$db = \Conf\Conf::_DB_CONN['_default']['_database'];
		$library = new \Library;

		$sql = new \Library\Sql;
		$sql2 = new \Library\Sql;
		if( !$sql->isConnected() )
			return;

		$tables = array();

		$sql->query("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE' ");
		while($row = $sql->fetch()){

			$row['name'] = $row['Tables_in_'.$db];
			$row['installed'] = true;
			$row['column_matches'] = true;
			//we dont need continue, if error occurs
			if( (self::$error['installed'] == true || self::$error['column_matches'] == true) && $return == false )
				break;

			if( !is_file(dirname(__DIR__, 3).'/'.\Conf\Conf::_DB_ROOT_DIR.'/'.$row['Tables_in_'.$db].'.php') ){

				self::$error['installed'] = true;
				$row['installed'] = false;
				//we dont need continue, if error occurs
				if( $return == false )
					break;
			}
			else{

				//it cannot access to file same time when file is written
				if( $_GET['action'] == 'rebuild' && $_GET['table'] == $row['name'] )
					continue;

				$class = create_function('', 'return new \\'.\Conf\Conf::_DB_ROOT_DIR.'\\'.$row['name'].';')();
				$reflection = \Library::reflection( '\\'.\Conf\Conf::_DB_ROOT_DIR.'\\'.$row['name'] );
				$property = $reflection->getProperty( '_validFields' );
				$property->setAccessible(true);
				$variable = $property->getValue( $class );

				$sql2->query("SHOW COLUMNS FROM ".$db.".".$row['name'])->fetch();
				if( $sql2->Numrows() != count($variable) ){

					self::$error['column_matches'] = true;
					$row['column_matches'] = false;
				}
				else{

					while($row2 = $sql2->fetch()){

						if( !in_array($row2['Field'], $variable) ){

							self::$error['column_matches'] = true;
							$row['column_matches'] = false;
							if( $return == false )
								break;
						}
					}
				}
			}
			if( $return == true )
				$tables[] = $row;
		}
		return array($tables, self::$error);
	}
}
?>