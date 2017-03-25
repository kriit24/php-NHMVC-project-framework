<?
namespace Command\Create\Action;

abstract class createModel{

	public static function init( $folder ){

		return ;

		$fileSystem = new \Library\FileSystem;
		$success = false;

		if( is_dir(dirname(__DIR__).'/inc/template/'.$folder) )
			$scandir = dirname(__DIR__).'/inc/template/'.$folder;
		else
			$scandir = dirname(__DIR__).'/inc/template/'.\Command\Create\Form::TEMPLATE;

		if( is_dir($scandir) ){

			$column_elems_form = self::createForm();
			$column_elems_data = self::createForm( 'data' );

			foreach($fileSystem->scandir($scandir, true) as $v){

				$file = $fileSystem->dir($scandir.'/'.$v);

				if( is_file($file) ){

					$array = array();

					if( preg_match('/parent/i', $v) ){

						$v = str_replace('parent', '', $v);
						$array['folder'] = $fileSystem->mkdir( dirname(__DIR__, 3).'/'.$_POST['folder'] . ( dirname($v) != '.' ? '/'.str_replace($scandir, '', dirname($v)) : '') );
					}
					else
						$array['folder'] = $fileSystem->mkdir( dirname(__DIR__, 3).'/'.$_POST['folder'].'/'.$_POST['name'] . ( dirname($v) != '.' ? '/'.str_replace($scandir, '', dirname($v)) : '') );
					
					$array['route'] = $_POST['folder'];
					$array['namespace'] = str_replace('/', '\\', str_replace(dirname(__DIR__, 3).'/', '', $array['folder']));
					$array['name'] = basename($_POST['name']);
					$array['uname'] = ucfirst(basename($_POST['name']));
					$array['class'] = ucfirst(basename($_POST['name']));
					$array['method'] = ucfirst(basename($_POST['name']));
					$array['file'] = basename(str_replace($scandir.'/', '', $v));
					$array['file'] = replace::init($array['file'], $array);
					$array['table'] = $_POST['table'];
					$array['column_elems_form'] = $column_elems_form;
					$array['column_elems_data'] = $column_elems_data;

					if( !is_file( $array['folder'] .'/'. $array['file']) ){

						$success = true;
						$content = file_get_contents($file);
						if( !in_Array(basename($v), array('script.js', 'style.css')) )
							$content = replace::init($content, $array);
						file_put_Contents( $array['folder'] .'/'. $array['file'], $content );
					}
				}
			}
		}
		return $success;
	}

	private static function createForm( $type = null ){

		if( !$_POST['table_column'] )
			return '';

		$db = \Conf\Conf::_DB_CONN['_default']['_database'];
		$sql = new \Library\Sql;
		$columns = $sql->Query("SHOW COLUMNS FROM ".$db.".".$_POST['table'])->fetchAll();
		$ret = '';

		foreach($_POST['table_column'] as $column){

			foreach($columns as $col){

				if( $col['Field'] == $column ){

					if( !$type ){

						if( preg_match('/char|text/i', $col['Type']) ){

							$type = 'text';
						}
					}

					$ret .= $ret ? "\n\n\t\t" : '';
					$ret .= '$form->addElem(\''.$type.'\', \''.$column.'\', array('."\n\t\t\t".'\'label\' => $this->Language(\''.ucfirst(str_replace('_', ' ', $column)).'\'),'."\n\t\t\t".'\'\' => \'\','."\n\t\t".'));';
				}
			}
		}
		return $ret;
	}
}
?>