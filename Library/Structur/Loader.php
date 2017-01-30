<?
namespace Library\Structur;

class Loader{

	private $fileSystem;
	private $path;
	private $route;
	private $className;
	private $method;
	private $filter;
	private $classMethod = array();
	private $classTemplate = '';
	private $isInstalled = false;
	private $_Abstract;
	private $_instance = array();
	public $isMethodAccessible = array();

	public function __construct( $autoload = true ){

		$this->fileSystem = new \Library\FileSystem;
		if( $autoload )
			$this->load();
	}

	public function getClass(){

		return $this->className;
	}

	public function getMethod(){

		return $this->method;
	}

	public static function getLoader(){

		return \Library\Component\Register::getRegister('LOADER');
	}

	public static function clear(){

		\Library\Component\Register::deleteRegister('LOADER');
	}

	private function load(){
		
		$subObject = debug_backtrace(false, 3)[2];
		$subClass = $subObject['class'];
		$file = $subObject['file'];

		$reflection = new \ReflectionClass($subClass);
		$namespace = $reflection->getNamespaceName();
		$routeName = strtolower($namespace);
		//$route = $_GET['route'] ? strtolower($_GET['route']) : strtolower(\Conf\Conf::_DEFAULT_ROUTE);

		$this->setNamespace( $namespace );
		$this->setPath( dirname($file) );
		$this->setRoute( $_GET['route'] );
		$this->setClass( $_GET[$routeName] );
		$this->setMethod( $_GET['method'] );
		$this->setFilter( $routeName, array($routeName => $_GET[$routeName], 'method' => $_GET['method']) );//if method called statically or in first page then it will be not displayed over url request
		
		$array = \Library\Component\Register::getRegister('LOADER');
		$classMethod = $this->getObject( $array[0] );
		\Library\Component\Register::setRegister('LOADER', array($classMethod, $this->classTemplate), false);
	}

	private function setNamespace( $namespace ){

		$this->namespace = $namespace;
	}

	private function setPath( $path ){

		if( $path )
			$this->path = $path;
	}

	private function setRoute( $route ){

		if( $route )
			$this->route = $route;
	}

	private function setClass( $class ){

		if( $class )
			$this->className = $class;
	}

	private function setMethod( $method ){

		if( $method )
			$this->method = $method;
		else{

			$this->method = 'Index';
			if( _APPLICATION_ENV == 'admin' )
				$this->method = 'Index_Admin';
		}
	}

	private function setFilter( $routeName, $filter ){

		//check if class exists
		/*if( $routeName && $filter[$routeName] ){

			if( !$abstract = $this->getAbstract($filter[$routeName]) ){

				$this->route = false;
			}
			else{

				$privilege = $abstract::privilege();
				if( $filter['method'] && !$privilege[$filter['method']] ){

					$this->setMethod( '' );
					$filter['method'] = $this->method;
				}
			}
		}*/
		$this->filter = $filter;
	}

	private function setTemplate( $template ){

		if( $template[$this->method] )
			$this->classTemplate = $template[$this->method];
	}

	private function getNamespace( $className ){

		return $this->namespace.'\\'.$className;
	}

	public function getAbstract( $classNamespaceName ){

		if( $this->_Abstract[$classNamespaceName] )
			return $this->_Abstract[$classNamespaceName];
		$name = $classNamespaceName;

		//echo 'LOADER='.$classNamespaceName.'<br>';

		//if its class not class namespace name
		if( !$this->path && class_exists($classNamespaceName) ){

			$reflection = new \ReflectionClass($classNamespaceName);
			list($namespace, $classNamespaceName) = explode('\\', $reflection->getNamespaceName());
			$this->setPath( dirname($reflection->getFileName(), 2) );
			$this->setNamespace( $namespace );
		}

		if( !is_dir($this->path . DIRECTORY_SEPARATOR . $classNamespaceName) ){

			if( \Conf\Conf::_DEV_MODE )
				die( 'Directory not found: '. $this->path . DIRECTORY_SEPARATOR . $classNamespaceName );
			else
				return false;
		}

		if( \Conf\Conf::_DEV_MODE ){

			if( is_file($this->path . DIRECTORY_SEPARATOR . $name . '/composer.json') ){
				
				if( is_file($this->path . DIRECTORY_SEPARATOR . $name . '/_Abstract.php') ){

					if( !class_Exists($this->getNamespace( $classNamespaceName.'\\_Abstract' )) )
						return false;
				}
				else
					return false;
			}
			else{

				if( !class_Exists($this->getNamespace( $classNamespaceName.'\\_Abstract' )) )
					die( '<b>'.$this->getNamespace( ''.$classNamespaceName.'\\_Abstract' ).'</b> not found <br><br>EXAMPLE:<br>namespace '.$this->getNamespace( $classNamespaceName ).';<br>abstract class _Abstract implements \Library_Interface_Abstract{' );
			}
		}
		else{

			if( !class_Exists($this->getNamespace( $classNamespaceName.'\\_Abstract' )) )
				return false;
		}

		/*if( !class_Exists($this->getNamespace( $classNamespaceName.'\\_Abstract' ), false) ){

			if( \Conf\Conf::_DEV_MODE )
				die( '<b>'.$this->getNamespace( ''.$classNamespaceName.'\\_Abstract' ).'</b> not found <br><br>EXAMPLE:<br>namespace '.$this->getNamespace( $classNamespaceName ).';<br>abstract class _Abstract implements \Library_Interface_Abstract{' );
			else
				return false;
		}*/

		$this->_Abstract[$name] = $this->getNamespace( $classNamespaceName.'\\_Abstract' );
		return $this->_Abstract[$name];
	}

	public function loadAbstract( $abstractObj ){

		return array($abstractObj::register(), $abstractObj::privilege());
	}

	public function setByprivilege( $privilege, $class, $method, $attributes ){

		if( \Library\Permission::getPrivilege( $privilege, $class, $method ) ){

			$className = $this->getNamespace( $class.'\\Index' );
			$position = $attributes['position'] ? $attributes['position'] : 'none';

			//if some problems then remove
			if( !$this->_instance[$className] )
				$this->_instance[$className] = new $className();
			$this->classMethod[$position][] = array('classname' => $class, 'method' => $method, 'class' => $this->_instance[$className]);

			//add this if some problems
			//$this->classMethod[$position][] = array('classname' => $class, 'method' => $method, 'class' => new $className());
			$this->isMethodAccessible[$class][$method] = true;
		}
	}

	private function getByFilter( $attributes ){

		if( $this->filter ){

			if( $attributes['is_static'] == true || ($attributes['is_first_page'] == true && !$this->route) ){

				return true;
			}
			return false;
		}
		return true;
	}

	private function getByFilterName( $abstract, $attributes ){

		if( $this->filter ){

			$arrayKeys = $this->filter;
			$arrayValues = $this->filter;

			if( ($attributes['is_route'] == true || ($attributes['is_route'] == false && _SHELL)) && strlen(implode(array_intersect_key($arrayValues, $arrayKeys))) ){

				if( $attributes['is_route'] == true )
					$this->setTemplate( $abstract::template() );
				return true;
			}
			return false;
		}
		return true;
	}

	public function getObject( $array ){

		$classMethod = array();

		if( $this->className && $classMethod = $this->getByClassName() )
			return $classMethod;

		$classMethod = $this->getByDirectory();
		if( $array != null )
			$classMethod = array_merge_recursive($array, $classMethod);

		return $classMethod;
	}

	private function getByClassName(){

		if( !$abstract = $this->getAbstract( $this->className ) )
			return array();

		list($register, $privilege) = $this->loadAbstract( $abstract );

		if( $this->method && isset($register[$this->method]) ){

			if( $this->getByFilterName( $abstract, $register[$this->method]) )
				$this->setByprivilege( $privilege, $this->className, $this->method, $register[$this->method] );

			if( $this->classMethod['none'] )
				return array('none' => $this->classMethod['none']);
		}
		return array();
	}

	private function getByDirectory(){

		$list = array();
		$list = $this->fileSystem->scandir( $this->path, false, '', true );

		foreach( $list as $class ){

			if( !$abstract = $this->getAbstract( $class ) )
				continue;

			list($register, $privilege) = $this->loadAbstract( $abstract );

			if( $register[_APPLICATION_ENV] ){

				foreach($register[_APPLICATION_ENV] as $method => $attributes){

					if( $this->route ){

						if( $this->getByFilter( $attributes ) )
							$this->setByprivilege( $privilege, $class, $method, $attributes );

						if( $this->getByFilterName( $abstract, $attributes ) && $this->className == $class && $this->method == $method )
							$this->setByprivilege( $privilege, $class, $method, $attributes );
					}
					else{

						if( $this->getByFilter( $attributes ) )
							$this->setByprivilege( $privilege, $class, $method, $attributes );
					}
				}
			}
		}
		return $this->classMethod;
	}
}

?>