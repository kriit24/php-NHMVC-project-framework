<?

require dirname(__DIR__).'/Component/functions.php';
require dirname(__DIR__).'/Component/loadTime.php';
require dirname(__DIR__).'/Loader/Autoload.php';

abstract class Application{

	private $autoloader = false;

	function __construct($autoloader = true){

		$this->autoload = new Autoload( _APPLICATION_PATH );
		$this->autoload($autoloader);
	}

	function autoload($autoloader){

		$appPath = get_include_path() .DIRECTORY_SEPARATOR. basename(_APPLICATION_PATH);

		//application root autoload
		if( is_file($appPath . DIRECTORY_SEPARATOR . '_Autoload.php') )
			require_once $appPath . DIRECTORY_SEPARATOR . '_Autoload.php';

		//library autoload
		if( is_file(__DIR__ . DIRECTORY_SEPARATOR . '_Autoload.php') )
			require_once __DIR__ . DIRECTORY_SEPARATOR . '_Autoload.php';

		$this->autoloader = $autoloader;
		if( $autoloader == true ){

			//application subfolder autoload
			foreach( glob($appPath.'/*/_Autoload.php') as $file )
				require_once $file;
		}
		else{

			require_once __DIR__ . '/_Autoload.php';
		}
	}

	function __destruct(){

		$autoloadEnd = Library\Component\Register::getRegister('AUTOLOAD_END');

		if( is_Array($autoloadEnd) )
			foreach($autoloadEnd as $class)
				$class::end( $this->autoloader );

		//END
	}
}

?>