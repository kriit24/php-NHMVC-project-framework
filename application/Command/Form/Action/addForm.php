<?
namespace Command\Form\Action;

abstract class addForm{

	public static function init($data, $columns){

		$dir = _DIR . '/' . _APPLICATION_PATH . '/' . $data['route_name'] . '/' . $data['app_name'];

		if( is_dir( $dir ) ){

			if( !is_file( $dir . '/Form.php' ) ){

				$method = self::formMethod($data, $columns);
				$data['form_method'] = $method;
				$form = self::formClass($data);
				if( self::writeFormFile( $dir.'/Form.php', $form, $data, false ) )
					return array('Form created/updated', '');
				else
					return array('', 'Method allready exists');
			}
			else{

				$content = self::formMethod($data, $columns);
				if( self::writeFormFile( $dir.'/Form.php', $content, $data, true ) )
					return array('Form created/updated', '');
				else
					return array('', 'Method allready exists');
			}
		}
		return array('', 'Route or app name does not exists');
	}

	private static function formClass($data){

		$content = file_Get_Contents(dirname(__DIR__) . '/inc/Form-class-tpl.php');
		return \Library\Replace::singleton()->replace($content, $data);
	}

	private static function formMethod($data, $columns){

		$content = file_get_contents(dirname(__DIR__) . '/inc/Form-method-tpl.php');
		$methodColumns = '';
		foreach($columns as $column){

			$methodColumns .= ($methodColumns ? "\n\n" : "");
			$methodColumns .= "\t\t".'$form->addElem(\''.$column->column_type.'\', \''.$column->column_name.'\', array(';
			if( $column->column_label )
				$methodColumns .= "\n\t\t\t".'\'label\' => \''.$column->column_label.'\'';
			$methodColumns .= "\n\t\t".'));';
		}
		$data['method_columns'] = $methodColumns;
		$content = \Library\Replace::singleton()->replace($content, $data);
		return $content;
	}

	private static function writeFormFile($file, $content, $data, $extend = false){

		if( $extend ){

			if( preg_match('/public function '.$data['form_name'].'\(/i', file_get_contents($file)) )
				return;

			$key = 0;
			foreach( array_reverse(\Library\FileSystem::singleton()->fileToLines($file), true) as $k => $v ){

				if( preg_match('/\}/i', $v) ){

					$key = $k;
					break;
				}
			}

			$fullContent = '';

			foreach( \Library\FileSystem::singleton()->fileToLines($file) as $k => $line ){

				if( $k == $key )
					$fullContent .= "\n".$content ."\n". $line;
				else
					$fullContent .= $line;
			}
			//echo pre(htmlspecialchars($fullContent));
			$content = $fullContent;
		}
		file_put_contents($file, $content);
		return true;
	}
}
?>