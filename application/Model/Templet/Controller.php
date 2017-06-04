<?
namespace Model\Templet;

class Controller extends \Library{

	var $data = array();
	var $files = array();
	var $styleContent = array();
	var $hiddenStyleContent = array();
	var $restoreData = array();
	var $preStyle = false;

	const ORIGIN_BACKUP_FOLDER = _DIR .'/tmp/theme/origin';
	const BACKUP_FOLDER = _DIR .'/tmp/theme/backup';

	function getTemplate(){

		if( !is_dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public') )
			return $this;

		
		if( is_dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/css') ){

			$files = $this->scanfile( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/css' );
			$this->readFiles( $files, array('flag-icon'), true );
		}
		if( is_Dir(_DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/js') ){

			$files = $this->scanfile( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/js' );
			$this->readFiles( $files, array(), true );
		}

		$this->data = \Library\Component\Cache::get('template');

		$dir = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public/design/';
		if( is_File($dir . 'header-pre.php') ){

			foreach( array('header-pre.php', 'body-pre.php', 'footer-pre.php') as $file ){

				$this->styleContent[] = array('file' => $file, 'content' => utf8_encode(file_get_contents($dir . $file)));
			}
			$this->preStyle = true;
		}
		else{

			foreach( array('header.php', 'body.php', 'footer.php') as $file ){

				$this->styleContent[] = array('file' => $file, 'content' => utf8_encode(file_get_contents($dir . $file)));
			}
		}

		foreach( array('header.php', 'body.php', 'footer.php', 'footer.min.php') as $file ){

			if( is_file($dir . 'inc/' . $file) )
				$this->hiddenStyleContent[] = array('file' => $file, 'content' => file_get_contents($dir . 'inc/' . $file));
		}

		$this->restoreData = $this->scandir( self::BACKUP_FOLDER );

		return $this;
	}

	private function readFiles( $files, $exclude = array(), $getFile = false ){

		foreach($files as $file){

			$fileContent = file_get_contents($file);

			if( $getFile )
				$this->files[] = array('file' => basename($file), 'path' => str_replace(array(_DIR, '/application/Template/project/'), array('', '/Template/'), dirname($file)), 'content' => utf8_encode($fileContent));

			if( in_array(pathinfo(basename($file), PATHINFO_FILENAME), $exclude) )
				continue;
		}
	}

	public function getStyles(){

		$this->styles = glob(_DIR . '/application/Template/*', GLOB_ONLYDIR);

		return $this;
	}

	public function backupIfNeeded(){

		if( !is_dir(_DIR . '/tmp/theme') )
			mkdir(_DIR . '/tmp/theme');

		if( !is_dir( self::ORIGIN_BACKUP_FOLDER ) ){

			mkdir(self::ORIGIN_BACKUP_FOLDER, 0755, true);
			$this->fileCopyRec( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/', '', self::ORIGIN_BACKUP_FOLDER );
		}

		if( !is_dir( self::BACKUP_FOLDER ) ){

			mkdir(self::BACKUP_FOLDER, 0755, true);
			$this->fileCopyRec( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/', '', self::BACKUP_FOLDER .'/'. date('d.m.Y_H.i.s') );
		}

		if( $_POST['addFile'] || $_GET['action'] == 'deleteFile' || $_POST['updateDesign'] ){

			$today = self::BACKUP_FOLDER . '/' . date('d.m.Y_H.i.s');
			if( !is_dir($today) )
				mkdir($today);

			$this->fileCopyRec( _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/', '', $today );
		}
	}

	public static function replaceStylingKeys( $content ){

		preg_match_all('/\{\[([a-zA-Z0-9\-_]+)\]\}/s', $content, $matches);
		if( !empty($matches[1]) ){

			$data = \Library\Component\Cache::get('template');

			foreach($matches[1] as $key){

				$content = str_replace('{[' . $key . ']}', $data[ $key ], $content);
			}
		}

		return $content;
	}

	public static function setStylingContent( $type, $content ){

		if( !\Library\Component\Register::inRegister( 'styling' ) )
			\Library\Component\Register::register( 'styling', array($type => $content), \Library\Component\Register::IS_ARRAY );
		else
			\Library\Component\Register::setRegister( 'styling', array($type => $content), \Library\Component\Register::IS_ARRAY );
	}

	public static function getStylingContent( $siblingDir, $replaceTags = true ){

		$dir = _DIR .'/'. _APPLICATION_PATH .'/Template/' . \Conf\Conf::_TEMPLATE .'/public/' . $siblingDir;
		$ret = '';

		if( is_dir($dir) ){

			$files = \Library\FileSystem::singleton()->scanFile( $dir );
			foreach($files as $file){

				if( $replaceTags && !preg_match('/\.min\./i', $file) )
					$ret .= self::replaceTags(file_get_contents($file)) . "\n";
				else
					$ret .= file_get_contents($file) . "\n";
			}
		}

		$Includes = \Library\Component\Register::getRegister('INCLUDES');

		if( $Includes ){

			foreach($Includes as $href){

				if( pathinfo(basename($href), PATHINFO_EXTENSION) == $siblingDir ){

					$ret .= file_get_Contents(_DIR . $href);
				}
			}
		}
		if( \Library\Component\Register::inRegister( 'styling' ) ){

			$styling = \Library\Component\Register::getRegister( 'styling' );
			if( $styling[$siblingDir] )
				$ret .= $styling[$siblingDir];
		}
		return $ret;
	}

	//THIS IS REQUIRED
	public static function replaceTags( $content ){

		$array = \Library\Component\Cache::get('template');
		if( !empty($array) ){

			$content = preg_replace_callback('/\{\[([a-zA-Z0-9\_\-_]+)\]\}/', function( $matches ) use ( $array ) {return $array[$matches[1]];}, $content);
			$content = preg_replace_callback('/url\((.*?)\)/s', function( $matches ) use ( $array ) {
				$key = htmlspecialchars(str_replace(array("'", '"', ':', ';', '{[', ']}'), '', $matches[1]));
				$key = str_replace('.', '_', $key);
				if( !isset($array[$key]) || empty($array[$key]) )
					return $matches[0];
				return 'url("' . $array[$key] . '")';
			}, $content);
		}

		return $content;
	}
}
?>