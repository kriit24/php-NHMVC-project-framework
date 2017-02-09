<?
(boolean)$autoload = (boolean)$die = true;

class Autoload{

	private $option = array();
	private $require = array();
	private $applicationPath = '';

	//$applicationPath - if u want set application path as main path then u can call application classes like new Model\modelname\filename instead of new \application\Model\modelname\filename
	function __construct( $applicationPath = '' ){

		//\Library\Component\loadTime::start();
		if( $applicationPath )
			$this->applicationPath = $applicationPath;
		spl_autoload_register(array('Autoload', 'autoload'));
		spl_autoload_extensions('.php');
		//echo \Library\Component\loadTime::end();
	}

	static function register(){

		global $autoload;
		$autoload = true;
	}

	static function unregister(){

		global $autoload;
		$autoload = false;
	}

	static function dieOnError(){

		global $die;
		$die = true;
	}

	static function undieOnError(){

		global $die;
		$die = false;
	}

	private function setOption( $name, $value ){

		$this->option[$name] = $value;
	}

	private function getBasePath( $className ){

		$strpos = strpos($className, $this->getSeparator());
		if( !$strpos )
			$classBaseName = $className;
		else
			$classBaseName = substr($className, 0, $strpos);

		if( is_dir(get_include_path() .DIRECTORY_SEPARATOR. $this->applicationPath .DIRECTORY_SEPARATOR. $classBaseName) )
			$this->_dir = get_include_path() .DIRECTORY_SEPARATOR. $this->applicationPath;
		else
			$this->_dir = get_include_path();
	}

	private function getSeparator(){

		if( $this->option['separator'] )
			return $this->option['separator'];
		else
			return false;
	}

	private function getPath(){

		return $this->option['path'];
	}

	private function getFile(){

		return $this->option['file'];
	}

	private function getNamespace(){

		return $this->option['namespace'];
	}

	private function autoload( $className ){

		global $autoload;
		if( !class_exists( $className, false ) && gettype($autoload) == 'boolean' && $autoload == true ){

			if( _DEBUG == 'autoload' )
				echo '<br><b>$className='.$className.' EXISTS '.class_exists( $className, false ).'</b>';

			if( preg_match('/\\\/i', $className) && substr($className, 0, 1) != '\\' )
				$this->setOption( 'separator', '\\' );
			else if( preg_match('/_/i', $className) && substr($className, 0, 1) != '_' )
				$this->setOption( 'separator', '_' );
			else
				$this->setOption( 'separator', '' );

			$this->setOption( 'namespace', substr($className, 0, strrpos($className, '\\')) );
			$this->getBasePath( $className );
			$this->setPath( $className );
			$this->setFile( $className );

			if( _DEBUG == 'autoload' ){

				print '<pre>';
				print_r ($this->option);
				print '</pre>';
				echo 'REQUIRE='.$this->getPath(). DIRECTORY_SEPARATOR .$this->getFile().'<br>';
			}
			$this->require();
		}
	}

	private function setPath( $className ){

		if( $this->getNamespace() ){

			$dir = str_replace($this->getSeparator(), DIRECTORY_SEPARATOR, $this->getNamespace());

			if( preg_match('/_/i', $className) && !preg_match('/\_/i', $className) && $this->getSeparator() != '_' ){

				$dirTest = str_replace($this->getSeparator(), DIRECTORY_SEPARATOR, substr($className, 0, strrpos($className, '_')));
				$dirTest = str_replace('_', DIRECTORY_SEPARATOR, $dirTest);
				
				if( is_dir($this->_dir . DIRECTORY_SEPARATOR . $dirTest) )
					$dir = $dirTest;
			}
		}
		else
			$dir = str_replace($this->getSeparator(), DIRECTORY_SEPARATOR, substr($className, 0, strrpos($className, $this->getSeparator())));

		$this->setOption( 'path', $this->_dir . ($dir ? DIRECTORY_SEPARATOR. $dir : '') );
	}

	private function setFile( $className ){

		$file = str_replace($this->getSeparator(), DIRECTORY_SEPARATOR, $className).'.php';
		if( $this->getSeparator() )
			$file = str_replace($this->getSeparator(), DIRECTORY_SEPARATOR, end(explode($this->getSeparator(), $className))).'.php';
		if( preg_match('/_/i', $className) && !preg_match('/\_/i', $className) && $this->getSeparator() != '_' ){

			$fileTest = str_replace($this->getSeparator(), DIRECTORY_SEPARATOR, $className).'.php';
			$fileTest = str_replace('_', DIRECTORY_SEPARATOR, $fileTest);
			if( is_File($this->_dir . DIRECTORY_SEPARATOR . $fileTest) ){

				$file = end(explode(DIRECTORY_SEPARATOR, $fileTest));
			}
		}
		$this->setOption( 'file', $file );
		$this->setDebug();
	}

	private function require(){

		global $die;

		if( !is_file($this->getPath(). DIRECTORY_SEPARATOR .$this->getFile()) ){

			if( $die )
				die('AUTOLOAD NOT FOUND FILE:<br>'.implode('<br>', $this->require) . str_replace('/application', '<b style="color:red;">/application</b>', '<pre>'.\Library\Component\Trace::get() . '</pre>') );
			else
				return false;
		}
		unset($this->require);
		require_once $this->getPath(). DIRECTORY_SEPARATOR .$this->getFile();
	}

	private function setDebug(){

		$fullLocation = $this->getPath() .DIRECTORY_SEPARATOR. $this->getFile();
		if( !in_array($fullLocation, (empty($this->require) ? array() : $this->require)) )
			$this->require[] = $fullLocation;
	}
}

?>